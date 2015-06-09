<?php

defined('JPATH_BASE') or die;

$user = JFactory::getUser();

$data = $displayData;

$filterForm = $displayData['view']->filterForm;
$activeFilters = $displayData['view']->activeFilters;

//If exists, add search terms to active filters array
$jinput = JFactory::getApplication()->input;
$filterArray = $jinput->get('filter', array(), 'array');
if( $filterArray['search'] ){
	$activeFilters = array_merge( $activeFilters , array( "search" => $filterArray['search']) );
}

//Reset URL
$uri = JUri::getInstance();
$isSEF = ( JFactory::getConfig()->get("sef")==1 ) ? true : null ;
if ( $isSEF )
{
	$resetURL = JUri::current ();
}else
{
	$resetURI = clone $uri;
	$resetURI->delVar('filter');
	$resetURI->delVar('list');
	$resetURL = $resetURI->toString();
}

$filters      = false;

if (isset($filterForm))
{
	
	//Filter by opportunity_id
	$opportunity_id_query = '';
	//Check if the user can create the opportunity
	$canCreateOpportunity = $user->authorise('core.create', 'com_dw_opportunities');
	if ($canCreateOpportunity){
		$opportunity_id_query = 'SELECT "" AS id, "'.JText::_('COM_DW_OPPORTUNITIES_RESPONSES_FILTER_BY_OPPORTUNITY_SELECT').'" AS title UNION ALL SELECT id , title FROM #__dw_opportunities WHERE state IN (0,1) AND created_by="'.$user->id.'"';
	}
	//Check if the user can create the resposne
	$canCreateResponse = $user->authorise('core.create', 'com_dw_opportunities_responses');
	if ($canCreateResponse){
		$opportunity_id_query = 'SELECT "" AS id, "'.JText::_('COM_DW_OPPORTUNITIES_RESPONSES_FILTER_BY_OPPORTUNITY_SELECT').'" AS title UNION ALL SELECT a.id , a.title FROM #__dw_opportunities as a LEFT JOIN #__dw_opportunities_responses AS b ON a.id=b.opportunity_id WHERE a.state IN (0,1) AND b.state=1 AND b.created_by="'.$user->id.'"';
	}
	
	if ( $user->get('isRoot') ){
		$opportunity_id_query = 'SELECT "" AS id, "'.JText::_('COM_DW_OPPORTUNITIES_RESPONSES_FILTER_BY_OPPORTUNITY_SELECT').'" AS title UNION ALL SELECT id , title FROM #__dw_opportunities WHERE state IN (0,1)';
	}
	
	$filterForm->setFieldAttribute( 'opportunity_id', 'query' ,$opportunity_id_query, 'filter' );
	
	$filters = $filterForm->getGroup('filter');
	$list = $filterForm->getGroup('list');
}

?>

<form id="dwopportunityresponses_default_filter" action="<?php echo JURI::current();?>" method="get" class="form-validate uk-form uk-form-stacked " enctype="multipart/form-data">


	<?php if(JFactory::getConfig()->get("sef")!=1): ?>
        <input type="hidden" name="option" value="<?php echo $uri->getVar('option'); ?>"  />
        <input type="hidden" name="view" value="<?php echo $uri->getVar('view'); ?>"  />
        <input type="hidden" name="layout" value="<?php echo $uri->getVar('layout'); ?>"  />
        <input type="hidden" name="Itemid" value="<?php echo $uri->getVar('Itemid'); ?>"  />
        <input type="hidden" name="lang" value="<?php echo $uri->getVar('lang'); ?>"  />
    <?php endif ?>
	
<?php if ($filters) : ?>

	<div id="filter-options" class="<?php if ( empty ( $activeFilters ) ) echo 'uk-hidden';?>">
			<?php echo $filters['filter_search']->input;?>
	<button class="uk-button uk-button-large uk-button-primary" type="submit"><i class="uk-icon-search"></i></button>
		<?php echo $filters['filter_status']->input;?>
		<?php echo $filters['filter_opportunity_id']->input;?>
		<?php echo $filters['created_by']->input;?>
		
	<hr class="uk-artivle-divider uk-margin-small">
	
	</div>

	<a class="uk-button uk-button-large uk-button-primary" data-uk-toggle="{target:'#filter-options'}"><i class="uk-icon-filter uk-margin-small-right"></i><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_FILTER_OPTIONS'); ?></a>
	
	<?php if ( !empty ( $activeFilters ) ) :?>
	
		<a class="uk-button uk-button-large uk-button-primary" href="<?php echo $resetURL;?>"><i class="uk-icon-remove"></i></a>
	
	<?php endif;?>

<?php endif; ?>

<?php if ($list) : ?>

	<div class="uk-float-right">

		<?php echo $list['list_fullordering']->input;?>
		<?php echo $list['list_limit']->input;?>
	</div>
	<?php endif; ?>

</form>