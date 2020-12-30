<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				JoomlaBoat.com
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.76
	@build			19th July, 2018
	@created		24th May, 2018
	@package		Custom Tables
	@subpackage		edit.php
	@author			Ivan Komlev <https://joomlaboat.com>
	@copyright		Copyright (C) 2018. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
$componentParams = JComponentHelper::getParams('com_customtables');

require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_customtables'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'layouteditor.php');

$onPageLoads=array();



?>
<script type="text/javascript">
	// waiting spinner
	var outerDiv = jQuery('body');
	jQuery('<div id="loading"></div>')
		.css("background", "rgba(255, 255, 255, .8) url('components/com_customtables/assets/images/import.gif') 50% 15% no-repeat")
		.css("top", outerDiv.position().top - jQuery(window).scrollTop())
		.css("left", outerDiv.position().left - jQuery(window).scrollLeft())
		.css("width", outerDiv.width())
		.css("height", outerDiv.height())
		.css("position", "fixed")
		.css("opacity", "0.80")
		.css("-ms-filter", "progid:DXImageTransform.Microsoft.Alpha(Opacity = 80)")
		.css("filter", "alpha(opacity = 80)")
		.css("display", "none")
		.appendTo(outerDiv);
	jQuery('#loading').show();
	// when page is ready remove and show
	jQuery(window).load(function() {
		jQuery('#customtables_loader').fadeIn('fast');
		jQuery('#loading').hide();
	});
</script>


<div id="customtables_loader" style="display: none;">
<form action="<?php echo JRoute::_('index.php?option=com_customtables&layout=edit&id='.(int) $this->item->id.$this->referral); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">

	<?php echo JLayoutHelper::render('layouts.details_above', $this); ?>
<div class="form-horizontal">

	<?php //echo JHtml::_('bootstrap.startTabSet', 'layoutsTab', array('active' => 'details')); ?>

	<?php //echo JHtml::_('bootstrap.addTab', 'layoutsTab', 'details', JText::_('COM_CUSTOMTABLES_LAYOUTS_DETAILS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">

				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('layoutcode'); ?></div>
					<?php

						$textareacode='<textarea name="jform[layoutcode]" id="jform_layoutcode" filter="raw" style="width:100%" rows="30">'.$this->item->layoutcode.'</textarea>';


						$textareaid='jform_layoutcode';
						$textareatabid="layouttagbox";
						$typeboxid="jform_layouttype";

						//$tags=array();

						//$tags[0]=[];
						//$tags[1]=get_PageLayout_Tagsets();
						//$tags[2]=get_ItemLayout_Tagsets();//


						echo renderEditor($textareacode,$textareaid,$typeboxid,$textareatabid,$onPageLoads);

					?>

				</div>



			</div>
		</div>
	<?php //echo JHtml::_('bootstrap.endTab'); ?>

	<?php /* if ($this->canDo->get('core.delete') || $this->canDo->get('core.edit.created_by') || $this->canDo->get('core.edit.state') || $this->canDo->get('core.edit.created')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'layoutsTab', 'publishing', JText::_('COM_CUSTOMTABLES_LAYOUTS_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('layouts.publishing', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('layouts.publlshing', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; */ ?>

	<?php /* if ($this->canDo->get('core.admin')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'layoutsTab', 'permissions', JText::_('COM_CUSTOMTABLES_LAYOUTS_PERMISSION', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<fieldset class="adminform">
					<div class="adminformlist">
					<?php foreach ($this->form->getFieldset('accesscontrol') as $field): ?>
						<div>
							<?php echo $field->label; echo $field->input;?>
						</div>
						<div class="clearfix"></div>
					<?php endforeach; ?>
					</div>
				</fieldset>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; */ ?>

	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<div>
		<input type="hidden" name="task" value="layouts.edit" />
		<?php echo JHtml::_('form.token'); ?>
	<!--</div>-->
	</div>
</div>

<div class="clearfix"></div>
<?php echo JLayoutHelper::render('layouts.details_under', $this);



echo render_onPageLoads($onPageLoads,$this->item->layouttype);

//echo $this->getFieldsJS();

$this->getMenuItems();



?>
</form>
</div>
