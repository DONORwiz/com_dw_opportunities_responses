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
		
	
		//Notify Beneficiary about the new response  ----------------------------------------------------------------------------------------------------
		JPluginHelper::importPlugin('donorwiz');
		$dispatcher	= JEventDispatcher::getInstance();
		$dispatcher->trigger( 'onOpportunityResponseUpdate' , array( &$data ) );

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