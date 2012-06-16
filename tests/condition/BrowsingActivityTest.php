<?php
/**
 * Test case for Browsing Activity
 * @author yuki
 */
class BrowsingActivityTest extends SapphireTest {
	
	function setUp(){
		parent::setUp();
		setcookie("CPEnvironment","", time() - 3600); // Delete cookie before running the tests.
		unset($_COOKIE["CPEnvironment"]);
		setcookie("CPEnvLocation","", time() - 3600);
		unset($_COOKIE["CPEnvLocation"]);
	}
	
	function testLogAccessOfLongUrl() {
		$stubEnv = CPEnvironmentStub::getCPEnvironment();
		$browsingActivity = new BrowsingActivity();
		$browsingActivity->logAccesse($stubEnv, '/this-is-long-url-and-more-than-50-chars/this-is-57-chars');
		
		$stubEnv->commit();
		$result = $stubEnv->get('BrowsingActivity', null);
		
		$this->assertNotNull($result);
		
		//Assert the length of the url is 50chars
		$firstUrl = $result[0];
		$this->assertEquals($firstUrl, '/this-is-long-url-and-more-than-50-chars/this-is-5');	
	}
	
	function testLogAccessOfDoesNotStoreAlreadyStoredUrl() {
		$stubEnv = CPEnvironmentStub::getCPEnvironment();
		$browsingActivity = new BrowsingActivity();
		
		$browsingActivity->logAccesse($stubEnv, '/url-one');
		$stubEnv->commit();
		
		$browsingActivity->logAccesse($stubEnv, '/url-two');
		$stubEnv->commit();
		
		$browsingActivity->logAccesse($stubEnv, '/url-one');
		$stubEnv->commit();
		
		$result = $stubEnv->get('BrowsingActivity', null);
		
		$this->assertNotNull($result);
		$this->assertEquals(2, count($result));
		
		$this->assertEquals('/url-two', $result[0]);
		$this->assertEquals('/url-one', $result[1]);
	}
	
	function testLogAccessOfMaxNum() {
		$stubEnv = CPEnvironmentStub::getCPEnvironment();
		$browsingActivity = new BrowsingActivity();
		
		for($i = 0; $i < 40; $i++){
			$browsingActivity->logAccesse($stubEnv, "/url{$i}");
			$stubEnv->commit();
			
			$result = $stubEnv->get('BrowsingActivity', null);
			$this->assertEquals(($i + 1), count($result));
		}
		
		$browsingActivity->logAccesse($stubEnv, "/url_41");
		$stubEnv->commit();
		
		$result = $stubEnv->get('BrowsingActivity', null);
		$this->assertEquals(40, count($result));
		
		$this->assertEquals('/url1', $result[0]);
		$this->assertEquals('/url_41', $result[39]);
	}
	
	function testHasAccessedTo() {
		$stubEnv = CPEnvironmentStub::getCPEnvironment();
		$browsingActivity = new BrowsingActivity();
		
		$browsingActivity->logAccesse($stubEnv, "/sample_url_one");
		$stubEnv->commit();
		
		$browsingActivity->logAccesse($stubEnv, "/sample_url_two");
		$stubEnv->commit();
		
		// Exact match
		$this->assertTrue($browsingActivity->hasAccessedTo($stubEnv, '/sample_url_one'));
		
		// False case
		$this->assertFalse($browsingActivity->hasAccessedTo($stubEnv, '/sample_url_one_and_two'));
		
		// Prefix match
		$this->assertTrue($browsingActivity->hasAccessedTo($stubEnv, '/sample'));
	}
	
}