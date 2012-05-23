<?php
/**
 * Audience Type Loader
 * @package sscp
 */
class AudienceTypeLoader{
	public function load(){
		$result = Config::inst()->get("AudienceType", "AudienceTypes");
		return $result;
	}
}