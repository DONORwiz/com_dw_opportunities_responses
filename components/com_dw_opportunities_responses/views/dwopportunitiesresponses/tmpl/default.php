<?php

defined('_JEXEC') or die;

$items = $this->items;
$user = JFactory::getUser();
//Check if the user can create the response
$canCreateResponse = $user->authorise('core.create', 'com_dw_opportunities_responses');
//Check if the user can create the opportunity
$canCreateOpportunity = $user->authorise('core.create', 'com_dw_opportunities');
//check if is root user
$isRoot = $user->get('isRoot');

?>

<div class="uk-margin-left">

<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>

<div class="uk-align-right uk-margin-small-top">
<?php echo JLayoutHelper::render( 
	'export.items', 
	array ( 
		'items' => $items , 
		'component' => 'com_dw_opportunities' , 
		'fields' => 'id,created_name,created_email,created_telephone,opportunity_title,status,message' ,
		'filename' => 'donorwiz_volunteers_'.JFactory::getUser() -> name.'_'.JFactory::getDate()->format('d M Y') 
	) , 
	JPATH_ROOT .'/components/com_donorwiz/layouts' , 
	null 
); ?>
</div>
		
<?php if ( !$isRoot ) : ?>

	<?php if( $canCreateOpportunity ) : ?>
	<h1><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_MY_VOLUNTEERS');?> (<?php echo $this->pagination->total;?>) </h1>
	<?php endif;?>
		
	<?php if( $canCreateResponse ) : ?>
	<h1><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_MY_VOLUNTEERING_OPPORTUNITIES');?> (<?php echo $this->pagination->total;?>)</h1>
	<?php endif;?>
	
<?php endif;?>

<?php if (!$items):?>

<h2><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_MY_VOLUNTEERS_ΝΟ_ITEMS');?></h2>

<?php endif;?>

<?php if( $items ): ?>

	<?php echo JLayoutHelper::render( 'items.responses' , array( 'items'=> $items ) , JPATH_ROOT .'/components/com_dw_opportunities_responses/layouts/' , null ); ?>	
	<?php echo $this->pagination->getPagesLinks(); ?>

<?php endif;?>

</div>