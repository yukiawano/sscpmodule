<?php
class DefaultBlockHolderTest extends SapphireTest {
	static $fixture_file = '../blockholders.yml';
	
	public function testGetRelatedAudienceTypes() {
		$blockHolder = $this->objFromFixture('DefaultBlockHolder', 'blockholdera');
		$expected = array('TypeA', 'TypeB', 'TypeC');
		$result = $blockHolder->getRelatedAudienceTypes();
		$this->assertEquals($expected, $result);
	}
	
	public function testGetContent() {
		$audienceTypes = array('ExclusiveOR' => array(
				'TypeA' => array('Location' => 'Hokkaido'),
				'TypeB' => array('Location' => 'Akita'),
				'TypeC' => array('NewComer' => 'true'),
		));
		
		$env = CPEnvironmentStub::getCPEnvironment($audienceTypes);
		$blockHolder = $this->objFromFixture('DefaultBlockHolder', 'blockholdera');
		
		$result = $blockHolder->getBlock($env)->Title;
		$expected = 'BlockC';
		
		$this->assertEquals($expected, $result);
	}
	
	public function testGetContentButNoBlock() {
		$audienceTypes = array('ExclusiveOR' => array(
				'TypeA' => array('Location' => 'Hokkaido'),
				'TypeB' => array('Location' => 'Akita'),
				'TypeC' => array('Location' => 'Aomori'),
		));
		
		$env = CPEnvironmentStub::getCPEnvironment($audienceTypes);
		$blockHolder = $this->objFromFixture('DefaultBlockHolder', 'blockholdera');
		
		$result = $blockHolder->getBlock($env);
		$expected = null;
		
		$this->assertEquals($expected, $result);
	}
}