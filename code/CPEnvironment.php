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
		if(Cookie::get(self::CPEnvLocationKey) != null){
			return json_decode(Cookie::get(self::CPEnvLocationKey), true);
		}else{
			$result = Config::inst()->get("APIKey", "IPInfoDB");
			
			//Load the class
			$ipLite = new ip2location_lite();
			$ipLite->setKey($result);
			
			//Get errors and locations
			$locations = $ipLite->getCity($_SERVER['REMOTE_ADDR']);
			$errors = $ipLite->getError();
			
			$value = array('lon' => $locations['longitude'],
						    'lat'  => $locations['latitude'],
							'Country' => $locations['countryName'],
							'Region' => $locations['regionName'],
							'City' => $locations['cityName'],
							'Source' => 'IPInfoDB');
			
			Cookie::set(self::CPEnvLocationKey, json_encode($value));
			return $value;
		}
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
		return $value;
	}
	
	/**
	 * Return audience types
	 * (This method is provided for testing(Stub) and performance(Reducing IOs).
	 */
	private function getAudienceTypes() {
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
	public function getNearestLocation() {
		// List up nearest-optioned locations for $result
		$cache = SS_Cache::factory('sscp');
		if(!($serializedResult = $cache->load(self::CacheKeyOfNearestLocations))) {
			$audienceTypeManager = new AudienceTypeManager();
			
			$audienceTypes = $this->getAudienceTypes();
			$nearestOptionedLocations = $audienceTypeManager->getNearestOptionedLocations($audienceTypes);
			
			$result = array();
			foreach ($nearestOptionedLocations as $nearestOptionedLocation) {
				if(is_array($nearestOptionedLocation)) {
					array_push($result, $nearestOptionedLocation);
				} else {
					$latLon = CPEnvironment::getLatLon($nearestOptionedLocation);
					array_push($result, $latLon);
				}
			}
			$cache->save(serialize($result), self::CacheKeyOfNearestLocations);	
		} else {
			$result = unserialize($serializedResult);
		}
		
		// Calculate the nearest location to the user
		$distanceFunc = function ($a, $b) {
			return pow($a['lat'] - $b['lat'], 2) + pow($a['lon'] - $b['lon'], 2);
		};
		
		$location = $this->getLocation();
		$nearestLocation = null;
		$minimumDistance = pow(200,2) * 2;
		foreach ($result as $latLon) {
			$distance = $distanceFunc($latLon, $location);
			if($distance < $minimumDistance) {
				$minimumDistance = $distance;
				$nearestLocation = $latLon;
			}
		}
		
		return $nearestLocation;
	}
	
	/**
	 * Return lat and lon of address by using Nominatim API
	 * https://wiki.openstreetmap.org/wiki/Nominatim
	 * 
	 * @param string $address
	 */
	public static function getLatLon($address) {
		$cache = SS_Cache::factory('sscp_location');
		
		if($latLon = $cache->load($address)) {
			return unserialize($latLon);
		} else {
			$encodedAddress = urlencode($address);
			$url = "http://nominatim.openstreetmap.org/search?q={$encodedAddress}&format=xml";
			$xml =  simplexml_load_file($url);
			$place = $xml->place[0];
			
			$latLon = array('lat' => (float)$place['lat'],
							 'lon' => (float)$place['lon']);
			$cache->save(serialize($latLon), $address);
		}
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