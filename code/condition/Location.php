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
	
	/**
	 * Parse parameter and return in as array
	 * @example
	 * nearest(kyoto) => array('nearest' => array('location' => 'kyoto'))
	 * in((135,40), 10km) => array('in' => array('distance' => '10', 'location' => array('lat' => '135', 'lon' => '40'))
	 * kyoto => array('match' => array('location' => 'kyoto')' 
	 * 
	 * @param string $param
	 */
	function parseParameter($param) {
		$getLocation = function($p) {
			if(preg_match('/\(([0-9]+),([0-9]+)\)/', $p, $m)) {
				return array('lat' => $m[1], 'lon' => $m[2]);
			} else {
				return $p;
			}
		};
		
		if(preg_match('/nearest\((.+)\)/', $param, $matches)) {
			return array('nearest' => array('location' => $getLocation($matches[1])));
		} else if (preg_match('/in\((.+),([0-9]+)km\)/', $param, $matches)) { 
			return array('in' => array('location' => $getLocation($matches[1]), 'distance' => $matches[2]));
		} else {
			return array('match' => array('location' => $getLocation($param)));
		}
	}
}