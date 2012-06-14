<?php
class SnippetAddController extends LeftAndMain {
	
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
		$fields->push(new LabelField('gaf', 'getAddForm is called.'));
		
		$actions = new FieldList();
		
		$form = new Form($this, "AddForm", $fields, $actions);
		
		return $form;
	}
	
	function getEditForm($id = null, $fields = null) {
		$fields = new FieldList();
		$fields->push(new LabelField('gef', 'getEditForm is called.'));
		
		$actions = new FieldList();
		
		$form = new Form($this, "AddForm", $fields, $actions);
		
		return $form;
		
	}
}