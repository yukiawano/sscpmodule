<?php
/**
 * Tests for AudienceType
 *
 */

class AudienceTypeLoaderTest extends SapphireTest{
	function testLoad(){
		$audienceTypeLoader = new AudienceTypeLoader();
		$result = $audienceTypeLoader->load();
		$expected = array("InclusiveOR" => array(
				"NewComer" => array("NewComer" => true),
				"NewYoker" => array(
						"Location" => "NewYork",
						"OS" => "iOS")
				));
		$this->assertEquals($result,$expected);
	}
}