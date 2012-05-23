<?php
class AudienceTypeManager{
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
		// TODO Change to class loader.
		switch($conditionClass){
			case 'Location':
				require_once SSCP_PATH.'/code/condition/Location.php';
				return new Location();
			case 'NewComer':
				require_once SSCP_PATH.'/code/condition/NewComer.php';
				return new NewComer();
			case 'OS':
				require_once SSCP_PATH.'/code/condition/OS.php';
				return new OS();
		}
	}
}