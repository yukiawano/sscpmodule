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
		$result = $audienceTypeManager->getAudienceTypes($audienceTypes, CPEnvironmentStub::getCPEnvironment());
		$this->assertEquals(array('NewComer', 'Kyoto'), $result);
	}
	
	function testGetAudienceTypesExclusive(){
		$audienceTypeManager = new AudienceTypeManager();
		$audienceTypes = array('ExclusiveOR' => array(
				'NewComer' => array('NewComer' => 'true'),
				'Kyoto' => array('Location' => 'Kyoto', 'Device' => 'Linux')
				));
		$result = $audienceTypeManager->getAudienceTypes($audienceTypes, CPEnvironmentStub::getCPEnvironment());
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
				'OsakaResidents' => array('Location' => 'nearest(osaka)'),
				'ShigaResidents' => array('Location' => 'shiga'),
				'KyotoResidents' => array('Location' => 'nearest(kyoto)'),
				'TokyoResidents' => array('Location' => 'nearest(tokyo)')
		));
		
		$expected = array('osaka', 'kyoto', 'tokyo');
		$this->assertEquals($expected, $audienceTypeManager->getNearestOptionedLocations($audienceTypes));
	}
	
	function testPrettyPrint(){
		$audienceTypes = array('InclusiveOR' => array(
				'NewComer' => array('NewComer' => 'true'),
				'ShigaResidents' => array('Location' => 'SHIGA', 'Device' => 'Linux')
				));
		$expected = <<<EOT
MatchingRule: InclusiveOR<br />
---<br />
NewComer:<br />
&nbsp;&nbsp;NewComer: true<br />
ShigaResidents:<br />
&nbsp;&nbsp;Location: SHIGA<br />
&nbsp;&nbsp;Device: Linux<br />

EOT;
		
		$audienceTypeManager = new AudienceTypeManager();
		$result = $audienceTypeManager->prettyPrint($audienceTypes);
		$this->assertEquals($expected, $result);
	}
	
}