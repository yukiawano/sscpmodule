<?php
/**
 * @package sscp
 */
// class BlockHolderMain extends CMSSettingsController {
class BlockHolderMain extends LeftAndMain implements PermissionProvider {
	
	const ADMIN_PERSONALIZATION = 'ADMIN_PERSONALIZATION';
	
	static $url_segment = 'personalization';
	static $url_rule = '/$Action/$ID/$OtherID';
	static $menu_title = 'Personalization';
	static $menu_priority = -1;
		
	static $allowed_actions = array(
			'AddSnippetForm',
			'doAddSnippet',
			'AddBlockForm',
			'doAddBlock'
			);
	
	function providePermissions() {
		return array(BlockHolderMain::ADMIN_PERSONALIZATION => "Manage and debug personalized contents.");
	}
	
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
    	$gridField = new GridField('BlockHolders', null, BlockHolderBase::get(), $config);
    	$blockHolderTab->push($gridField);
    	
    	// Snippet Tab
    	$sConfig = GridFieldConfig_RecordEditor::create();
    	$sConfig->addComponent(new GridFieldAddNewSnippetButton('buttons-before-left'));
    	$sConfig->removeComponentsByType('GridFieldAddNewButton');
    	$sGridField = new GridField('Snippets', null, SnippetBase::get(), $sConfig);
    	$snippetTab->push($sGridField);
    	
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
	
	
	public function Breadcrumbs($unlinked = false) {
		$items = parent::Breadcrumbs($unlinked);
	
		// The root element should point to the pages tree view,
		// rather than the actual controller (which would just show an empty edit form)
		$items[0]->Title = self::menu_title_for_class('BlockHolderMain');
		$items[0]->Link = singleton('BlockHolderMain')->Link();
	
		return $items;
	}
	
	/**
	 * Return a subclasses of SnippetBase
	 *
	 * @return array
	 */
	protected function getSnippetClasses() {
		$classes = ClassInfo::subclassesFor('SnippetBase');
		
		$subClasses = array();
		foreach($classes as $class){
			if($class == 'SnippetBase') continue;
			$snippetName = $class::$snippet_name;
			$subClasses[$class] = $snippetName;
		}
		
		return $subClasses;
	}
	
	public function addBlockForm(SS_HTTPRequest $request) {
		$blockHolderId = $request->getVar('block_holder_id');
		
		$obj = $this->customise(array(
				'EditForm' => $this->getAddBlockForm($blockHolderId)
		));
		
		if($request->isAjax()) {
			return $obj->renderWith($this->getTemplatesWithSuffix('_Content'));
		} else {
			return $obj->renderWith($this->getViewer('show'));
		}
	}
	
	public function getAddBlockForm($id = null, $fields = null) {
		$fields = new FieldList();
		
		$blockHolder = BlockHolder::get()->byID($id);
		
		// Snippet Bases
		$snippetBasesArray = array();
		$snippetBases = SnippetBase::get();
		foreach($snippetBases as $snippetBase) {
			$snippetBasesArray[$snippetBase->ID] = $snippetBase->Title;
		}
		
		// Audience Types
		$audienceTypeLoader = new AudienceTypeLoader();
		$audienceTypesArray = $audienceTypeLoader->getAudienceTypes($audienceTypeLoader->load());
		
		$fields->push(new LabelField('description', "Create a new block to {$blockHolder->Title}."));
		$fields->push(new TextField('Title', 'Title'));
		$fields->push(new DropdownField('SnippetBaseID', 'SnippetBase', $snippetBasesArray));
		$fields->push(new DropdownField('AudienceType', 'AudienceType', $audienceTypesArray));
		$fields->push(new HiddenField('BlockHolderID', 'BlockHolderID', $id));
		
		$actions = new FieldList(
				FormAction::create('doAddBlock')
				->addExtraClass('ss-ui-action-constructive')->setAttribute('data-icon', 'accept')
				->setTitle('Create Block')
		);
		
		$form = new Form($this, "doAddBlock", $fields, $actions);
		$form->addExtraClass('add-form cms-add-form cms-edit-form cms-panel-padded center ' . $this->BaseCSSClasses());
		$form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
		
		return $form;
	}
	
	public function doAddBlock(SS_HTTPRequest $request) {
		$title = $request->postVar('Title');
		$snippetBaseID = $request->postVar('SnippetBaseID');
		$audienceType = $request->postVar('AudienceType');
		$blockHolderID = $request->postVar('BlockHolderID');
		
		$sscpBlock = new SSCP_Block();
		$sscpBlock->Title = $title;
		$sscpBlock->BlockHolderID = $blockHolderID;
		$sscpBlock->SnippetBaseID = $snippetBaseID;
		$sscpBlock->AudienceType = $audienceType;
		$sscpBlock->write();
		
		$link = Controller::join_links(
				$this->stat('url_base', true),
				"personalization/EditForm/field/BlockHolders/item/{$blockHolderID}/edit/"
		);
		
		$this->response->addHeader('X-Status', 'Created new block.');
		return $this->redirect($link);
	}
	
	public function addSnippetForm(SS_HTTPRequest $request) {
		$obj = $this->customise(array(
				'EditForm' => $this->getAddSnippetForm()
		));
	
		if($request->isAjax()) {
			// Rendering is handled by template, which will call EditForm() eventually
			return $obj->renderWith($this->getTemplatesWithSuffix('_Content'));
		} else {
			return $obj->renderWith($this->getViewer('show'));
		}
	}
	
	private function getAddSnippetForm($id = null, $fields = null) {
		$fields = new FieldList();
		$fields->push(new TextField('Title', 'Title'));
		$fields->push(new ListboxField('SnippetType', 'Snippet Type', $this->getSnippetClasses()));
	
		$actions = new FieldList(
				FormAction::create('doAddSnippet')
					->addExtraClass('ss-ui-action-constructive')->setAttribute('data-icon', 'accept')
					->setTitle('Create Snippet')
		);
		
		$form = new Form($this, "doAddSnippet", $fields, $actions);
		$form->addExtraClass('add-form cms-add-form cms-edit-form cms-panel-padded center ' . $this->BaseCSSClasses());
		$form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
		
		return $form;
	}
	
	public function doAddSnippet(SS_HTTPRequest $request) {
		$title = $request->postVar('Title');
		$snippetType = $request->postVar('SnippetType');
	
		$snippet = new $snippetType();
		$snippet->Title = $title;
		$snippet->write();
	
		$link = Controller::join_links(
				$this->stat('url_base', true),
				'personalization',
				'/'
		);
		
		$this->response->addHeader('X-Status', 'Created new snippet.');
		return $this->redirect($link);
	}
}