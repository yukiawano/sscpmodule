<?php
/**
 * Audience Type Loader
 * @package sscp
 */
class AudienceTypeLoader{
	public function load(){
		$audienceTypes = Config::inst()->get("AudienceType", "AudienceTypes");
		$matchingRule = Config::inst()->get("AudienceType", "MatchingRule");
		
		return array($matchingRule => $audienceTypes);
	}
}