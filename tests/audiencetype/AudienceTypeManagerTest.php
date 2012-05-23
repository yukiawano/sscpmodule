<?php
class AudienceTypeManagerTest extends SapphireTest{
	
	function setUp(){
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
				'ShigaResidents' => array('Location' => 'SHIGA', 'OS' => 'Ubuntu')
				));
		$result = $audienceTypeManager->getAudienceTypes($audienceTypes);
		$this->assertEquals(array('NewComer', 'ShigaResidents'), $result);
	}
	
	function testGetAudienceTypesExclusive(){
		$audienceTypeManager = new AudienceTypeManager();
		$audienceTypes = array('ExclusiveOR' => array(
				'NewComer' => array('NewComer' => 'true'),
				'ShigaResidents' => array('Location' => 'SHIGA', 'OS' => 'Ubuntu')
				));
		$result = $audienceTypeManager->getAudienceTypes($audienceTypes);
		$this->assertEquals(array('NewComer'), $result);
	}
	
}