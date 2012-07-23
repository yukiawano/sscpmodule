<?php
class CPEnvironmentTest extends SapphireTest {
	
	function setUp(){
		parent::setUp();
		$cache = SS_Cache::factory('sscp');
		$cache->remove(CPEnvironment::CacheKeyOfNearestLocations);
	}
	
	function testGetLatLon() {
		$result = CPEnvironment::getLatLon('kyoto');
		$expected = array('lat' => 35.1061038125824, 'lon' => 135.727367242386);
		
		$this->assertEquals($expected, $result);
	}
	
	function testGetNearestLocation() {
		$cpenvironment = CPEnvironmentStub::getCPEnvironment(array('ExclusiveOR' => array(
				'Osaka' => array('Location' => 'nearest(osaka)'),
				'Tokyo' => array('Location' => 'nearest(tokyo)')
				)));
		$result = $cpenvironment->getNearestLocation();
		$expected = array('lat' => 34.6852929, 'lon' => 135.5146944);
		
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
	
}