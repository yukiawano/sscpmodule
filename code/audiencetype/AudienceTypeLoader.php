<?php
/**
 * Audience Type Loader
 * @author yuki
 *
 */
class AudienceTypeLoader{
	public function load(){
		$result = Config::inst()->get("AudienceType", "AudienceTypes");
		return $result;
	}
}