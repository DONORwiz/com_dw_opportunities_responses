<?php

defined('_JEXEC') or die;

JFactory::getLanguage()->load('com_dw_opportunities_responses');

$response = $displayData['response'];
$class = ( isset ( $displayData['class'] ) ) ? $displayData['class'] : '' ;

$user = JFactory::getUser();

//Check if user can edit.state response----------------------------------------
$canEditStateResponse = $user->authorise('core.edit.state', 'com_dw_opportunities_responses');
?>

<?php if ( $canEditStateResponse && $response && isset ( $response->state )  ) : ?>

<a class="uk-button uk-button-link uk-text-muted trashed <?php echo $class;?>" href="#" title="<?php echo JText::_( 'COM_DW_OPPORTUNITIES_RESPONSES_OPPORTUNITY_WIZARD_TRASH' );?>" data-uk-tooltip>
	<i class="uk-icon-trash-o uk-icon-small"></i>
</a>

<?php endif; ?>