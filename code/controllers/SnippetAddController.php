<?php
class SnippetAddController extends BlockHolderMain {
	
	static $url_segment = 'personalization/add';
	static $url_rule = '/$Action/$ID/$OtherID';
	static $url_priority = 42;
	static $menu_title = 'Add snippet';
	
	static $allowed_actions = array(
			'AddForm',
			'doAdd',
	);
	
	function AddForm($id = null, $fields = null) {
		$fields = new FieldList();
		$fields->push(new TextField('Title', 'Title'));
		$fields->push(new ListboxField('SnippetType', 'Snippet Type', $this->getSnippetClasses()));
		
		
		$actions = new FieldList(
				FormAction::create("doAdd", _t('CMSMain.Create',"Create"))
				->addExtraClass('ss-ui-action-constructive')->setAttribute('data-icon', 'accept')
				->setUseButtonTag(true)
		);
		
		$form = new Form($this, "AddForm", $fields, $actions);
		$form->addExtraClass('cms-add-form stacked cms-content center cms-edit-form ' . $this->BaseCSSClasses());
		$form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
		
		return $form;
	}
	
	function doAdd($data, $form) {
		$title = $data['Title'];
		$snippetType = $data['SnippetType'];
		
		$snippet = new $snippetType();
		$snippet->Title = $title;
		$snippet->write();
		
		$link = Controller::join_links(
				$this->stat('url_base', true),
				'personalization',
				'/'
		);
		
		return $this->redirect($link);
	}
	
}