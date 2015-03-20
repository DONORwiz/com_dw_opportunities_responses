<?php

defined('_JEXEC') or die;

JFactory::getLanguage()->load('com_dw_opportunities_responses_statuses');

$app = JFactory::getApplication();
$jinput = $app->input;


$resetlink = array();

$jinputFilter = $app->input->get('filter','','array');

$jinputStatus = ( isset ( $jinputFilter['status'] ) ) ? $jinputFilter['status'] : '' ;


//$dashboard = ( $jinput->get('dashboard', '', 'string'=='true') ) ? true : null ;

$donorwizUrl = new DonorwizUrl();


?>
<div class="uk-width-1-1 uk-margin-large-bottom" data-uk-sticky="{top:76}" style="background:#fff;">

	<div class="uk-width-1-1 uk-text-right uk-margin-small-top uk-form">
		
		<a class="uk-button uk-button-mini uk-button-link" href="<?php echo JRoute::_('index.php?option=com_donorwiz&view=dashboard&layout=dwopportunitiesresponses'); ?>">
			<i class="uk-icon-remove uk-icon-small"></i>
			<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_VOLUNTEERS_RESET_FILTERS');?>
		</a>


	
	</div>
	


	<div id="filters-toggle" class="uk-form uk-animation-slide-top">
	
			<div class="uk-grid uk-grid-small uk-margin-small-top">
				
				<div class="uk-width-medium-1-2">
					
					<select class="uk-form-large uk-width-1-1 created-sort" onchange="if (this.value) window.location.href=this.value">
						<option value="<?php echo $donorwizUrl -> getCurrentUrlWithNewParams( array( 'filter_order_Dir' => 'desc' ) );?>" <?php if( $app->input->get('filter_order_Dir','','string') == 'desc' || $app->input->get('filter_order_Dir','','string') == '' ) echo 'selected="selected"';?>>
							<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_SORT_BY_DATE_DESC'); ?>
						</option>
						<option value="<?php echo $donorwizUrl -> getCurrentUrlWithNewParams( array( 'filter_order_Dir' => 'asc' ) );?>" <?php if( $app->input->get('filter_order_Dir','','string') == 'asc' ) echo 'selected="selected"';?>>
							<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_SORT_BY_DATE_ASC'); ?>
						</option>
					</select>

				</div>
				
				<div class="uk-width-medium-1-2">
					
					<select class="uk-form-large uk-width-1-1 created-sort" onchange="if (this.value) window.location.href=this.value">
						<option value="<?php echo $donorwizUrl -> getCurrentUrlWithNewParams( array( 'filter' => array ( 'status' => '' ) ) );?>" <?php if( $jinputStatus == '' ) echo 'selected="selected"';?>>
							<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_STATUSES_ALL'); ?>
						</option>
						<option value="<?php echo $donorwizUrl -> getCurrentUrlWithNewParams( array( 'filter' => array ('status'=>'pending' ) ) );?>" <?php if( $jinputStatus == 'pending' ) echo 'selected="selected"';?>>
							<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_STATUSES_PENDING'); ?>
						</option>
						<option value="<?php echo $donorwizUrl -> getCurrentUrlWithNewParams( array( 'filter' => array ('status'=>'accepted' ) ) );?>" <?php if( $jinputStatus == 'accepted' ) echo 'selected="selected"';?>>
							<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_STATUSES_ACCEPTED'); ?>
						</option>
						<option value="<?php echo $donorwizUrl -> getCurrentUrlWithNewParams( array( 'filter' => array ('status'=>'declined' ) ) );?>" <?php if( $jinputStatus == 'declined') echo 'selected="selected"';?>>
							<?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_STATUSES_DECLINED'); ?>
						</option>
				</select>
			
				</div>
			</div>		
		

	</div>
	
</div>