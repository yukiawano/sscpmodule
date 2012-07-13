<?php
class LocationTest extends SapphireTest {
	function setUp(){
		parent::setUp();
		setcookie("CPEnvJSON","", time() - 3600); // Delete cookie before running the tests.
		unset($_COOKIE["CPEnvJSON"]);
		setcookie("CPEnvLocationJSON","", time() - 3600);
		unset($_COOKIE["CPEnvLocationJSON"]);
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
}