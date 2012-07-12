<?php
/**
 * Location
 * @package sscp
 */
class Location extends ConditionBase{
	
	function doesSatisfy(CPEnvironment $env, $args) {
		if(preg_match('/nearest\((.+)\)/', $args, $matches)) {
			$nearestLocation = $env->getNearestLocation();
			if(strtolower($matches[1]) === strtolower($nearestLocation)) {
				return true;
			} else {
				return false;
			}
		} else {
			$getValue = function(& $value) {
				if(isset($value)) {
					return $value;
				} else {
					return null;
				}
			};
			
			$locations = $env->getLocation();
			$locationString = $getValue($locations['Country']) . ' '
			. $getValue($locations['Region']) . ' '
			. $getValue($locations['City']) . ' '
			. $getValue($locations['County']) . ' '
			. $getValue($locations['Road']) . ' '
			. $getValue($locations['PublicBuilding']) . ' '
			. $getValue($locations['Postcode']);
			
			if(stristr($locationString, $args) != false){
				return true;
			}else{
				return false;
			}	
		}
	}
}