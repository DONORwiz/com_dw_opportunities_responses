<?php

defined('_JEXEC') or die;

JFactory::getLanguage()->load('com_dw_opportunities_responses');

$response = $displayData['response'];

//Check if user can edit response----------------------------------------
$user = JFactory::getUser();
$canEditResponse = $user->authorise('core.edit', 'com_dw_opportunities_responses');
if (!$canEditResponse && $user->authorise('core.edit.own', 'com_dw_opportunities_responses'))
	$canEditResponse = $user->id == $response->created_by;

?>

<?php 

if ( $canEditResponse ) {

	echo JLayoutHelper::render(
		'popup-button', 
		array (
			'buttonText' => JText::_('COM_DW_OPPORTUNITIES_RESPONSES_WIZARD_EDIT'),
			'buttonIcon' => 'uk-icon-edit uk-icon-small uk-margin-small-right',
			'buttonType' => 'uk-button uk-button-blank uk-button-small uk-width-1-1',
			'buttonID' => 'responseform_'.$response->id,
			'popupParams' => array (
								'header' => '<h2>'.JText::_('COM_DW_OPPORTUNITIES_RESPONSES_EDIT').'</h2>',
								'footer' => '',
							),
			'layoutPath' => JPATH_ROOT .'/components/com_dw_opportunities_responses/layouts/wizard',
			'layoutName' => 'response',
			'layoutParams' => array( 'response' => $response )
		), 
		JPATH_ROOT .'/components/com_donorwiz/layouts/popup' , 
		null ); 
}

?>