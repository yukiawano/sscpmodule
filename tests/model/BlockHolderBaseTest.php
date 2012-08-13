<?php
class BlockHolderBaseTest extends SapphireTest {
	static $fixture_file = '../blockholders.yml';
	
	public function testGetRelatedAudienceTypes() {
		$blockHolder = $this->objFromFixture('DefaultBlockHolder', 'blockholdera');
		$expected = array('TypeA', 'TypeB');
		$this->assertEquals($expected, $blockHolder->getRelatedAudienceTypes());
	}
	
	public function testGetBlockHolderTypes() {
		$result = BlockHolderBase::getBlockHolderTypes();
		$expected = array('DefaultBlockHolder' => 'Block Holder');
		$this->assertEquals($expected, $result);
	}
}