<?php
/**
 * CPEnvironment
 * 
 * CPEnvironment provides variables and information of users.
 * Condition rules only talks to CPEnvironment.
 * This can make condition rules stateless.
 * And it make condition rules more testable and debugable.
 */
class CPEnvironment {
	private $values = array();
	private $valuesForRead = array();
	
	public static function getCPEnvironment(){
		$env = new CPEnvironment();
		if(isset($_COOKIE["CPEnvironment"])){
			$env->values = unserialize($_COOKIE["CPEnvironment"]);
			$env->valuesForRead = $env->values;
		}
		return $env;
	}
	
	/**
	 * Return agent of current session
	 */
	public function getAgent(){
		return "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:12.0) Gecko/20100101 Firefox/12.0";
	}
	
	/**
	 * Return location of current session.
	 * 
	 * If it needs to get location of current session, it tries to get.
	 * This method would cost.
	 */
	public function getLocation(){
		if(isset($_COOKIE['CPEnvLocation'])){
			return unserialize($_COOKIE['CPEnvLocation']);
		}else{
			$value = array('Country' => 'JAPAN', 'Region' => 'SHIGA', 'City' => 'OTSU');
			setcookie('CPEnvLocation', serialize($value));
			return $value;
		}
		
		// Change to use actual location data
		/*
		 require_once SSCP_PATH.'/thirdparty/ip2locationlite.class.php';
		
		//Load the class
		$ipLite = new ip2location_lite;
		$ipLite->setKey('APIKEY');
		
		//Get errors and locations
		$locations = $ipLite->getCity("182.166.41.166");
		$errors = $ipLite->getError();
		*/
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
		setcookie("CPEnvironment", serialize($this->valuesForRead));
	}
}