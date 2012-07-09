<?php
class CPEnvironmentTest extends SapphireTest {
	
	function testGetLatLon() {
		$result = CPEnvironment::getLatLon('kyoto');
		$expected = array('lat' => 35.1061038125824, 'lon' => 135.727367242386);
		
		$this->assertEquals($expected, $result);
	}
	
	
}