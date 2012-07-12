<?php
class LocationTest extends SapphireTest {
	function testNearestOptionedLocation() {
		// There are two nearest optioned location, tokyo and osaka.
		// And our location is kyoto.
		$env = CPEnvironmentStub::getCPEnvironment();
		$cond = new Location();
		$this->assertEquals(true, $cond->doesSatisfy($env, "nearest(osaka)"));
		$this->assertEquals(false, $cond->doesSatisfy($env, "nearest(tokyo)"));
	}
}