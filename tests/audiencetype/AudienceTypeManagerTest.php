<?php
class AudienceTypeManagerTest extends SapphireTest{
	
	function setUp(){
		// TODO move this to CPEnvironmentStub as utility method
		parent::setUp();
		setcookie("CPEnvironment","", time() - 3600); // Delete cookie before running the tests.
		unset($_COOKIE["CPEnvironment"]);
		setcookie("CPEnvLocation","", time() - 3600);
		unset($_COOKIE["CPEnvLocation"]);
	}
	
	function testGetAudienceTypesInclusive(){
		$audienceTypeManager = new AudienceTypeManager();
		$audienceTypes = array('InclusiveOR' => array(
				'NewComer' => array('NewComer' => 'true'),
				'ShigaResidents' => array('Location' => 'SHIGA', 'Device' => 'Linux')
				));
		$result = $audienceTypeManager->getAudienceTypes($audienceTypes, CPEnvironmentStub::getCPEnvironment());
		$this->assertEquals(array('NewComer', 'ShigaResidents'), $result);
	}
	
	function testGetAudienceTypesExclusive(){
		$audienceTypeManager = new AudienceTypeManager();
		$audienceTypes = array('ExclusiveOR' => array(
				'NewComer' => array('NewComer' => 'true'),
				'ShigaResidents' => array('Location' => 'SHIGA', 'Device' => 'Linux')
				));
		$result = $audienceTypeManager->getAudienceTypes($audienceTypes, CPEnvironmentStub::getCPEnvironment());
		$this->assertEquals(array('NewComer'), $result);
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