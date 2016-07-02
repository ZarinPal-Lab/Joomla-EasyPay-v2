<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Zarinpaleasypay
 * @author     mohsen ranjbar <mimrahe@gmail.com>
 * @copyright  2016 mohsen ranjbar
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Class ZarinpaleasypayFrontendHelper
 *
 * @since  1.6
 */
class ZarinpaleasypayHelpersTranslate
{
    public static function translate($prefix, $key)
    {
        $key = 'COM_ZARINPALEASYPAY_' . strtoupper($prefix . '_' . $key);
        return JText::_($key);
    }
}
