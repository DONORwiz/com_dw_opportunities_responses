<?php
 
// no direct access
defined('_JEXEC') or die;

$user = JFactory::getUser() ;

$response = $displayData['response'];
$opportunity = $displayData['opportunity'];

$id = ( isset ( $response->id ) ) ? $response->id : null ;
$opportunity_id =  ( isset ( $response-> opportunity_id ) ) ? $response-> opportunity_id : $opportunity -> id ;

$showResponseWizard = true;
$showResponseWizardMessages = array();

//If id is null and opportunity id not null
//Check if user can create a new response to this opportunity, thus show the response wizard
if ( !$id && $opportunity )
{

	//Check if user can create response item-----------------------------------------
	$canCreateResponse = JFactory::getUser()->authorise('core.create', 'com_dw_opportunities_responses');
	
	if( $canCreateResponse )
	{
		//Check the user has already created a response for this opportunity_id - ONLY 1 allowed - yesinternet
		JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_dw_opportunities_responses/models', 'Dw_opportunities_responsesModel');
		$opportunitiesresponsesModel = JModelLegacy::getInstance('DwOpportunitiesresponses', 'Dw_opportunities_responsesModel', array('ignore_request' => true));        
		$opportunityresponses = $opportunitiesresponsesModel -> getItemsByVolunteer( JFactory::getUser()->id , $opportunity -> id );
	
		//Check if user has already created a response
		if( $opportunityresponses )
		{
			$showResponseWizard =  false;
			$showResponseWizardMessages[] = JText::_('COM_DW_OPPORTUNITIES_RESPONSES_WIZARD_ALREADY_RESPONDED');
		}
	}
	else
	{
		$showResponseWizard =  false;
		$showResponseWizardMessages[] = JText::_('COM_DW_OPPORTUNITIES_RESPONSES_WIZARD_RESPONSE_NOT_ALLOWED');
	}
	
	//Check if vounteers form is disables
	if( isset ( $opportunity -> parameters -> volunteers_disabled ) && $opportunity -> parameters -> volunteers_disabled =="1" )
	{
		$showResponseWizard =  false;
		$showResponseWizardMessages[] = JText::_('COM_DW_OPPORTUNITIES_RESPONSES_WIZARD_RESPONSES_DISABLED');
	}
	
	//Check if start date has passed
	if( $opportunity -> startDateExpired == true )
	{
		$showResponseWizard =  false;
		$showResponseWizardMessages[] = JText::_('COM_DW_OPPORTUNITIES_RESPONSES_WIZARD_RESPONSE_DATE_START_EXPIRED');
	}
}

JFactory::getLanguage()->load('com_dw_opportunities_responses');
JFactory::getLanguage()->load('com_donorwiz');

if( $showResponseWizard == true){
	
	//Load the Response Wizard form
	$form = new JForm( 'com_dw_opportunities_responses.dwopportunityresponseform' , array( 'control' => 'jform', 'load_data' => true ) );
	$form->loadFile( JPATH_ROOT . '/components/com_dw_opportunities_responses/models/forms/dwopportunityresponseform.xml' );
	
	JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_dw_opportunities_responses/models', 'Dw_opportunities_responsesModel');
	$responseModel = JModelLegacy::getInstance('DwOpportunityresponse', 'Dw_opportunities_responsesModel', array('ignore_request' => true));	
	$responseData = $responseModel->getData($id);	
	$form->bind( $responseData );
	
	if( !$responseData -> state )
		$responseData -> state = '1';
	
	JFactory::getApplication()->setUserState('com_dw_opportunities_responses.form.opportunityresponse.'.$responseData->id, $responseData );
	
	JHtml::_('jquery.framework');
	
	$script = array();
	
	$script[] = 'var waitingModal;';
	$script[] = 'var JText_COM_DONORWIZ_WIZARD_SAVE_FAIL = "'.JText::_('COM_DONORWIZ_WIZARD_SAVE_FAIL').'";';
	$script[] = 'var JText_COM_DONORWIZ_WIZARD_TRASH_CONFIRM = "'.JText::_('COM_DONORWIZ_WIZARD_TRASH_CONFIRM').'";';
	$script[] = 'var JText_COM_DONORWIZ_MODAL_PLEASE_WAIT = "'.JText::_('COM_DONORWIZ_MODAL_PLEASE_WAIT').'";';
	
	JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));	
}

?>

<?php if( $showResponseWizard == false) :?>

	<?php foreach ($showResponseWizardMessages as $message ): ?>
		<div class="uk-alert uk-alert-danger">
		<?php echo $message;?>
		</div>
		<?php endforeach;?>
	<?php return false;?>
	
<?php endif;?>

<?php if( $opportunity_id) : ?>

<div class="uk-article">
	
	<form id="form-response" class="uk-form uk-form-horizontal dw-wizard" action="<?php echo JURI::base();?>index.php?option=com_dw_opportunities_responses&task=dwopportunityresponseform.save" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
		
		<div class="uk-hidden">
		
			<?php echo $form->getInput('id'); ?>

			<?php echo $form->getInput('created_by'); ?>
			<input id="jform_state" type="hidden" name="jform[state]" value="<?php echo $responseData->state; ?>" />	
			
		</div>

		<div class="uk-form-row">
			<?php echo $form->getInput('message'); ?>
		</div>

		<div class="uk-form-row">
			<label class="uk-form-label"><?php echo $form->getLabel('telephone','parameters'); ?>
			<?php if( $form ->getFieldAttribute( 'telephone' , 'tooltip' , '' , 'parameters') ) :?>
			<i data-uk-tooltip title="<?php echo JText::_( $form ->getFieldAttribute( 'telephone', 'tooltip' , '' , 'parameters') );?>" class="uk-icon-question-circle uk-margin-left-small uk-float-right"></i>
			<?php endif;?>	
			</label>
			<div class="uk-form-controls"><?php echo $form->getInput('telephone','parameters'); ?></div>
		</div>
	
		<p class="uk-article-meta"><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_WIZARD_CONFIDENTIAL'); ?></p>
		
		<div class="uk-form-row" data-uk-margin>
			<button type="submit" class="validate uk-button uk-button-primary uk-button-large"><?php echo JText::_('COM_DW_OPPORTUNITIES_RESPONSES_WIZARD_SUBMIT'); ?></button>
			<?php echo JLayoutHelper::render( 'acl.button.trash.response', array ( 'response' => $response , 'class' => 'uk-float-right' ) , JPATH_ROOT .'/components/com_dw_opportunities_responses/layouts' , null ); ?>
		</div>
		
		<input type="hidden" name="jform[opportunity_id]" value="<?php echo $opportunity_id; ?>" />


		<?php echo JHtml::_('form.token'); ?>

	</form>

</div>

<?php endif;?>