<?php
class DefaultBlockHolderTest extends SapphireTest {
	static $fixture_file = '../blockholders.yml';
	
	public function testGetRelatedAudienceTypes() {
		$blockHolder = $this->objFromFixture('DefaultBlockHolder', 'blockholdera');
		$expected = array('TypeA', 'TypeB');
		$this->assertEquals($expected, $blockHolder->getRelatedAudienceTypes());
	}
}