<?php
class SSCP_BlockController extends LeftAndMain {
	
	static $url_segment = 'personalization/sscpblock';
	static $url_rule = '/$Action/$ID/$OtherID';
	static $url_priority = 42;
	static $menu_title = 'SSCP Block';
	static $required_permission_codes = 'CMS_ACCESS_CMSMain';
	
	static $allowed_actions = array(
			'addForm',
			'doAdd'
			);
	
	public function init() {
		parent::init();
		Requirements::css(SSCP_DIR . '/css/SSCP_BlockController.css');
	}
	
	/**
	 * Return addForm for SSCP_Block
	 * @param SS_HTTPRequest $request
	 */
	public function addForm(SS_HTTPRequest $request) {
		$blockHolderId = $request->getVar('block_holder_id');
		
		$obj = $this->customise(
				array('EditForm' => $this->getAddBlockForm($blockHolderId))
				);
		
		if($request->isAjax()) {
			// Rendering is handled by template, which will call EditForm() eventually
			return $obj->renderWith($this->getTemplatesWithSuffix('_Content'));
		} else {
			return $obj->renderWith($this->getViewer('show'));
		}
	}
	
	public function getAddBlockForm($id = null, $fields = null) {
		$fields = new FieldList();
	
		$blockHolder = BlockHolderBase::get()->byID($id);
	
		// Snippet Bases
		$snippetBasesArray = array();
		$snippetBases = SnippetBase::get();
		foreach($snippetBases as $snippetBase) {
			$snippetBasesArray[$snippetBase->ID] = $snippetBase->Title;
		}
	
		// Audience Types
		$fields->push(new LabelField('description', "Create a new block to {$blockHolder->Title}."));
		$fields->push(new TextField('Title', 'Title'));
		$fields->push(new DropdownField('SnippetBaseID', 'SnippetBase', $snippetBasesArray));
		$fields->push(new TextField('AudienceType', 'Audience Type'));
		$fields->push(new HiddenField('BlockHolderID', 'BlockHolderID', $id));
		
		$actions = new FieldList(
				FormAction::create('doAdd')
				->addExtraClass('ss-ui-action-constructive')->setAttribute('data-icon', 'accept')
				->setTitle('Create Block')
		);
	
		$form = new Form($this, "doAdd", $fields, $actions);
		$form->addExtraClass('add-form cms-add-form cms-edit-form cms-panel-padded center ' . $this->BaseCSSClasses());
		$form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
	
		return $form;
	}
	
	/**
	 * Add SSCP Block
	 * @param SS_HTTPRequest $request
	 */
	public function doAdd(SS_HTTPRequest $request) {
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
	
}