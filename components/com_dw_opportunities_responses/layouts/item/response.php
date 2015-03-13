<?php

// no direct access
defined('_JEXEC') or die;

JFactory::getLanguage()->load('com_dw_opportunities_responses');
JFactory::getLanguage()->load('com_dw_opportunities_responses_statuses');

include_once JPATH_ROOT.'/components/com_community/libraries/core.php';
include_once JPATH_ROOT.'/components/com_community/libraries/messaging.php';

$response = $displayData['response'];

$response -> status = ( !$response -> status || ( $response -> status_state=='-1' || $response -> status_state=='-2' ) ) ? 'pending' : $response -> status ;

// JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_dw_opportunities/models', 'Dw_opportunitiesModel');
// $opportunityModel = JModelLegacy::getInstance('DwOpportunity', 'Dw_opportunitiesModel', array('ignore_request' => true));	
// $opportunity = $opportunityModel -> getData( $response -> opportunity_id );	

//Get opportunity from user state
$opportunity = JFactory::getApplication()->getUserState('com_dw_opportunities.opportunity.id'.$response -> opportunity_id);	
//Save response to user state
JFactory::getApplication()->setUserState('com_dw_opportunities.opportunity_response.id'.$response->id , $response);	


$response -> status_created_by = ( !$response -> status_created_by ) ? $opportunity ->created_by : $response -> status_created_by ;

//Check if logged in user is the owner of the response
$user = JFactory::getUser();
$isOwner = $response->created_by == $user -> id;

//Check if the user can edit the response
$canEdit = $user->authorise('core.edit', 'com_dw_opportunities_responses');
if (!$canEdit && $user->authorise('core.edit.own', 'com_dw_opportunities_responses'))
	$canEdit = $user->id == $response->created_by;

$canEditStatus = $user->authorise('core.edit', 'com_dw_opportunities_responses_statuses');
if (!$canEditStatus && $user->authorise('core.edit.own', 'com_dw_opportunities_responses_statuses'))
	$canEditStatus = $user->id == $response->status_created_by;



//Create response user object
$cuser = CFactory::getUser( $response->created_by );

?>


<li id="opportunityresponse<?php echo $response->id;?>" class="uk-margin uk-panel uk-panel-box" >

	<div class="uk-grid uk-grid-small">
		
		<div class="uk-width-1-1 uk-width-medium-1-4 uk-text-center">
			
			<a class="uk-display-block" target="_blank" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$response->created_by); ?>">
				<img src="<?php echo $cuser->getThumbAvatar(); ?>" alt="">
			</a>
				
			<div class="name">
				<?php echo $cuser->getDisplayName(); ?>
			</div>
			
			<?php if( isset( $response->parameters['telephone'] ) ) :?>
			<a class="uk-display-block uk-button uk-button-success telephone" href="tel:<?php echo $response->parameters['telephone']; ?>" target="_blank" >
				<i class="uk-icon-phone"></i>
				<?php echo $response->parameters['telephone']; ?>
			</a>
			<?php endif;?>
			
			<?php if( !$isOwner ) :?>
			<a class="uk-display-block uk-button uk-button-primary" onclick="<?php echo CMessaging::getPopup($response->created_by); ?>;return false;" href="#">
				<i class="uk-icon-envelope-o"></i>
				<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_SEND_MESSAGE'); ?>
			</a>
			<?php endif;?>
			
			<a class="uk-display-block uk-button uk-button-link uk-text-muted" target="_blank" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$response->created_by);?>">
				<i class="uk-icon-user"></i>
				<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_VIEW_PROFILE'); ?>
			</a>	
			
		</div>
		
		<div class="uk-width-1-1 uk-width-medium-3-4">
			
			<div class="uk-grid uk-grid-small uk-width-1-1">
				
				<div class="uk-width-1-2">
			
					<div class="created uk-article-meta"><?php echo JFactory::getDate($response->created)->format('D, d M Y'); ?></div>


				</div>
				
				<div class="uk-width-1-2">
				

					<div class="uk-badge  uk-float-right
						<?php if ( $response -> status == 'pending' ) echo 'uk-badge-warning'?>
						<?php if ( $response -> status == 'accepted') echo 'uk-badge-success'?>
						<?php if ( $response -> status == 'declined') echo 'uk-badge-danger'?>

						"
					>
					<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_STATUSES_'.$response -> status);?>
					</div>
					<div class="uk-hidden status"><?php echo $response -> status; ?></div>
					
					
				</div>
			</div>
		<div class="uk-width-1-1">
			<p class="message uk-margin-remove"><?php echo $response->message; ?></p>
		</div>		
		
	</div>
			

		<div class="uk-width-1-1">
			<span class="uk-float-right">
				
				<?php if ($canEdit) :?>
					
					<?php echo JLayoutHelper::render(
						'popup-button', 
						array (
							'buttonText' => JText::_('COM_DW_OPPORTUNITIES_RESPONSES_WIZARD_EDIT'),
							'buttonIcon' => 'uk-icon-edit uk-icon-small uk-margin-small-right',
							'buttonType' => '',
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
					?>				
				
				
				<?php endif;?>
			
			
				<?php if ($canEditStatus) :?>
				
					<?php echo JLayoutHelper::render(
						'popup-button', 
						array (
							'buttonText' => JText::_('COM_DW_OPPORTUNITIES_RESPONSES_WIZARD_EDIT'),
							'buttonIcon' => 'uk-icon-edit uk-icon-small uk-margin-small-right',
							'buttonType' => '',
							'buttonID' => 'responsestatusform_'.$response->status_id,
							'popupParams' => array (
												'header' => '<h2>'.JText::_('COM_DW_OPPORTUNITIES_RESPONSES_EDIT').'</h2>',
												'footer' => '',
											),
							'layoutPath' => JPATH_ROOT .'/components/com_dw_opportunities_responses_statuses/layouts/wizard',
							'layoutName' => 'responsestatus',
							'layoutParams' => array( 'response' => $response )
						), 
						JPATH_ROOT .'/components/com_donorwiz/layouts/popup' , 
						null ); 
					?>
				<?php endif;?>
			</span>
		</div>
	</div>
	
</li>

