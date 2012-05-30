<?php
class BlockHolder extends DataObject {
	static $db = array(
			'Name' => 'Varchar',
			'TemplateKey' => 'Varchar',
			'Description' => 'Text'
			);
	static $summary_fields = array(
			'Name',
			'TemplateKey',
			'Description'
			);
	
	public function getCMSFields(){
		$fields = parent::getCMSFields();
		// $fields->push(new TextField("Name", "Name"));
		return $fields;
	}
}