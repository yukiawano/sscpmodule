<?php
class BlockHolderTest extends SapphireTest {
	static $fixture_file = '../blockholders.yml';
	
	public function testGetRelatedAudienceTypes() {
		$blockHolder = $this->objFromFixture('BlockHolder', 'blockholdera');
		$expected = array('TypeA', 'TypeB');
		$this->assertEquals($expected, $blockHolder->getRelatedAudienceTypes());
	}
}