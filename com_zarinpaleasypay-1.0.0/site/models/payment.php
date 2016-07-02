<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Zarinpaleasypay
 * @author     mohsen ranjbar <mimrahe@gmail.com>
 * @copyright  2016 mohsen ranjbar
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * Methods supporting a list of Zarinpaleasypay records.
 *
 * @since  1.6
 */
class ZarinpaleasypayModelPayment extends JModelLegacy
{
/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	public function start()
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$params = JComponentHelper::getParams('com_zarinpaleasypay');
		try{
			// get form data
			$form_id = $jinput->getInt('form_id');
			$form = $this->getForm($form_id);

			// create a faktor for this payment
			$faktor = $this->setFaktor($form, $jinput);

			// create a payment table row for this payment
			$payment = $this->setPayment($faktor->id);

			// check payment request parameters and send request
			$this->goPayment($jinput, $params, $form, $app, $payment->id);

		} catch (Exception $e){
			$referrer = $_SERVER['HTTP_REFERER'];
			$this->doException($e, $referrer, $app);
			exit;
		}
	}

	private function doException($exception, $referrer, $app)
	{
		$header = ZarinpaleasypayHelpersTranslate::translate('title', 'error') . '<br>';
		$error = ZarinpaleasypayHelpersTranslate::translate('error', $exception->getMessage());
		$error = $header . $error;
		$app->redirect($referrer, $error, 'error');
	}

	public function getTable($type = '', $prefix = 'ZarinpaleasypayTable', $config = array())
	{
		JTable::addIncludePath(JPATH_SITE . '/components/com_zarinpaleasypay/tables');
		return JTable::getInstance($type, $prefix, $config);
	}

	private function getForm($form_id)
	{
		$form = $this->getTable('Form');
		$form->load($form_id);

		if(!$form)
			throw new Exception('form_not_found');

		return $form;
	}

	private function setFaktor($form, $jinput)
	{
		$form_id = $form->id;
		$first_name = $jinput->getString('first_name');
		$last_name = $jinput->getString('last_name');
		$email = $jinput->getString('email');
		$mobile = $jinput->getString('mobile');
		$description = $jinput->getString('description');
		$context = compact(
			'form_id', 'first_name', 'last_name', 'email', 'mobile', 'description'
		);

		$faktor = $this->getTable('Faktor');
		foreach ($context as $key => $value)
		{
			$faktor->{$key} = $value;
		}
		$faktor->store();
		if(!$faktor)
			throw new Exception('saving_faktor_failed');

		return $faktor;
	}

	private function setPayment($faktor_id)
	{
		$payment = $this->getTable('Payment');
		$payment->faktor_id = (int)$faktor_id;
		$payment->store();
		if(!$payment)
			throw new Exception('saving_payment_failed');

		return $payment;
	}

	private function goPayment($jinput, $params, $form, $app, $payment_id)
	{
		$MerchantID = trim( $params->get('merchant_id'));
		if(empty($MerchantID))
			throw new Exception('merchant_id_is_empty');

		$Amount = ZarinpaleasypayHelpersRequest::fixAmount($form->amount, $form->currency);
		$Description = $form->alias;
		$Email = $jinput->getString('email');
		$Mobile = $jinput->getString('mobile');
		$CallbackURL = JURI::base() . 'index.php?option=com_zarinpaleasypay&view=finish';

		$requestContext = compact(
			'MerchantID', 'Amount', 'Description', 'Email', 'Mobile', 'CallbackURL'
		);
		$request = ZarinpaleasypayHelpersRequest::request('request', $requestContext);

		if(!$request)
			throw new Exception('connection_failed');

		$status = $request->Status;
		if($status != 100){ // must setting payment status too
			$this->setPaymentStatus($payment_id, $status);
			throw new Exception('status_' . $status);
		}

		if($status == 100)
		{
			// preparing to going to zarinpal payment
			$Authority = $request->Authority;
			$prefix = $params->get('test_mode') ? 'sandbox' : 'www';
			$postfix = $jinput->getString('payment_type') == 'zaringate' ? '/ZarinGate' : '';
			// store payment id and amount in session
			$session =& JFactory::getSession();
			$session->set('zarinpal.amount', $Amount);
			$session->set('zarinpal.payment_id', $payment_id);
			$session->set('zarinpal.form_callback', $form->callback_url);
			$session->set('zarinpal.form_alias', $form->alias);
			$session->set('zarinpal.payer_email', $Email);
			$session->set('zarinpal.payer_name', $jinput->getString('first_name') . ' ' . $jinput->getString('last_name'));
			// redirect to zarinpal payment gateway
			$redirect = "https://{$prefix}.zarinpal.com/pg/StartPay/{$Authority}{$postfix}";
			$app->redirect($redirect);
			exit;
		}
	}

	private function setPaymentStatus($payment_id, $status_id)
	{
		$payment = $this->getTable('Payment');
		$payment->load((int)$payment_id);
		$payment->status = 'failed';
		if($status_id == 100){
			$payment->status = 'completed';
		}
		$payment->status_id = $status_id;
		$payment->store();
	}

}
