<?php
class LocationTest extends SapphireTest {
	
	function setUp(){
		parent::setUp();
	}
	
	function testNearestOptionedLocation() {
		// There are two nearest optioned location, tokyo and osaka.
		// And our location is kyoto.
		$env = CPEnvironmentStub::getCPEnvironment(array('ExclusiveOR' => array(
				'Osaka' => array('Location' => 'nearest(osaka)'),
				'Tokyo' => array('Location' => 'nearest(tokyo)')
				)));
		$cond = new Location();
		$this->assertEquals(true, $cond->doesSatisfy($env, "nearest(osaka)"));
		$this->assertEquals(false, $cond->doesSatisfy($env, "nearest(tokyo)"));
	}
	
	function testParseParameter() {
		$loc = new Location();
		$expected = array('match' => array('location' => 'kyoto'));
		$this->assertEquals($expected, $loc->parseParameter('kyoto'));
		
		$expected = array('nearest' => array('location' => 'kyoto'));
		$this->assertEquals($expected, $loc->parseParameter('nearest(kyoto)'));
		
		$expected = array('nearest' => array('location' => array('lat' => 135, 'lon' => 40)));
		$this->assertEquals($expected, $loc->parseParameter('nearest((135,40))'));
		
		$expected = array('in' => array('location' => array('lat' => 135, 'lon' => 40), 'distance' => 30));
		$this->assertEquals($expected, $loc->parseParameter('in((135,40),30km)'));
	}
}