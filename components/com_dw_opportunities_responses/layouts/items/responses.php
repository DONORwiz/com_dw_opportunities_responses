<?php

defined('_JEXEC') or die;

$items = $displayData['items'];

?>

<?php if ($items) : ?>

<div id="opportunity_responses_list">
	
	<?php if ( count ($items) > 1 ) :?>
	<?php echo JLayoutHelper::render( 'responsesfilters' , array( ) , JPATH_ROOT .'/components/com_dw_opportunities/layouts/volunteers' , null ); ?>	
	<?php endif;?>
	
	<ul  class="uk-list list">
	
		<?php foreach ( $items as $i => $response) : ?>
	
		<?php echo JLayoutHelper::render( 'response' , array ( 'response' => $response  ) , JPATH_ROOT .'/components/com_dw_opportunities_responses/layouts/item' , null ); ?>
		
		<?php endforeach;?>
	
	</ul>

</div>

<?php endif;?>