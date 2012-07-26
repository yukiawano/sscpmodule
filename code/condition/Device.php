<?php
/**
 * Device(OS, Browser)
 * 
 * This condition uses User_agent.php in third party directory.
 * e.g. Linux Firefox
 * 
 * @package sscp
 */
class Device extends ConditionBase{
	public function doesSatisfy(CPEnvironment $env, $args, $consideredAudienceTypes){
		$platform = $env->getPlatform();
		$browser = $env->getBrowser();
		
		$clientEnv = "{$platform} {$browser}";
		
		if(stristr($clientEnv, $args) != false){
			return true;
		}else{
			return false;
		}
	}
}