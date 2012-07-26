<?php
/**
 * Location
 * 
 * match - address
 * nearest - latlon
 * in - latlon
 * @package sscp
 */
class Location extends ConditionBase{
	
	function doesSatisfy(CPEnvironment $env, $args) {
		$parsedArgs = $this->parseParameter($args);
		switch(key($parsedArgs)) {
			case 'nearest':
				$latLon = $parsedArgs['nearest']['location'];
				$key = "{$latLon['lat']}-{$latLon['lon']}";
				$nearestKey = $env->getNearestLocation();
				return ($nearestKey === $key);
			case 'in':
				$visitorLocation = $env->getLocation();
				$latLon = $parsedArgs['in']['location'];
				// The below formula is obtained from http://pastie.org/4269303 .
				$distance = (6378.137 * acos((sin(pi( ) * $visitorLocation['lat'] /180 ) * sin(pi() * $latLon['lat'] /180 )) + (cos(pi() * $visitorLocation['lat'] /180) * cos(pi() * $latLon['lat'] /180) * cos((pi() * $latLon['lon'] /180) - (pi( ) * $visitorLocation['lon'] / 180)))));
				return $distance < $parsedArgs['in']['distance'];
			case 'match':
				if(is_array($parsedArgs['match']['location'])){
					return false; // match option does not accept lat and lon
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
					
					// Return value of stristr is not bool
					return (stristr($locationString, $parsedArgs['match']['location']) != false);
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
			if(preg_match('/\((\-*[0-9\.]+),(\-*[0-9\.]+)\)/', $p, $m)) {
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