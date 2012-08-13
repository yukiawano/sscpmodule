<?php

class SSCP_BlockTest extends SapphireTest {
	static $fixture_file = '../blockholders.yml';
	
	public function testGetAudienceTypes() {
		// Single
		$blockA = $this->objFromFixture('SSCP_Block', 'blocka');
		$result = $blockA->getAudienceTypes();
		$expected = array('TypeA');
		$this->assertEquals($expected, $result);
		
		// Multiple
		$blockC = $this->objFromFixture('SSCP_Block', 'blockc');
		$result = $blockC->getAudienceTypes();
		$expected = array('TypeA', 'TypeC');
		$this->assertEquals($expected, $result);
	}
}