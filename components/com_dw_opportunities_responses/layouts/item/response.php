<?php

defined('_JEXEC') or die;
$jinput = JFactory::getApplication()->input;
$filterArray = $jinput->get('filter', array(), 'array');

$user = JFactory::getUser();

JFactory::getLanguage()->load('com_donorwiz');
JFactory::getLanguage()->load('com_dw_opportunities_responses');
JFactory::getLanguage()->load('com_dw_opportunities_responses_statuses');

//Response ---------------------------------------------------------------------------------------------------------------------
$response = $displayData['response'];

JFactory::getApplication()->setUserState('com_dw_opportunities.opportunity_response.id'.$response->id , $response);

//Opportunity ---------------------------------------------------------------------------------------------------------------------
//Get Opportunity from UserState
$opportunity = JFactory::getApplication()->getUserState('com_dw_opportunities.opportunity.id'.$response -> opportunity_id);	

if(!$opportunity)
{
	JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_dw_opportunities/models', 'Dw_opportunitiesModel');
	$opportunityModel = JModelLegacy::getInstance('DwOpportunity', 'Dw_opportunitiesModel', array('ignore_request' => true));	
	$opportunity = JFactory::getApplication()->setUserState('com_dw_opportunities.opportunity.id'.$response -> opportunity_id , $opportunityModel -> getData( $response -> opportunity_id ));
}	

//Response Status ---------------------------------------------------------------------------------------------------------------------
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_dw_opportunities_responses_statuses/models', 'Dw_opportunities_responses_statusesModel');
$responseStatusModel = JModelLegacy::getInstance('DwOpportunityresponsestatus', 'Dw_opportunities_responses_statusesModel', array('ignore_request' => true));	
$responseStatus = $responseStatusModel -> getData( $response -> status_id ) ;

if ( !$responseStatus -> created_by)
	$responseStatus -> created_by = $opportunity -> created_by;

if ( !$responseStatus -> status)
	$responseStatus -> status = 'pending';

if ( !$responseStatus -> response_id)
	$responseStatus -> response_id = $response -> id;

//Response owner object ---------------------------------------------------------------------------------------------------------------------
$cuser = CFactory::getUser( $response -> created_by );

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
			
			<a class="uk-button uk-button-success telephone" href="tel:<?php echo $response->parameters['telephone']; ?>" target="_blank" title="<?php echo $response->parameters['telephone']; ?>" data-uk-tooltip>
				<i class="uk-icon-phone"></i>
				
			</a>
			
			<?php if( $user -> id != $response -> created_by): ?>
			<?php include_once JPATH_ROOT.'/components/com_community/libraries/messaging.php'; ?>

			<a class="uk-button uk-button-primary" onclick="<?php echo CMessaging::getPopup($response->created_by); ?>;return false;" href="#" title="<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_SEND_MESSAGE'); ?>" data-uk-tooltip>
				<i class="uk-icon-envelope-o"></i>
			</a>
			<?php endif;?>	
			</div>

			<div class="uk-margin-small-top">
				<div class="uk-button uk-margin-remove uk-width-1-1
					<?php if ( $responseStatus -> status == 'pending' ) echo 'uk-button-warning'?>
					<?php if ( $responseStatus -> status == 'accepted') echo 'uk-button-success'?>
					<?php if ( $responseStatus -> status == 'declined') echo 'uk-button-danger'?>
				">
				<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_STATUSES_'.$responseStatus -> status);?>
				</div>
				<div class="status" style="display:none!important"><?php echo $responseStatus -> status; ?></div>
			</div>
			
			<div class="uk-margin-small-top">
		
				<?php echo JLayoutHelper::render( 'acl.button.edit.response', array ( 'response' => $response ) , JPATH_ROOT .'/components/com_dw_opportunities_responses/layouts' , null ); ?>
				<?php echo JLayoutHelper::render( 'acl.button.edit.responsestatus', array ( 'responseStatus' => $responseStatus ) , JPATH_ROOT .'/components/com_dw_opportunities_responses_statuses/layouts' , null ); ?>

			</div>
			
		</div>
		
		<div class="uk-width-1-1 uk-width-medium-8-10 uk-panel uk-panel-blank">
			
			<div class="uk-width-1-1">
				<?php if ( isset ( $opportunity->title) ) :?>
				<div class="uk-margin-small-top">
				<?php if( $user -> id != $response -> created_by):?>
				<?php echo JText::sprintf('COM_DW_OPPORTUNITIES_RESPONSES_ITEM_THE_VOLUNTEER_IS_INTERESTED_IN',$cuser->getDisplayName());?>
				<?php endif;?>

				<?php //if ( ( isset ( $filterArray['opportunity_id']) && $filterArray['opportunity_id'] =='' ) || !$filterArray['opportunity_id'] ):?>
				<h3 class="uk-text-primary uk-margin-small">
					
					<?php echo  $opportunity->title;?> 
					
				
					<span class="acltoolbar uk-float-right uk-margin-small-right">

					<?php echo JLayoutHelper::render( 'edit.opportunity', array ( 'opportunity' => $opportunity ) , JPATH_ROOT .'/components/com_dw_opportunities/layouts/acl/button' , null ); ?>
					<?php echo JLayoutHelper::render( 'edit.opportunityvolunteers', array ( 'opportunity' => $opportunity ) , JPATH_ROOT .'/components/com_dw_opportunities/layouts/acl/button' , null ); ?>

					</span>

				</h3>
				<?php endif;?>

				
				</div>
				<?php //endif;?>
				
				<blockquote class="uk-margin-small">
					
					
					<div class="modified uk-text-muted">

						<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_ITEM_LAST_MODIFIED'); ?>: <?php echo JFactory::getDate($response->modified)->format('D, d M Y'); ?>
					
					</div>
					
					
						<?php $messageLength = strlen($response->message); ?>
						
						<?php if( $messageLength > 600 ):?>
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