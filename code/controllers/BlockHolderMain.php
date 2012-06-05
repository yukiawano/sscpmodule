<?php
/**
 * @package sscp
 */
// class BlockHolderMain extends CMSSettingsController {
class BlockHolderMain extends LeftAndMain {
	
	static $url_segment = 'personalization';
	static $url_rule = '/$Action/$ID';
	static $menu_title = 'Personalization';
	static $menu_priority = -1;
	
	public function init(){
		parent::init();
		Requirements::javascript(CMS_DIR . '/javascript/CMSMain.GridField.js');
		Requirements::css(CMS_DIR . '/css/screen.css');
	}
	
	function getEditForm($id = null, $fields = null) {
    	if($fields == null) $fields = new FieldList();
    	
    	$fields = new FieldList(
    			new TabSet('Root',
    					$blockHolderTab = new Tab('BlockHolder'),
    					$snippetTab = new Tab('Snippet'),
    					$audienceTypeTab = new Tab('AudienceType')));
    	
    	// BlockHolder Tab
    	$config = GridFieldConfig_RelationEditor::create();
    	$gridField = new GridField('BlockHolders', null, BlockHolder::get(), $config);
    	$blockHolderTab->push($gridField);
    	
    	// AudienceType Tab
    	$audienceTypeLoader = new AudienceTypeLoader();
    	$audienceTypeManager = new AudienceTypeManager();
    	$prettyPrintOfAudienceTypes = $audienceTypeManager->prettyPrint($audienceTypeLoader->load());
    	$audienceTypeTab->push(new LabelField('AudienceTypesTips', 'When you update audience types with the configuration file, you need to access /dev/build/?flush=all.<br /><br />'));
    	$audienceTypeTab->push(new LabelField('AudienceTypes', $prettyPrintOfAudienceTypes));
    	
    	$form = new Form($this, "EditForm", $fields, new FieldList());
    	
		$form->addExtraClass('root-form');
		$form->addExtraClass('cms-edit-form center'); // cms-panel-padded 
		if($form->Fields()->hasTabset()) $form->Fields()->findOrMakeTab('Root')->setTemplate('CMSTabSet');
		$form->setHTMLID('Form_EditForm');
		$form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
		
		 $this->extend('updateEditForm', $fields);
    	return $form;
	}
}