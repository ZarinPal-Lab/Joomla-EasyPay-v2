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
class ZarinpaleasypayHelpersRequest
{
    public static function request($type, $context)
    {
        $params = JComponentHelper::getParams('com_zarinpaleasypay');
        $prefix = (bool)$params->get('test_mode') ? 'sandbox' : 'www';
        $host = (bool)$params->get('mirror') ? 'zarin.link' : 'zarinpal.com';

        try {
            $client = new SoapClient("https://{$prefix}.{$host}/pg/services/WebGate/wsdl", ['encoding' => 'UTF-8']);

            $type = 'Payment' . ucfirst($type);
            return $client->$type($context);

        } catch (SoapFault $e) {
            return false;
        }
    }

    public static function fixAmount($amount, $currency)
    {
        if($currency == 'rial'){
            $amount /= 10;
        }
        return (int)$amount;
    }
}
