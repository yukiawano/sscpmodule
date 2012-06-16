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
}