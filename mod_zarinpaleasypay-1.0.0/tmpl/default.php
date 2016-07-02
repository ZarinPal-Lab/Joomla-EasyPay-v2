<?php
defined('_JEXEC') or die();

$en2fa = function ($number) {
    $english = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    return str_replace($english, $persian, $number);
};
?>
<div dir="rtl" style="text-align: center;background-color: rgb(243, 243, 243);padding:10px">
    <div style="text-align: center;padding-bottom:15px">
        <?php if ($form['image']) { ?>
            <img src="<?php echo JURI::base() . $form['image']; ?>"
            style="width: 80%; margin-top:10px"/>
        <?php } ?>
        <p style="text-align: right;margin-top:20px;padding-right: 10px;line-height: 23px;">
            <?php echo $form['description']; ?>
        </p>
    </div>
    <div style="text-align: center;">
        <?php
        $action = "index.php?option=com_zarinpaleasypay&view=pay&form_id={$form['id']}"
        ?>
        <form action="<?php echo $action; ?>" method="post">
            <?php
            $label = JText::_('MOD_ZARINPALEASYPAY_LBL_PAY');
            $label .= '  ' . $en2fa(number_format($form['amount'])) . '  ';
            $label .= JText::_('MOD_ZARINPALEASYPAY_CURRENCY_' . strtoupper($form['currency']));
            $image = JURI::base(true) . '/media/mod_zarinpaleasypay/default.png';
            ?>
            <button type="submit"
                   style="text-align:center;line-height: 30px; padding-right:20px; padding-left:20px; background-color: rgba(0, 5, 18, 0.6);
                   border:2px solid rgb(255, 207, 0); -webkit-border-radius:5px ;-moz-border-radius:5px ;border-radius:5px ; color: white">
                <img src="<?php echo $image; ?>" style="max-width: 22px; margin-bottom: 4px; margin-left:10px; vertical-align: middle"/>
                <span><?php echo $label; ?></span>
                </button>
        </form>
    </div>
</div>
