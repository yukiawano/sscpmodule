<?php

class AudienceTypeManagerTest extends SapphireTest{
	
	function setUp(){
		// TODO move this to CPEnvironmentStub as utility method
		parent::setUp();
		setcookie("CPEnv","", time() - 3600); // Delete cookie before running the tests.
		unset($_COOKIE["CPEnv"]);
		setcookie("CPEnvLocationJ","", time() - 3600);
		unset($_COOKIE["CPEnvLocationJ"]);
	}
	
	function testGetAudienceTypesInclusive(){
		$audienceTypeManager = new AudienceTypeManager();
		$audienceTypes = array('InclusiveOR' => array(
				'NewComer' => array('NewComer' => 'true'),
				'Kyoto' => array('Location' => 'Kyoto', 'Device' => 'Linux')
				));
		$result = $audienceTypeManager->getAudienceTypes($audienceTypes, CPEnvironmentStub::getCPEnvironment(), array('NewComer', 'Kyoto'));
		$this->assertEquals(array('NewComer', 'Kyoto'), $result);
	}
	
	function testGetAudienceTypesExclusive(){
		$audienceTypeManager = new AudienceTypeManager();
		$audienceTypes = array('ExclusiveOR' => array(
				'NewComer' => array('NewComer' => 'true'),
				'Kyoto' => array('Location' => 'Kyoto', 'Device' => 'Linux')
				));
		$result = $audienceTypeManager->getAudienceTypes($audienceTypes, CPEnvironmentStub::getCPEnvironment(), array('NewComer', 'Kyoto'));
		$this->assertEquals(array('NewComer'), $result);
	}
	
	function testGetAudienceTypeFiltered() {
		$audienceTypeManager = new AudienceTypeManager();
		$audienceTypes = array('ExclusiveOR' => array(
				'NewComer' => array('NewComer' => 'true'),
				'Kyoto' => array('Location' => 'kyoto'),
				'LinuxUser' => array('Device' => 'linux')
				));
		$env = CPEnvironmentStub::getCPEnvironment($audienceTypes);
		$result = $audienceTypeManager->getAudienceTypes($audienceTypes, $env, array('Kyoto', 'LinuxUser'));
		$this->assertEquals(array('Kyoto'), $result);
	}
	
	function testGetNearestOptionedLocations() {
		$audienceTypeManager = new AudienceTypeManager();
		$audienceTypes = array('ExclusiveOR' => array(
				'OsakaResidents' => array('Location' => 'nearest((34.693744,135.502151))'),
				'ShigaResidents' => array('Location' => 'shiga'),
				'KyotoResidents' => array('Location' => 'nearest((35.011642,135.768031))'),
				'TokyoResidents' => array('Location' => 'nearest((35.6895,139.691729))'),
		));
		
		$expected = array(	array('lat' => 34.693744, 'lon' => 135.502151), // Osaka
							array('lat' => 35.6895, 'lon' => 139.691729) // Tokyo
						  	);
		$this->assertEquals($expected, $audienceTypeManager->getNearestOptionedLocations($audienceTypes, array('OsakaResidents', 'TokyoResidents')));
	}
	
	function testPrettyPrint(){
		// TODO Change to use template tracked on https://github.com/yukiawano/sscpmodule/issues/18
		$audienceTypes = array('InclusiveOR' => array(
				'NewComer' => array('NewComer' => 'true'),
				'ShigaResidents' => array('Location' => 'SHIGA', 'Device' => 'Linux')
				));
		$expected = <<<EOT
<p>MatchingRule: InclusiveOR</p>
<ul>

<li>
	<strong>NewComer</strong>
	<ul>
	
		<li>NewComer: true</li>
	
	</ul>
</li>

<li>
	<strong>ShigaResidents</strong>
	<ul>
	
		<li>Location: SHIGA</li>
	
		<li>Device: Linux</li>
	
	</ul>
</li>

</ul>
EOT;
		
		$audienceTypeManager = new AudienceTypeManager();
		$result = $audienceTypeManager->prettyPrint($audienceTypes);
		$this->assertEquals($expected, $result);
	}
	
}