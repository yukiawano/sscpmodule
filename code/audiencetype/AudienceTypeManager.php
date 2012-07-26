<?php
/**
 * AudienceTypeManager
 * @package sscp
 */
class AudienceTypeManager extends Object{
	public function getAudienceTypes($audienceTypes, CPEnvironment $env, $consideredTypes){
		$matchingRule = key($audienceTypes);
		$rules = $audienceTypes[$matchingRule];
		$results = array();
		foreach($rules as $audienceTypeName => $conditions){
			if($consideredTypes != null && !in_array($audienceTypeName, $consideredTypes)) {
				continue; // When the audience type is not in consideredTypes, then skip.
			}
			$match = true;
			foreach($conditions as $conditionClass => $conditionArgs){
				$condition = $this->getConditionClass($conditionClass);
				$result = $condition->doesSatisfy($env, $conditionArgs, $consideredTypes);
				if(!$result){
					$match = false;
				}
			}
			if($match){
				array_push($results, $audienceTypeName);
				if($matchingRule == 'ExclusiveOR'){
					$env->commit();
					return $results;
				}
			}
		}
		
		$env->commit();
		return $results;
	}
	
	/**
	 * Get locations that is marked as nearest
	 * @param mixed $audienceTypes
	 * @param array $consideredAudienceTypes Considered audience types with string array.
	 */
	public function getNearestOptionedLocations($audienceTypes, $consideredAudienceTypes) {
		$getLocation = function($p) {
			if(preg_match('/\((\-*[0-9\.]+),(\-*[0-9\.]+)\)/', $p, $m)) {
				return array('lat' => $m[1], 'lon' => $m[2]);
			} else {
				return $p;
			}
		};
		
		$matchingRule = key($audienceTypes);
		$rules = $audienceTypes[$matchingRule];
		$results = array();
		foreach($rules as $audienceTypeName => $conditions) {
			if(in_array($audienceTypeName, $consideredAudienceTypes)) {
				foreach($conditions as $conditionClass => $conditionArgs) {
					if(preg_match('/nearest\((.+)\)/', $conditionArgs, $matches)){
						array_push($results, $getLocation($matches[1]));
					}
				}
			}
		}
		
		return $results;
	}
	
	/**
	 * Return pretty print of AudienceTypes
	 * @param array $audienceTypes
	 */
	public function prettyPrint($audienceTypeArray) {	
		// TODO This should be handled by template.
		
		$matchingRule = key($audienceTypeArray);
		$result = "MatchingRule: " . $matchingRule . "\n---\n";
		
		$audienceTypes = $audienceTypeArray[$matchingRule];
		foreach($audienceTypes as $audienceTypeName => $conditions) {
			$result .= $audienceTypeName . ":\n";
			foreach($conditions as $condition => $args) {
				$result .= "&nbsp;&nbsp;" . $condition . ": " . $args . "\n";
			}
		}
		
		return nl2br($result);
	}
	
	private function getConditionClass($conditionClass){
		return new $conditionClass;
	}
}