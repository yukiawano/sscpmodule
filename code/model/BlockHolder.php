<?php
class BlockHolder extends DataObject {
	static $db = array(
			'Name' => 'Varchar',
			'TemplateKey' => 'Varchar',
			'Description' => 'Text',
			'ShowDefaultSnippet' => 'Boolean'
			);
	
	static $defaults = array(
			'ShowDefaultSnippet' => true
	);
	
	static $summary_fields = array(
			'Name',
			'TemplateKey',
			'Description'
			);
	
	static $has_many = array(
			'Blocks'=>'Block'
	);
	
	static $has_one = array(
			'DefaultSnippet' => 'Snippet'
	);
	
	public function getCMSFields(){
		$fields = parent::getCMSFields();
		$descriptionField = new LabelField('AddBlocks', 'You can add blocks by clicking the Blocks tab at the right above.<br />');
		$fields->insertBefore($descriptionField, "Name");
		return $fields;
	}
}