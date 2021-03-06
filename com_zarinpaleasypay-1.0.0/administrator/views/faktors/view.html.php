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
 * View class for a list of Zarinpaleasypay.
 *
 * @since  1.6
 */
class ZarinpaleasypayViewFaktors extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

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
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		ZarinpaleasypayHelpersZarinpaleasypay::addSubmenu('forms', 'forms');
		ZarinpaleasypayHelpersZarinpaleasypay::addSubmenu('faktors', 'faktors', true);
		ZarinpaleasypayHelpersZarinpaleasypay::addSubmenu('payments', 'payments');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = ZarinpaleasypayHelpersZarinpaleasypay::getActions();

		JToolBarHelper::title(JText::_('COM_ZARINPALEASYPAY_TITLE_FAKTORS'), 'faktors.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/';
/*
		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('.add', 'JTOOLBAR_NEW');
				JToolbarHelper::custom('faktors.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('.edit', 'JTOOLBAR_EDIT');
			}
		}
*/


		if ($canDo->get('core.edit.state'))
		{

			/* if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('faktors.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('faktors.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			else */
			if (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'faktors.delete', 'JTOOLBAR_DELETE');
			}
			/*
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('faktors.archive', 'JTOOLBAR_ARCHIVE');
			}
			*/
			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('faktors.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}

		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'faktors.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('faktors.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_zarinpaleasypay');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_zarinpaleasypay&view=faktors');

		$this->extra_sidebar = '';
	}

	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => JText::_('JGRID_HEADING_ID'),
			'a.`form_id`' => JText::_('COM_ZARINPALEASYPAY_FAKTORS_FORM_ID'),
			'a.`first_name`' => JText::_('COM_ZARINPALEASYPAY_FAKTORS_FIRST_NAME'),
			'a.`last_name`' => JText::_('COM_ZARINPALEASYPAY_FAKTORS_LAST_NAME'),
			'a.`email`' => JText::_('COM_ZARINPALEASYPAY_FAKTORS_EMAIL'),
			'a.`mobile`' => JText::_('COM_ZARINPALEASYPAY_FAKTORS_MOBILE'),
			'a.`description`' => JText::_('COM_ZARINPALEASYPAY_FAKTORS_DESCRIPTION'),
			'a.`created_at`' => JText::_('COM_ZARINPALEASYPAY_FAKTORS_CREATED_AT'),
		);
	}
}
