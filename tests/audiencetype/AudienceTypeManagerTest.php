<?php
class AudienceTypeManagerTest extends SapphireTest{
	
	function testGetAudienceTypesInclusive(){
		$audienceTypeManager = new AudienceTypeManager();
		$audienceTypes = array('InclusiveOR' => array(
				'NewComer' => array('NewComer' => 'true'),
				'NewYorker' => array('Location' => 'NewYork', 'OS' => 'iOS')
				));
		$result = $audienceTypeManager->getAudienceTypes($audienceTypes);
		$this->assertEquals(array('NewComer', 'NewYorker'),$result);
	}
	
	function testGetAudienceTypesExclusive(){
		$audienceTypeManager = new AudienceTypeManager();
		$audienceTypes = array('ExclusiveOR' => array(
				'NewComer' => array('NewComer' => 'true'),
				'NewYorker' => array('Location' => 'NewYork', 'OS' => 'iOS')
				));
		$result = $audienceTypeManager->getAudienceTypes($audienceTypes);
		$this->assertEquals(array('NewComer'), $result);
	}
	
}