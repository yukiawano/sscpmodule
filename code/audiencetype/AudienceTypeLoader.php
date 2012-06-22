<?php
/**
 * Audience Type Loader
 * @package sscp
 */
class AudienceTypeLoader{
	public function load(){
		$audienceTypes = Config::inst()->get("AudienceTypeDefinition", "AudienceTypes");
		$matchingRule = Config::inst()->get("AudienceTypeDefinition", "MatchingRule");
		
		return array($matchingRule => $audienceTypes);
	}
	
	/**
	 * Return audience types as array(list)
	 * @param array $audienceTypes
	 */
	public function getAudienceTypes($audienceTypes) {
		$matchingRule = key($audienceTypes);
		$types = $audienceTypes[$matchingRule];
		$result = array();
		foreach($types as $audienceTypeName => $conds) {
			array_push($result, $audienceTypeName);
		}
		return $result;
	}
}