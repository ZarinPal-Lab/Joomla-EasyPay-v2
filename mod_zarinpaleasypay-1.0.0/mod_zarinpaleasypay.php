<?php
defined('_JEXEC') or die();

require_once dirname(__FILE__) . '/helper.php';

$form_id = $params->get('form', '1');
$form = ModZarinpalEasyPayHelper::getFormItems($form_id);
$module->title = $form['alias'];
require JModuleHelper::getLayoutPath('mod_zarinpaleasypay');