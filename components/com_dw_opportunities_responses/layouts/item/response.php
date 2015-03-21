<?php

defined('_JEXEC') or die;

JFactory::getLanguage()->load('com_donorwiz');
JFactory::getLanguage()->load('com_dw_opportunities_responses');
JFactory::getLanguage()->load('com_dw_opportunities_responses_statuses');

include_once JPATH_ROOT.'/components/com_community/libraries/core.php';
include_once JPATH_ROOT.'/components/com_community/libraries/messaging.php';

$response = $displayData['response'];

$response -> status = ( !$response -> status || ( $response -> status_state=='-1' || $response -> status_state=='-2' ) ) ? 'pending' : $response -> status ;

//Opportunity data
//Get opportunity data from UserState
$opportunity = JFactory::getApplication()->getUserState('com_dw_opportunities.opportunity.id'.$response -> opportunity_id);	

//If ppportunity UserState is null, query the opportunity model and create the UserState
if(!$opportunity)
{
	JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_dw_opportunities/models', 'Dw_opportunitiesModel');
	$opportunityModel = JModelLegacy::getInstance('DwOpportunity', 'Dw_opportunitiesModel', array('ignore_request' => true));	
	$opportunity = JFactory::getApplication()->setUserState('com_dw_opportunities.opportunity.id'.$response -> opportunity_id , $opportunityModel -> getData( $response -> opportunity_id ));
}	

//Save response to user state
JFactory::getApplication()->setUserState('com_dw_opportunities.opportunity_response.id'.$response->id , $response);	

$response -> status_created_by = ( !$response -> status_created_by && $opportunity ) ? $opportunity ->created_by : $response -> status_created_by ;

//Check if logged in user is the owner of the response
$user = JFactory::getUser();

//Check if the user can edit the response
$canEdit = $user->authorise('core.edit', 'com_dw_opportunities_responses');
if (!$canEdit && $user->authorise('core.edit.own', 'com_dw_opportunities_responses'))
	$canEdit = $user->id == $response->created_by;

//Check if the user can edit the response status
$canEditStatus = $user->authorise('core.edit', 'com_dw_opportunities_responses_statuses');
if (!$canEditStatus && $user->authorise('core.edit.own', 'com_dw_opportunities_responses_statuses'))
	$canEditStatus = $user->id == $response->status_created_by;

//Check if the user can edit the opportunity
$canEditOpportunity = $user->authorise('core.edit', 'com_dw_opportunities');
if (!$canEditOpportunity && $user->authorise('core.edit.own', 'com_dw_opportunities'))
	$canEditOpportunity = $user->id == $opportunity->created_by;

//Create response user object
$cuser = CFactory::getUser( $response->created_by );

?>

