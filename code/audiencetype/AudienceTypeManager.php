<?php
/**
 * AudienceTypeManager
 * @package sscp
 */
class AudienceTypeManager extends Object{
	public function getAudienceTypes($audienceTypes, CPEnvironment $env){
		$matchingRule = key($audienceTypes);
		$rules = $audienceTypes[$matchingRule];
		$results = array();
		foreach($rules as $audienceTypeName => $conditions){
			$match = true;
			foreach($conditions as $conditionClass => $conditionArgs){
				$condition = $this->getConditionClass($conditionClass);
				$result = $condition->doesSatisfy($env, $conditionArgs);
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