<?php
class DefaultBlockHolderTest extends SapphireTest {
	static $fixture_file = '../blockholders.yml';
	
	public function testGetRelatedAudienceTypes() {
		$blockHolder = $this->objFromFixture('DefaultBlockHolder', 'blockholdera');
		$expected = array('TypeA', 'TypeB', 'TypeC');
		$result = $blockHolder->getRelatedAudienceTypes();
		$this->assertEquals($expected, $result);
	}
	
	
	private function getBlockSequenceString($blocks) {
		$result = count($blocks);
		foreach($blocks as $block) {
			$result .= "-{$block->Title}";
		}
		return $result;
	}
	
	public function testGetBlocksSingle() {
		$audienceTypes = array(
				'TypeA' => array('Location' => 'Hokkaido'),
				'TypeB' => array('Location' => 'Akita'),
				'TypeC' => array('NewComer' => 'true'), // Only the type C is true
		);
		
		$env = CPEnvironmentStub::getCPEnvironment($audienceTypes);
		$blockHolder = $this->objFromFixture('DefaultBlockHolder', 'blockholdera');
		
		$result = $this->getBlockSequenceString($blockHolder->getBlocks($env));
		$expected = '1-BlockC';
		
		$this->assertEquals($expected, $result);
	}
	
	public function testGetBlocksMultiple() {
		$audienceTypes = array(
				'TypeA' => array('Location' => 'Hokkaido'),
				'TypeB' => array('Location' => 'Kyoto'),
				'TypeC' => array('NewComer' => 'true'), // Only the type C is true
		);
		
		$env = CPEnvironmentStub::getCPEnvironment($audienceTypes);
		$blockHolder = $this->objFromFixture('DefaultBlockHolder', 'blockholdera');
		
		$result = $this->getBlockSequenceString($blockHolder->getBlocks($env));
		$expected = '2-BlockB-BlockC';
		
		$this->assertEquals($expected, $result);
	}
	
	public function testGetContentButNoBlock() {
		$audienceTypes = array(
				'TypeA' => array('Location' => 'Hokkaido'),
				'TypeB' => array('Location' => 'Akita'),
				'TypeC' => array('Location' => 'Aomori'),
		);
		
		$env = CPEnvironmentStub::getCPEnvironment($audienceTypes);
		$blockHolder = $this->objFromFixture('DefaultBlockHolder', 'blockholdera');
		
		$result = $blockHolder->getBlock($env);
		$expected = null;
		
		$this->assertEquals($expected, $result);
	}
}