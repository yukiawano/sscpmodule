<?php
/**
 * Browsing Activity Condition
 * 
 * Condition that whether a user has accessed to specified url.
 * 
 * Notes:
 *  * Domain is ignored.
 *  * Url is stored for top 50 chars and latest 40 urls.
 *  * Prefix match.
 * 
 * @example
 * BrowsingActivity: /blog
 * 
 * This matches to
 *  * http://yourdomain/blog/index
 *  * http://yourdomain/blog
 *
 */
class BrowsingActivity extends ConditionBase {
	
	const cookieKey = 'BrowsingActivity';
	const maxUrlLength = 50;
	const maxUrlSize = 40;
	
	public function doesSatisfy(CPEnvironment $env, $args, $consideredAudienceTypes) {
		return $this->hasAccessedTo($env, $args);
	}
	
	// Utility methos, they should be put on more proper location...
	
	public function logAccesse(CPEnvironment $env, $url) {
		$shortUrl = substr($url, 0, self::maxUrlLength);
		
		$accessedSites = $env->get(self::cookieKey, array());
		if(($key = array_search($shortUrl, $accessedSites)) !== false) {
			// When the url is already stored, delete it.
			array_splice($accessedSites, $key, 1);
		}
		
		// Delete and make the length of the array smaller than (maxUrlSize - 1)
		if(count($accessedSites) >= self::maxUrlSize) {
			$accessedSites = array_slice($accessedSites, (count($accessedSites) - (self::maxUrlSize - 1)));
		}
		
		array_push($accessedSites, $shortUrl);
		
		$env->set(self::cookieKey, $accessedSites);
	}
	
	public function hasAccessedTo(CPEnvironment $env, $url) {
		$accessedSites = $env->get(self::cookieKey, array());
		
		foreach($accessedSites as $site) {
			if(strpos($site, $url, 0) === 0) {
				return true;
			}
		}
		
		return false;
	}
}