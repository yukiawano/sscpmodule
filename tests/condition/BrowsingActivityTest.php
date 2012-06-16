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
	
}