<li id="opportunityresponse<?php echo $response->id;?>" class="uk-margin" >

	<div class="uk-grid uk-grid-small">
		
		<div class="uk-width-1-1 uk-width-medium-2-10 uk-panel uk-panel-box uk-panel-box-secondary">
			
			<div class="uk-text-center">
			<a class="uk-display-block uk-margin-small-bottom" target="_blank" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$response->created_by); ?>" title="<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_VIEW_PROFILE').': '.$cuser->getDisplayName(); ?>" data-uk-tooltip>
				<img class="uk-thumbnail uk-border-circle" src="<?php echo $cuser->getThumbAvatar(); ?>" alt="">
			</a>
				
			<div class="name" style="display:none!important">
				<?php echo $cuser->getDisplayName(); ?>
			</div>
			
			
			<?php if( isset( $response->parameters['telephone'] ) && $canEditStatus ) :?>
			<a class="uk-button uk-button-success telephone" href="tel:<?php echo $response->parameters['telephone']; ?>" target="_blank" title="<?php echo $response->parameters['telephone']; ?>" data-uk-tooltip>
				<i class="uk-icon-phone"></i>
				
			</a>
			<?php endif;?>
			
			<?php if( !$canEdit ) :?>
			<a class="uk-button uk-button-primary" onclick="<?php echo CMessaging::getPopup($response->created_by); ?>;return false;" href="#" title="<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_SEND_MESSAGE'); ?>" data-uk-tooltip>
				<i class="uk-icon-envelope-o"></i>
			</a>
			<?php endif;?>	
			</div>

			<div class="uk-margin-small-top">
				
				
					<div class="uk-button uk-margin-remove uk-width-1-1
						<?php if ( $response -> status == 'pending' ) echo 'uk-button-warning'?>
						<?php if ( $response -> status == 'accepted') echo 'uk-button-success'?>
						<?php if ( $response -> status == 'declined') echo 'uk-button-danger'?>
					">
					<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_STATUSES_'.$response -> status);?>
					</div>
					<div class="status" style="display:none!important"><?php echo $response -> status; ?></div>


				


				
			</div>
			
			<div class="uk-margin-small-top">
							<?php if ($canEdit) :?>
					<?php echo JLayoutHelper::render(
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
					?>				
				
				<?php endif;?>
			
			
				<?php if ($canEditStatus) :?>

					<?php echo JLayoutHelper::render(
						'popup-button', 
						array (
							'buttonText' => JText::_('COM_DW_OPPORTUNITIES_RESPONSES_WIZARD_EDIT'),
							'buttonIcon' => 'uk-icon-edit uk-icon-small uk-margin-small-right',
							'buttonType' => 'uk-button uk-button-blank uk-button-small uk-width-1-1',
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
			</div>
			
		</div>
		
		<div class="uk-width-1-1 uk-width-medium-8-10 uk-panel uk-panel-box">
			
			<div class="uk-width-1-1">
				<?php if ( isset ( $opportunity->title) ) :?>
				<div class="uk-margin-small-top">
				<?php if( $user -> id != $response -> created_by):?>
				<?php echo JText::sprintf('COM_DW_OPPORTUNITIES_RESPONSES_ITEM_THE_VOLUNTEER_IS_INTERESTED_IN',$cuser->getDisplayName());?>
				<?php endif;?>
				<h3 class="uk-text-primary uk-margin-small">
					
					<?php echo  $opportunity->title;?> 
					
					
						<a class="uk-button uk-button-mini uk-button-link uk-float-right uk-margin-small-left" target="_blank" href="<?php echo JRoute::_('index.php?option=com_dw_opportunities&view=dwopportunity&Itemid=261&id='.$response->opportunity_id); ?>" title="<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_PREVIEW_OPPORTUNITY');?>" data-uk-tooltip>
						<i class="uk-icon-eye"></i>
						</a>
						
					<?php if ($canEditOpportunity):?>
						<a class="uk-button uk-button-mini uk-button-primary uk-float-right uk-margin-small-left" href="<?php echo JRoute::_('index.php?option=com_donorwiz&view=dashboard&layout=dwopportunityform&Itemid=298&id='.$response->opportunity_id); ?>" title="<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_EDIT_OPPORTUNITY');?>" data-uk-tooltip>
						<i class="uk-icon-edit"></i>
						</a>

						<a class="uk-button uk-button-mini uk-button-success uk-float-right uk-margin-small-left" href="<?php echo JRoute::_('index.php?option=com_donorwiz&view=dashboard&layout=dwopportunityvolunteers&Itemid=298&id='.$response->opportunity_id); ?>" title="<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_EDIT_OPPORTUNITY_VOLUNTEERS');?>" data-uk-tooltip>
						<i class="uk-icon-users"></i>
						</a>
					<?php endif;?>
				</h3>
				</div>
				<?php endif;?>
				
				<blockquote class="uk-margin-small">
					
					
					<div class="modified uk-text-muted">

						<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_ITEM_LAST_MODIFIED'); ?>: <?php echo JFactory::getDate($response->modified)->format('D, d M Y'); ?>
					
					</div>
					
					
						<?php $messageLength = strlen($response->message); ?>
						
						<?php if( $messageLength>600 ):?>
						<?php echo substr( $response->message , 0, 600).'...'; ?>
							<a href="#" data-uk-modal="{target:'#response-message-modal-<?php echo $response->id; ?>'}"><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_READ_FULL_MESSAGE'); ?></a>
							<div id="response-message-modal-<?php echo $response->id; ?>" class="uk-modal">
								<div class="uk-modal-dialog">
									<a class="uk-modal-close uk-close"></a>
									<h3><?php echo JText::sprintf('COM_DW_OPPORTUNITIES_RESPONSES_ITEM_THE_VOLUNTEER_WROTE',$cuser->getDisplayName());?></h3>
									<blockquote><?php echo $response->message; ?></blockquote>
								</div>
							</div>						
						
						<?php else:?>
						<p class="message uk-margin-remove">
						<?php echo $response->message ; ?>
						</p>
						<?php endif;?>
					
					
				</blockquote>

			</div>	

		</div>			
		
	</div>

</li>


