<?php
class SSCP_Block extends DataObject {
	
	static $db = array(
			'Title' => 'Varchar',
			'AudienceType' => 'Varchar'
		);
	
	static $has_one = array(
			'BlockHolder' => 'DefaultBlockHolder',
			'SnippetBase' => 'SnippetBase'
	);
	
	public function getFields($blockHolderId) {
		$fields = new FieldList();
		
		$fields->push(new TextField('Title', 'Title'));
		$fields->push(new TextField('AudienceType', 'AudienceType'));
		
		return $fields;
	}
	
	public function getContent() {
		return $this->SnippetBase()->getContent();
	}
	
	public function getCMSFields() {
		// $fields = parent::getCMSFields();
		
		$fields = parent::getCMSFields();
		// $fields->removeByName('BlockHolderID');
		
		
		// Audience Types
		/*
		$env = CPEnvironment::getCPEnvironment();
		$audienceTypeLoader = new AudienceTypeLoader();
		$audienceTypesArray = $audienceTypeLoader->getAudienceTypes($env->getAudienceTypes());
		$fields->push(new DropdownField('AudienceType', 'AudienceType', $audienceTypesArray));
		*/
		return $fields;
	}
	
	public function getAudienceTypes() {
		return array_map('trim', explode(',', $this->AudienceType));
	}
}