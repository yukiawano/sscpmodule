<?php
class SSCP_Block extends DataObject {
	
	static $db = array(
			'Title' => 'Varchar',
			'AudienceType' => 'Varchar'
		);
	
	static $has_one = array(
			'BlockHolder' => 'BlockHolder',
			'SnippetBase' => 'SnippetBase'
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		
		$fields->removeByName('BlockHolderID');
		$fields->removeByName('AudienceType');
		
		// Audience Types
		$audienceTypeLoader = new AudienceTypeLoader();
		$audienceTypesArray = $audienceTypeLoader->getAudienceTypes($audienceTypeLoader->load());
		$fields->push(new DropdownField('AudienceType', 'AudienceType', $audienceTypesArray));
		
		return $fields;
	}
	
}