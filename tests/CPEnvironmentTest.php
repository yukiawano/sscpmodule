<?php
class CPEnvironmentTest extends SapphireTest {
	
	function setUp(){
		parent::setUp();
		$cache = SS_Cache::factory('sscp');
		$cache->remove(CPEnvironment::CacheKeyOfNearestLocations);
		
		CPEnvironmentStub::clearCookie();
	}
	
	function testGetNearestLocation() {
		$env = CPEnvironmentStub::getCPEnvironment(array('ExclusiveOR' => array(
				'Osaka' => array('Location' => 'nearest((34.693744,135.502151))'),
				'Tokyo' => array('Location' => 'nearest((35.6895,139.691729))'),
				'Sapporo' => array('Location' => 'nearest((43.062092,141.354377))')
				)));
		$result = $env->getNearestLocation(array('Sapporo', 'Tokyo')); // Current location is kyoto, thus Tokyo is the nearest location.
		$expected = "35.6895-139.691729";
		
		$this->assertEquals($expected, $result);
	}
	
	function testSetLocationManually() {
		$cpenvironment = CPEnvironmentStub::getCPEnvironment();
		$result = $cpenvironment->setLocationManually(34, 135);
		
		$this->assertEquals(34, $result['lat']);
		$this->assertEquals(135, $result['lon']);
		$this->assertEquals('Japan', $result['Country']);
		$this->assertEquals('DebugToolbar', $result['Source']);
	}
	
	function testDefaultLocation() {
		$defaultLocation = array(	'lon' => 40, 
									'lat' => 135, 
									'Country' => 'Japan', 
									'Region' => 'Kanto', 
									'City' => 'Tokyo', 
									'Source' => 'Default');
		
		$env = CPEnvironmentStub::getCPEnvironment(null, $defaultLocation);
		$result = $env->CPEnvGetLocation();
		
		$this->assertEquals($defaultLocation, $result);
	}
	
}