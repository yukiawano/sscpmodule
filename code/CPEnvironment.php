<?php
/**
 * CPEnvironment
 * 
 * CPEnvironment provides variables and information of users.
 * Condition rules only talks to CPEnvironment.
 * This can make condition rules stateless.
 * And it make condition rules more testable and debugable.
 * 
 * @link /docs/en/topics/what-is-cpenvironment
 * @package sscp
 */
class CPEnvironment {
	
	const CPEnvLocationKey = 'CPEnvLocationJSON';
	const CPEnvKey = 'CPEnvJSON';
	const CacheKeyOfNearestLocations = 'NearestLocationsArray';
	
	private $values = array();
	private $valuesForRead = array();
	private static $env = null;
	protected $audienceTypes = null;
	protected $defaultLocation = null;
	protected $ipInfoDbAPIKey = null;
	
	/**
	 * Get CPEnvironment for current session
	 * @return CPEnvironment
	 */
	public static function getCPEnvironment(){
		if(self::$env == null) { 
			self::$env = $env = new CPEnvironment(); 
			
			if(Cookie::get(self::CPEnvKey) != null){
				self::$env->values = json_decode(Cookie::get(self::CPEnvKey), true);
				self::$env->valuesForRead = self::$env->values;
			}
			
			return self::$env;
		}else{
			return self::$env;
		}
	}
	
	/**
	 * Return platform of current session
	 */
	public function getPlatform() {
		$userAgent = new CI_User_agent();
		return $userAgent->platform();
	}
	
	/**
	 * Return browser of current session
	 */
	public function getBrowser() {
		$userAgent = new CI_User_agent();
		return $userAgent->browser();
	}
	
	/**
	 * Return location of current session.
	 * 
	 * If it needs to get location of current session, it tries to get.
	 * This method would cost.
	 */
	public function getLocation() {
		if(Cookie::get(self::CPEnvLocationKey) != null) {
			return json_decode(Cookie::get(self::CPEnvLocationKey), true);
		} else {
			return $this->resetToDefaultLocation();
		}
	}
	
	/**
	 * Clear location
	 * 
	 * @return array Location
	 */
	public function resetToDefaultLocation() {
		$location = $this->getDefaultLocation();
		Cookie::set(self::CPEnvLocationKey, json_encode($location));
		return $location;
	}
	
	/**
	 * Set location manually
	 * @param double $lat
	 * @param double $lon
	 */
	public function setLocationManually($lat, $lon) {
		$url =  "http://nominatim.openstreetmap.org/reverse?format=xml&lat={$lat}&lon={$lon}&zoom=18&addressdetails=1";
		$xml =  simplexml_load_file($url);
		$address = $xml->addressparts[0];
		
		$value = array(	'lat' => $lat, 
				       	'lon' => $lon,
						'Country' => (string)$address->country,
						'Region' => (string)$address->state . ' ' . (string)$address->region,
						'City' => (string)$address->city,
						'County' => (string)$address->county,
						'Road' => (string)$address->road,
						'PublicBuilding' => (string)$address->public_building,
						'Postcode' => (string)$address->postcode,
						'Source' => 'DebugToolbar');
		
		Cookie::set(self::CPEnvLocationKey, json_encode($value));
		
		// Remove cache about the nearest location.
		$cache = SS_Cache::factory('sscp');
		$cache->remove(self::CacheKeyOfNearestLocations);
		
		return $value;
	}
	
	/**
	 * Return API Key of IPInfoDB
	 * (This method is provided for performance(Reducing IOs)
	 */
	private function getIpInfoDbAPIKey() {
		if($this->ipInfoDbAPIKey) {
			$this->ipInfoDbAPIKey = Config::inst()->get("APIKey", "IPInfoDB");
		}
		return $this->ipInfoDbAPIKey;
	}
	
	/**
	 * Return default location
	 * (This method is provided for testing(Stub) and performance(Reducing IOs)
	 */
	private function getDefaultLocation() {
		if($this->defaultLocation == null) {
			$location = Config::inst()->get("DefaultLocation", "Location");
			
			$this->defaultLocation = array(
					'lon' => $location['Lon'],
					'lat' => $location['Lat'],
					'Country' => $location['Country'],
					'Region' => $location['Region'],
					'City' => $location['City'],
					'Source' => 'Default');
		}
		
		return $this->defaultLocation;
	}
	
	/**
	 * Return audience types
	 * (This method is provided for testing(Stub) and performance(Reducing IOs).
	 */
	public function getAudienceTypes() {
		if($this->audienceTypes == null) {
			$audienceTypeLoader = new AudienceTypeLoader();
			$this->audienceTypes = $audienceTypeLoader->load();
		}
		return $this->audienceTypes;
	}
	
	/**
	 * Return nearest location to a user
	 * 
	 * CPEnvironment holds the nearest location to the user
	 */
	public function getNearestLocation($consideredAudienceTypes) {
		// List up nearest-optioned locations for $result
		$audienceTypeManager = new AudienceTypeManager();
		
		$audienceTypes = $this->getAudienceTypes();
		$nearestOptionedLocations = $audienceTypeManager->getNearestOptionedLocations($audienceTypes, $consideredAudienceTypes);
		
		$result = array();
		foreach ($nearestOptionedLocations as $nearestOptionedLocation) {
			$key = "{$nearestOptionedLocation['lat']}-{$nearestOptionedLocation['lon']}";
			$result[$key] = $nearestOptionedLocation;
		}
		
		// Calculate the nearest location to the user
		$distanceFunc = function ($a, $b) {
			return pow($a['lat'] - $b['lat'], 2) + pow($a['lon'] - $b['lon'], 2);
		};
		
		$location = $this->getLocation();
		$minimumDistance = pow(360,2) * 2; // Set maximum number that can be considered
		$minimumKey = null;
		foreach ($result as $key => $latLon) {
			$distance = $distanceFunc($latLon, $location);
			if($distance < $minimumDistance) {
				$minimumDistance = $distance;
				$minimumKey = $key;
			}
		}
		
		return $minimumKey;
	}
		
	/**
	 * Set Value
	 * @param string $key
	 * @param string $default
	 * @return object
	 */
	public function get($key, $default = null){
		if(isset($this->valuesForRead[$key])){
			return $this->valuesForRead[$key];
		}else{
			return $default;
		}
	}
	
	/**
	 * Get Value
	 * @param string $key
	 * @param object $value Object must be serializable
	 */
	public function set($key, $value){
		$this->values[$key] = $value;
	}
	
	/**
	 * Commit the changes in the environment.
	 * 
	 * CPEnvironment does not store the values that is set until commit is called.
	 * Because same conditions would be called multiple times in the assertion session,
	 * some changes would affect to the later evaluation.
	 * Commit is changed from audience type manager.
	 * So developers who uses CPEnvironment does NOT need call this method manually.
	 */
	public function commit(){
		$this->valuesForRead = $this->values;
		Cookie::set(self::CPEnvKey, json_encode($this->valuesForRead));
	}
}