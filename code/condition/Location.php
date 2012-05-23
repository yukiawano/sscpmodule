<?php
require_once 'ConditionBase.php';

class Location extends ConditionBase{
	function doesSatisfy(CPEnvironment $env, $args){
		$locations = $env->getLocation();
		$locationString = $locations['Country'].' '.$locations['Region'].' '.$locations['City'];
		
		if(stristr($locationString, $args) != false){
			return true;
		}else{
			return false;
		}
	}
}