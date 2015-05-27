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

    /**
     * Display the view
     */
    public function display($tpl = null) {
        
		$user = JFactory::getUser();
		
		$this->state = $this->get('State');
		
		//Filter by user
		$donorwizUser = new DonorwizUser( JFactory::getUser() -> id );
		$isBeneficiary = $donorwizUser -> isBeneficiary('com_donorwiz');
		$canCreateResponse = $user->authorise('core.create', 'com_dw_opportunities_responses');

		//Check if user is Beneficiary
		if( !$user->get('isRoot') )
		{
			if( $isBeneficiary )
			{
				$this->state->set('filter.opportunity_created_by', $user->id );
			}
			else if( $canCreateResponse )
			{
				$this->state->set('filter.created_by', $user->id );
			}
		}

		$this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
       
		// Check for errors.
        if (count($errors = $this->get('Errors')))
		{
            throw new Exception(implode("\n", $errors));
        }

		// $this->params =  JFactory::getApplication()->getParams('com_dw_opportunities_responses');
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