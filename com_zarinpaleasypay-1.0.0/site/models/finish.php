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
class ZarinpaleasypayModelFinish extends JModelLegacy
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

	public function getTable($type = '', $prefix = 'ZarinpaleasypayTable', $config = array())
	{
		JTable::addIncludePath(JPATH_SITE . '/components/com_zarinpaleasypay/tables');
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getFinish()
	{
		// get usefull tools
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$params = JComponentHelper::getParams('com_zarinpaleasypay');

		$session =& JFactory::getSession();
		$payment_id = $session->get('zarinpal.payment_id');
		// clear session
		$session->clear('zarinpal.payment_id');

		// verify payment via helper
		try{
			$MerchantID = $params->get('merchant_id');
			$Authority = $jinput->getString('Authority');
			$Amount = $session->get('zarinpal.amount');
			// clear session
			$session->clear('zarinpal.amount');

			$verifyContext = compact('MerchantID', 'Authority', 'Amount');
			$verify = ZarinpaleasypayHelpersRequest::request('verification', $verifyContext);

			if(!$verify)
				throw new Exception('payment_failed');

			$status = $verify->Status;
			if($status != 100) {
				$this->setPaymentStatus($payment_id, $status);
				throw new Exception('status_' . $status);
			}

			if($status == 100)
			{
				$ref_id = $verify->RefID;
				$this->setPaymentStatus($payment_id, $status, $ref_id);
				// send email to user containing payment information and form defined callback

				$this->sendEmail($ref_id);
				return [
					'success' => $ref_id
				];
			}
		} catch (Exception $e) {
			return [
				'error' => ZarinpaleasypayHelpersTranslate::translate('error', $e->getMessage())
			];
		}
	}

	private function setPaymentStatus($payment_id, $status_id, $ref_id = null)
	{
		$payment = $this->getTable('Payment');
		$payment->load((int)$payment_id);
		$payment->status = 'failed';
		if($status_id == 100){
			$payment->status = 'completed';
		}
		$payment->status_id = $status_id;
		if($ref_id){
			$payment->ref_id = $ref_id;
		}
		$payment->store();
	}

	public function sendEmail($ref_id)
	{
		$session =& JFactory::getSession();

		$email = $session->get('zarinpal.payer_email');
		$link = JURI::base() . $session->get('zarinpal.form_callback');
		$form_alias = $session->get('zarinpal.form_alias');
		$payer_name = $session->get('zarinpal.payer_name');

		$session->clear('zarinpal.payer_email');
		$session->clear('zarinpal.form_callback');
		$session->clear('zarinpal.form_alias');

		$config = JFactory::getConfig();
		$mailer = JFactory::getMailer();
		// get site name
		$site_name = $config->get( 'sitename' );
		$template = array(
			'payer_name' => $payer_name,
			'ref_id' => $ref_id,
			'link' => $link,
			'site_name' => $site_name
		);
		// set sender
		$sender = array(
			$config->get( 'mailfrom' ),
			$config->get( 'fromname' )
		);
		$mailer->setSender($sender);
		// set reciever
		$recipient = $email;
		$mailer->addRecipient($recipient);
		// set subject
		$subject = ZarinpaleasypayHelpersTranslate::translate('email', 'subject');
		$subject = sprintf($subject, $form_alias, $site_name);
		$mailer->setSubject($subject);
		// email body
		$body = file_get_contents(JPATH_SITE . '/media/com_zarinpaleasypay/html/email.html');
		foreach ($template as $item => $value) {
			$body = str_replace('{' . $item . '}', $value, $body);
		}
		$mailer->isHTML(true);
		$mailer->setBody($body);
		// send mail
		$mailer->send();
	}

}
