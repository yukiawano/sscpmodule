<?php
/**
 * Location
 * @package sscp
 */
class Location extends ConditionBase{
	
	var $javascript_file = 'sscp/code/condition/javascript/location.js';
	
	function doesSatisfy(CPEnvironment $env, $args) {
		$locations = $env->getLocation();
		$locationString = $locations['Country'].' '.$locations['Region'].' '.$locations['City'];
		
		if(stristr($locationString, $args) != false){
			return true;
		}else{
			return false;
		}
	}
}