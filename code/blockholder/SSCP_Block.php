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
	
	public function getContent() {
		return $this->SnippetBase()->getContent();
	}
	
	public function validate() {
		$result = parent::validate();
		
		$env = CPEnvironment::getCPEnvironment();
		$audienceTypeLoader = new AudienceTypeLoader();
		$audienceTypesArray = $audienceTypeLoader->getAudienceTypes($env->getAudienceTypes());
		
		foreach($this->getAudienceTypes() as $audienceType) {
			if(!in_array($audienceType, $audienceTypesArray)) {
				$result->error("Invalid Audience Type - {$audienceType}");
			}
		}
		
		return $result;
	}
	
	
	public function getAudienceTypes() {
		return array_map('trim', explode(',', $this->AudienceType));
	}
}