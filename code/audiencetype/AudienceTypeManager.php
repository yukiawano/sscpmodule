<?php
/**
 * AudienceTypeManager
 * @package sscp
 */
class AudienceTypeManager extends Object{
	public function getAudienceTypes($audienceTypes){
		$matchingRule = key($audienceTypes);
		$rules = $audienceTypes[$matchingRule];
		$env = CPEnvironment::getCPEnvironment();
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
	
	private function getConditionClass($conditionClass){
		// TODO Change to load classes automatically from the conditions directory.
		switch($conditionClass){
			case 'Location':
				return new Location();
			case 'NewComer':
				return new NewComer();
			case 'OS':
				return new OS();
		}
	}
}