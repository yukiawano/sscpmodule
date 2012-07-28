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
		$matchingRule = key($audienceTypeArray);
		
		$audienceTypes = new ArrayList();
		foreach($audienceTypeArray[$matchingRule] as $audienceTypeName => $conditionsArray) {
			$conditions = new ArrayList();
			foreach($conditionsArray as $condition => $args) {
				$conditions->push(new ArrayData(array('Name' => $condition, 'Args' => $args)));
			}
			$audienceTypes->push(new ArrayData(array(	'Name' => $audienceTypeName,
														'Conditions' => $conditions)));	
		}
		
		$result = array(	'MatchingRule' => $matchingRule,
							'AudienceTypes' => $audienceTypes );
		$template = new SSViewer('PrettyPrint');
		return $template->process(new ArrayData($result), array());
	}
	
	private function getConditionClass($conditionClass){
		return new $conditionClass;
	}
}