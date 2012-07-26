<?php
class ManualFlag extends ConditionBase {
	
	const ManualFlagPrefix = 'MF';
	
	function doesSatisfy(CPEnvironment $env, $args, $consideredAudienceTypes) {
		$flagName = $args;
		
		$result = $env->get("{self::ManualFlagPrefix}_{$flagName}", false);
		
		if($result) {
			return true;
		} else {
			return false;
		}
	}
	
}