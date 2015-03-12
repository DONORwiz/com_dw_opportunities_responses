<?php

// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Response controller class.
 */
class Dw_opportunities_responsesControllerDwOpportunityresponseForm extends Dw_opportunities_responsesController {

    public function save() {
        
		// Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('DwOpportunityresponseForm', 'Dw_opportunities_responsesModel');

        // Get the user data.
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');
		
        // Validate the posted data.
        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }
		
        // Validate the posted data.
        $data = $model->validate($form, $data);
		
        // Check for errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }
            
			$input = $app->input;
            $jform = $input->get('jform', array(), 'ARRAY');

            // Save the data in the session.
            $app->setUserState('com_dw_opportunities_responses.edit.opportunityresponse.data', $jform, array());
			
			try
			{
				echo new JResponseJson( '' , $errors , true );
			}
			catch(Exception $e)
			{
				echo new JResponseJson($e);
			}
		
			jexit();

        }		
		
		//parameters fields convert into json before save - yesinternet
		if (isset($data['parameters']) && is_array($data['parameters']))
		{
			$registry = new JRegistry;
			$registry->loadArray($data['parameters']);
			$data['parameters'] = (string) $registry;
		}
	

        // Attempt to save the data.
        $return = $model->save($data);

        // Check for errors.
        if ($return === false) {
			
			// Save the data in the session.
            $app->setUserState('com_dw_opportunities_responses.edit.opportunityresponse.data', $jform, array());
			
			try
			{
				echo new JResponseJson( '' , 'Could not save the data' , true );
			}
			catch(Exception $e)
			{
				echo new JResponseJson($e);
			}
		
			jexit();
        }

        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_dw_opportunities_responses.edit.opportunityresponse.id', null);

        // Flush the data from the session.
        $app->setUserState('com_dw_opportunities_responses.edit.opportunityresponse.data', null);
		
		
		//Notify opportunity creator via messaging system ---------------------------------------------------------------------
		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_dw_opportunities/models', 'Dw_opportunitiesModel');
		$opportunityModel = JModelLegacy::getInstance('DwOpportunity', 'Dw_opportunitiesModel', array('ignore_request' => true));	
		$opportunity = $opportunityModel -> getData( $data['opportunity_id']);
		
		$donorwizMessaging = new DonorwizMessaging();
		
		$messageParams = array();
		$messageParams['actor_id'] = CFactory::getUser() -> id;
		$messageParams['target'] = $opportunity -> created_by;
		$messageParams['opportunity_title'] = $opportunity -> title;
		$messageParams['link'] = JRoute::_('index.php?option=com_donorwiz&view=dashboard&layout=dwopportunity&Itemid=298&id='.$opportunity -> id).'#opportunityresponse'.$data['id'];
		$messageParams['subject'] = $opportunity->title.': '.JText::_('COM_DW_OPPORTUNITIES_RESPONSES_NEW_RESPONSE_NOTIFICATION_SUBJECT');
		$messageParams['body'] = JText::_('COM_DW_OPPORTUNITIES_RESPONSES_NEW_RESPONSE_NOTIFICATION_BODY');
		
		$donorwizMessaging -> sendNotification ( $messageParams ) ;
		//----------------------------------------------------------------------------------------------------------------------------------
		
		//Add donor to the ngo friends list
		
		// $user = JFactory::getUser();
		
		// $opportunityitemSession = $app->getUserState('com_dw_opportunities.dwopportunity.session');
		
		// $id = $opportunityitemSession -> created_by ;
		// $fromid = $user -> id ; 
		
		// if($id!=$fromid){
			// $donorwizCommunity = new DonorwizCommunity();
			// $addAsAFriend = $donorwizCommunity -> addAsAFriend( $id , $fromid , '' , 1 );
		// }
		
		
		try
		{
			echo new JResponseJson( $return );
		}
		catch(Exception $e)
		{
			echo new JResponseJson($e);
		}
	
		jexit();
    }
	
	



}
