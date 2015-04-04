<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Dw_opportunities_responses.
 */
class Dw_opportunities_responsesViewDwOpportunitiesresponses extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;
    protected $params;
    protected $jinputFilter;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        
		$app = JFactory::getApplication();
		
		$this->jinputFilter = $app->input->get('filter','','array');
		
		
        $this->state = $this->get('State');
        
		$donorwizUser = new DonorwizUser( JFactory::getUser() -> id );
		$isBeneficiary = $donorwizUser -> isBeneficiary('com_donorwiz');
		//Filter by user id
		//Check if user is Beneficiary
		if($isBeneficiary)
			$this->state->set('filter.opportunity_created_by', JFactory::getUser()->id );
		else
			$this->state->set('filter.created_by', JFactory::getUser()->id );
		
		//Default ordering
		if( $app->input->get('filter_order','','string')=='' )
		{
			$this->state->set('list.ordering','a.modified');
		}
		
		//Default ordering
		if( $app->input->get('filter_order_Dir','','string')=='' )
		{
			$this->state->set('list.direction','desc');
		}

		//Default status
		$jinputFilter = $this->jinputFilter;
		if(!isset($jinputFilter['status']))
		{
			$this->state->set('filter.status','');
		}

		//Default search
		$jinputFilter = $this->jinputFilter;
		if(!isset($jinputFilter['search']))
		{
			$this->state->set('filter.search','');
		}	

		
		$this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->params = $app->getParams('com_dw_opportunities_responses');
       
	   //var_dump($this);
	   // $this->filterForm = $this->get('FilterForm');
		//$this->activeFilters = $this->get('ActiveFilters');

        // // Check for errors.
        if (count($errors = $this->get('Errors'))) {

            throw new Exception(implode("\n", $errors));
        }

        //$this->_prepareDocument();
        parent::display($tpl);
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument() {
        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $title = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_DW_OPPORTUNITIES_RESPONSES_DEFAULT_PAGE_TITLE'));
        }
        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }

}