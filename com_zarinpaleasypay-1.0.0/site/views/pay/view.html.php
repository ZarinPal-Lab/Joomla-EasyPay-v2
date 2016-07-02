<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Zarinpaleasypay
 * @author     mohsen ranjbar <mimrahe@gmail.com>
 * @copyright  2016 mohsen ranjbar
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 *
 * @since  1.6
 */
class ZarinpaleasypayViewPay extends JViewLegacy
{
	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		// load form data
		$form_id = $input->getInt('form_id');

		JTable::addIncludePath(JPATH_SITE . '/components/com_zarinpaleasypay/tables');
		$this->form = JTable::getInstance('Form', 'ZarinpaleasypayTable', array());
		$this->form->load($form_id);
		// load component params
		$this->params = JComponentHelper::getParams('com_zarinpaleasypay');
		parent::display($tpl);
	}
}
