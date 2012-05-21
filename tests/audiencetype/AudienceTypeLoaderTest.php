<?php
/**
 * Tests for AudienceType
 *
 */

class AudienceTypeLoaderTest extends SapphireTest{
	function testLoad(){
		$audienceTypeLoader = new AudienceTypeLoader();
		$result = $audienceTypeLoader->load();
		$this->assertEquals($result,'abc');
	}
}