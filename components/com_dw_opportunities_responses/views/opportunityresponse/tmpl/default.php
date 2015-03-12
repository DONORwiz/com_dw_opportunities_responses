<?php
/**
 * @version     1.0.1
 * @package     com_dw_opportunities_responses
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Charalampos Kaklamanos <dev.yesinternet@gmail.com> - http://www.yesinternet.gr
 */
// no direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_dw_opportunities_responses');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_dw_opportunities_responses')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">
        <table class="table">
            <tr>
			<th><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_FORM_LBL_OPPORTUNITYRESPONSE_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_FORM_LBL_OPPORTUNITYRESPONSE_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_FORM_LBL_OPPORTUNITYRESPONSE_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_FORM_LBL_OPPORTUNITYRESPONSE_CREATED'); ?></th>
			<td><?php echo $this->item->created; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_FORM_LBL_OPPORTUNITYRESPONSE_MODIFIED_BY'); ?></th>
			<td><?php echo $this->item->modified_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_FORM_LBL_OPPORTUNITYRESPONSE_MODIFIED'); ?></th>
			<td><?php echo $this->item->modified; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_FORM_LBL_OPPORTUNITYRESPONSE_OPPORTUNITY_ID'); ?></th>
			<td><?php echo $this->item->opportunity_id; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_FORM_LBL_OPPORTUNITYRESPONSE_MESSAGE'); ?></th>
			<td><?php echo $this->item->message; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_FORM_LBL_OPPORTUNITYRESPONSE_PARAMETERS'); ?></th>
			<td><?php echo $this->item->parameters; ?></td>
</tr>

        </table>
    </div>
    <?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_dw_opportunities_responses&task=opportunityresponse.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_DW_OPPORTUNITIES_RESPONSES_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_dw_opportunities_responses')):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_dw_opportunities_responses&task=opportunityresponse.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_DW_OPPORTUNITIES_RESPONSES_DELETE_ITEM"); ?></a>
								<?php endif; ?>
    <?php
else:
    echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_ITEM_NOT_LOADED');
endif;
?>
