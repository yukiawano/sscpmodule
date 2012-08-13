<?php
class BlockHolderBaseTest extends SapphireTest {
	static $fixture_file = '../blockholders.yml';
	
	public function testGetBlockHolderTypes() {
		$result = BlockHolderBase::getBlockHolderTypes();
		$expected = array('DefaultBlockHolder' => 'Block Holder');
		$this->assertEquals($expected, $result);
	}
}