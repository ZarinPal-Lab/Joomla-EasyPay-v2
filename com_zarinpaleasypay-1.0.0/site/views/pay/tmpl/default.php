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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_zarinpaleasypay/css/form.css');
$document->addStyleSheet(JUri::root() . 'media/com_zarinpaleasypay/css/pay.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function () {
        document.formvalidator.setHandler('mobile', function (value) {
            regex = /^09\d{9}$/;
            return regex.test(value);
        });
    });
</script>
<?php
$translate = function ($key) {
    return JText::_('COM_ZARINPALEASYPAY_' . strtoupper($key));
};
$en2fa = function ($number) {
    $english = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    return str_replace($english, $persian, $number);
};
$image = JURI::base(true) . '/media/com_zarinpaleasypay/image/zarinpal.png';
$action = "index.php?option=com_zarinpaleasypay&task=start";
?>
<div dir="rtl" style="text-align: center;" class="pay-containor">
    <div class="pay-zarinpal-header">
        <img src="<?php echo $image; ?>"/>
        <h3><?php echo $translate('title_payment'); ?></h3>
    </div>
    <div class="pay-form-header">
        <div>
            <h2>
                <?php echo $this->form->alias; ?>
            </h2>
            <p>
                <?php echo $translate('lbl_pay') . ' ' . $en2fa(number_format($this->form->amount)) . ' ' . $translate($this->form->currency); ?>
            </p>
        </div>
        <div>
            <p>
                <?php echo $this->form->description; ?>
            </p>
        </div>
    </div>
    <div class="pay-form-section">
        <form action="<?php echo $action; ?>" method="post" class="form-validate">
            <input type="hidden" name="form_id" value="<?php echo $this->form->id; ?>"/>
            <fielset class="pay-fieldset-1">
                <label for="first_name"><?php echo $translate('lbl_first_name'); ?></label>
                <input type="text" id="first_name" name="first_name" value="" class="required"/>
                <br>
                <label for="last_name"><?php echo $translate('lbl_last_name'); ?></label>
                <input type="text" id="last_name" name="last_name" value="" class="required"/>
                <br>
                <label for="email"><?php echo $translate('lbl_email'); ?></label>
                <input type="email" id="email" name="email" value="" placeholder="mimrahe@gmail.com"
                       class="required validate-email"/>
                <br>
                <label for="mobile"><?php echo $translate('lbl_mobile'); ?></label>
                <input type="text" id="mobile" name="mobile" value="" pattern="09[\d]{9}" placeholder="09309147136"
                       class="required validate-mobile"/>
                <br>
                <label for="description"><?php echo $translate('lbl_description'); ?></label>
                <textarea id="description" name="description"></textarea>
            </fielset>
            <fieldset class="pay-gate">
                <?php
                $webgateActived = $this->params->get('webgate');
                $zaringateActived = $this->params->get('zaringate');
                $bothGates = $webgateActived && $zaringateActived;
                ?>
                <?php if ($bothGates) { ?>
                    <h3><?php echo $translate('lbl_gate_type'); ?></h3>
                    <label for="webgate"><?php echo $translate('webgate'); ?></label>
                    <input type="radio" id="webgate" name="payment_type" value="webgate"/>
                    <span class="pay-gate-seperator"></span>
                    <input type="radio" id="zaringate" name="payment_type" value="zaringate"/>
                    <label for="zaringate"><?php echo $translate('zaringate'); ?></label>
                <?php } else { ?>
                    <input type="hidden" name="payment_type"
                           value="<?php echo($zaringateActived ? 'zaringate' : 'webgate'); ?>"/>
                <?php } ?>
            </fieldset>
            <input type="submit" value="<?php echo $translate('lbl_go_payment'); ?>"
                   class="pay-button validate"/>

            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>
<!--
<form
	action="<?php //echo JRoute::_('index.php?option=com_zarinpaleasypay&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="form-form" class="form-validate">

	<div class="form-horizontal">
		<?php //echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php //echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_ZARINPALEASYPAY_TITLE_FORM', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

									<input type="hidden" name="jform[id]" value="<?php //echo $this->item->id; ?>" />
				<?php /*echo $this->form->renderField('alias'); ?>
				<?php echo $this->form->renderField('description'); ?>
				<?php echo $this->form->renderField('amount'); ?>
				<?php echo $this->form->renderField('currency'); ?>
				<?php echo $this->form->renderField('callback_url'); ?>
				<?php echo $this->form->renderField('image');*/ ?>

<?php /*
					<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
					<?php endif; ?>
 */ ?>
				</fieldset>
			</div>
		</div>
		<?php //echo JHtml::_('bootstrap.endTab'); ?>

		<?php //echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php //echo JHtml::_('form.token'); ?>

	</div>
</form>
-->