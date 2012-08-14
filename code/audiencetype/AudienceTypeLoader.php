<?php
/**
 * Audience Type Loader
 * @package sscp
 */
class AudienceTypeLoader{
	
	/**
	 * Return audience types as array(list)
	 * @param array $audienceTypes
	 */
	public function getAudienceTypes($audienceTypes) {
		// TODO This function should be moved to proper class.
		
		$result = array();
		foreach($audienceTypes as $audienceTypeName => $conds) {
			//array_push($result, $audienceTypeName);
			$result[$audienceTypeName] = $audienceTypeName;
		}
		return $result;
	}
	
}