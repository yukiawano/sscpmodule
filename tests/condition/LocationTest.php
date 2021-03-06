<?php
class LocationTest extends SapphireTest {
	
	function setUp(){
		parent::setUp();
		$cache = SS_Cache::factory('sscp');
		$cache->remove(CPEnvironment::CacheKeyOfNearestLocations);
	}
	
	function testMatch() {
		$env = CPEnvironmentStub::getCPEnvironment(array(
				'Osaka' => array('Location' => 'Osaka'),
				'Tokyo' => array('Location' => 'Tokyo'),
				'Kyoto' => array('Location' => 'Kyoto')
				));
		$consideredAudienceTypes = array('Osaka', 'Tokyo', 'Kyoto');
		$cond = new Location();
		$this->assertEquals(true, $cond->doesSatisfy($env, 'Kyoto', $consideredAudienceTypes));
		$this->assertEquals(false, $cond->doesSatisfy($env, 'Tokyo', $consideredAudienceTypes));
	}
	
	
	function testRadiusInOption() {
		// Kyoto University(35.028388,135.780621) is in 100km from osaka station(34.702781,135.494723).
		// Tokyo University(35.714957,139.762049) is not in 100km from osaka station.
		
		$env = CPEnvironmentStub::getCPEnvironment(array(
				'Osaka' => array('Location' => 'nearest(osaka)')
				));
		$env->setLocation(array('lat' => 34.702781, 'lon' => 135.494723));
		$consideredAudienceTypes = array('Osaka');
		
		$cond = new Location();
		$this->assertEquals(true, $cond->doesSatisfy($env, 'in((35.028388,135.780621),100km)', $consideredAudienceTypes));
		$this->assertEquals(false, $cond->doesSatisfy($env, 'in((35.714957,139.762049),100km)', $consideredAudienceTypes));
	}
	
	function testNearestOptionedLocationWithLatLon() {
		// Latitude and longitude of Kyoto University
		// 35.028872,135.780673
		// Latitude and longitude of Tokyo University
		// 35.713207,139.762659
		
		$env = CPEnvironmentStub::getCPEnvironment(array(
				'StudentOfKyotoUniv' => array('Location' => 'nearest((35.028872,135.780673))'),
				'StudentOfTokyoUniv' => array('Location' => 'nearest((35.713207,139.762659))')
				));
		$consideredAudienceTypes = array('StudentOfKyotoUniv', 'StudentOfTokyoUniv');
		$cond = new Location();
		$this->assertEquals(true, $cond->doesSatisfy($env, 'nearest((35.028872,135.780673))', $consideredAudienceTypes));
		$this->assertEquals(false, $cond->doesSatisfy($env, 'nearest((35.713207,139.762659))', $consideredAudienceTypes));
	}
	
	function testParseParameter() {
		$loc = new Location();
		$expected = array('match' => array('location' => 'kyoto'));
		$this->assertEquals($expected, $loc->parseParameter('kyoto'));
		
		$expected = array('nearest' => array('location' => array('lat' => 135, 'lon' => 40)));
		$this->assertEquals($expected, $loc->parseParameter('nearest((135,40))'));
		
		$expected = array('in' => array('location' => array('lat' => 135, 'lon' => 40), 'distance' => 30));
		$this->assertEquals($expected, $loc->parseParameter('in((135,40),30km)'));
	}
}