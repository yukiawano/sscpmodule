<?php
/**
 * Tests for AudienceType
 *
 */

class AudienceTypeLoaderTest extends SapphireTest{
	function testLoad(){
		// You may need to rename mysite/_config/audiencetype.yml not to load the configuration.
		
		$audienceTypeLoader = new AudienceTypeLoader();
		$result = $audienceTypeLoader->load();
		$expected = array("InclusiveOR" => array(
				"NewComer" => array("NewComer" => true),
				"NewYorker" => array(
						"Location" => "NewYork",
						"Device" => "Linux")
				));
		$this->assertEquals($result,$expected);
	}
	
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