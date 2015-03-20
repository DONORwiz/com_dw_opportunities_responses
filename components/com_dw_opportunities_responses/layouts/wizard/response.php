<?php
 
// no direct access
defined('_JEXEC') or die;

$user = JFactory::getUser() ;

$response = $displayData['response'];

$id =  ( isset ( $response->id ) ) ? $response->id : null ;
$opportunity_id =  ( isset ( $response-> opportunity_id ) ) ? $response-> opportunity_id : $displayData['opportunity_id'] ;

JFactory::getLanguage()->load('com_dw_opportunities_responses');
JFactory::getLanguage()->load('com_donorwiz');

//Load the Response Wizard form
$form = new JForm( 'com_dw_opportunities_responses.dwopportunityresponseform' , array( 'control' => 'jform', 'load_data' => true ) );
$form->loadFile( JPATH_ROOT . '/components/com_dw_opportunities_responses/models/forms/dwopportunityresponseform.xml' );

JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_dw_opportunities_responses/models', 'Dw_opportunities_responsesModel');
$responseModel = JModelLegacy::getInstance('DwOpportunityresponse', 'Dw_opportunities_responsesModel', array('ignore_request' => true));	
$responseData = $responseModel->getData($id);	
$form->bind( $responseData );


JFactory::getApplication()->setUserState('com_dw_opportunities_responses.form.opportunityresponse.'.$responseData->id, $responseData );

JHtml::_('jquery.framework');

$script = array();

$script[] = 'var waitingModal;';
$script[] = 'var JText_COM_DONORWIZ_WIZARD_SAVE_FAIL = "'.JText::_('COM_DONORWIZ_WIZARD_SAVE_FAIL').'";';
$script[] = 'var JText_COM_DONORWIZ_WIZARD_TRASH_CONFIRM = "'.JText::_('COM_DONORWIZ_WIZARD_TRASH_CONFIRM').'";';
$script[] = 'var JText_COM_DONORWIZ_MODAL_PLEASE_WAIT = "'.JText::_('COM_DONORWIZ_MODAL_PLEASE_WAIT').'";';

JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

JHtml::script(Juri::base() . 'media/com_donorwiz/js/wizard.js');
	

?>

<?php if( $opportunity_id) : ?>

<div class="uk-article">

	
	<form id="form-response" class="uk-form uk-form-horizontal dw-ajax-submit" action="<?php echo JURI::base();?>index.php?option=com_dw_opportunities_responses&task=dwopportunityresponseform.save" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
		
		<div class="uk-hidden">
		
			<?php echo $form->getInput('id'); ?>
			<?php echo $form->getInput('state'); ?>
			<?php echo $form->getInput('created_by'); ?>
			
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

		</div>
		
		<input type="hidden" name="jform[opportunity_id]" value="<?php echo $opportunity_id; ?>" />
		<input type="hidden" name="wizardReloadPage" value="current" />

		
		<?php echo JHtml::_('form.token'); ?>

	</form>

</div>

<?php endif;?>

