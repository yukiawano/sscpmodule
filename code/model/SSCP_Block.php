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
	
	function getCMSFields() {
		$fields = parent::getCMSFields();
		
		// Get audience types
		$fields->removeByName('AudienceType');
		
		$audienceTypeLoader = new AudienceTypeLoader();
		$audienceTypesArray = $audienceTypeLoader->load();
		$audienceTypes = $audienceTypeLoader->getAudienceTypes($audienceTypesArray);
		
		$fields->push(new DropdownField('AudienceType', 'AudienceType', $audienceTypes));
		
		return $fields;
	}
}