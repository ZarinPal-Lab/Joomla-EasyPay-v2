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
class ZarinpaleasypayViewFinish extends JViewLegacy
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
		$this->finishStatus = $this->get('Finish');
		if( array_key_exists('success', $this->finishStatus))
		{
			$session =& JFactory::getSession();
			$this->callback_url = JURI::base(true) . $session->get('zarinpal.form_callback');
			$session->clear('zarinpal.form_callback');
		}

		parent::display($tpl);
	}
}
