<?php
/**
 * Tests for AudienceType
 *
 */

class AudienceTypeLoaderTest extends SapphireTest{
	function testGetAudienceTypes() {
		$data = array("InclusiveOR" => array(
				"NewComer" => array("NewComer" => true),
				"NewYorker" => array(
						"Location" => "NewYork",
						"Device" => "Linux")
		));
		$expected = array('NewComer' => 'NewComer', 'NewYorker' => 'NewYorker');
		
		$audienceTypeLoader = new AudienceTypeLoader();
		$result = $audienceTypeLoader->getAudienceTypes($data);
		
		$this->assertEquals($expected, $result);
	}
}