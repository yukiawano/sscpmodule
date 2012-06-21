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
	
	const CPEnvLocationKey = 'CPEnvLocationJ';
	const CPEnvKey = 'CPEnv';
	
	private $values = array();
	private $valuesForRead = array();
	private static $env = null;
	
	/**
	 * Get CPEnvironment for current session
	 * @return CPEnvironment
	 */
	public static function getCPEnvironment(){
		if(self::$env == null) { 
			self::$env = $env = new CPEnvironment(); 
			
			if(Cookie::get(self::CPEnvKey) != null){
				self::$env->values = unserialize(Cookie::get(self::CPEnvKey));
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
	public function getLocation(){
		if(Cookie::get(self::CPEnvLocationKey) != null){
			return json_decode(Cookie::get(self::CPEnvLocationKey));
		}else{
			$result = Config::inst()->get("APIKey", "IPInfoDB");
			
			//Load the class
			$ipLite = new ip2location_lite();
			$ipLite->setKey($result);
			
			//Get errors and locations
			$locations = $ipLite->getCity($_SERVER['REMOTE_ADDR']);
			$errors = $ipLite->getError();
			
			$value = array('Country' => $locations['countryName'], 'Region' => $locations['regionName'], 'City' => $locations['cityName'], 'Source' => 'IPInfoDB');
			Cookie::set(self::CPEnvLocationKey, json_encode($value));
			return $value;
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
		Cookie::set(self::CPEnvKey, serialize($this->valuesForRead));
	}
}