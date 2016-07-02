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

// Import CSS
//$document = JFactory::getDocument();
//$document->addStyleSheet(JUri::root() . 'media/com_zarinpaleasypay/css/form.css');

$translate = function ($key) {
    return JText::_('COM_ZARINPALEASYPAY_' . strtoupper($key));
};
$en2fa = function ($number) {
    $english = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    return str_replace($english, $persian, $number);
};

?>
<div class="note">
    <img alt="zarinpal payment"
         src="<?php echo JURI::base(true) . '/media/com_zarinpaleasypay/image/zarinpal.png'; ?>"
         width="100" style="float:right"/>
    <?php if (array_key_exists('error', $this->finishStatus)) { ?>
        <p style="line-height:20px;color: darkred;display:inline-block;margin-right: 15px;">
            <?php echo $translate('error_payment_failed') ?>
            <br>
            <span><?php echo $translate('title_error') ?></span>
            <strong style="padding-right:10px"><?php echo $this->finishStatus['error']; ?></strong>
            <br>
            <span><?php echo $translate('contact_admin'); ?></span>
        </p>
    <?php } else { ?>
        <p style="line-height:20px;color: darkgreen;display:inline-block;margin-right: 15px;">
            <?php echo $translate('payment_succeed') ?>
            <br>
            <span><?php echo $translate('show_ref_id') ?></span>
            <strong style="padding-right:10px"><?php echo $en2fa($this->finishStatus['success']); ?></strong>
            <br>
            <span><?php echo $translate('thanks'); ?></span>
            <span style="padding-right:10px">(&nbsp;<a href="<?php echo $this->callback_url; ?>">
                    <?php echo $translate('continue'); ?>
                </a>&nbsp;)</span>
        </p>
    <?php } ?>
</div>