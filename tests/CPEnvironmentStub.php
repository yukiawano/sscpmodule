<?php
class CPEnvironmentStub extends CPEnvironment{
	
	/**
	 * Clear cookies related to CPEnvironment
	 */
	public static function clearCookie() {
		setcookie(self::CPEnvKey,"", time() - 3600); // Delete cookie before running the tests.
		unset($_COOKIE[self::CPEnvKey]);
		setcookie(self::CPEnvLocationKey,"", time() - 3600);
		unset($_COOKIE[self::CPEnvLocationKey]);
	}
	
	/**
	 * Return stub CPEnvironment (This actually writes to cookie)
	 * @return CPEnvironment
	 */
	public static function getCPEnvironment($audienceTypes = null){
		$env = new CPEnvironmentStub();
		if(Cookie::get(self::CPEnvKey) != null){
			$env->values = json_decode(Cookie::get(self::CPEnvKey), true);
			$env->valuesForRead = $env->values;
		}
		$env->audienceTypes = $audienceTypes;
		return $env;
	}
	
	public function getAgent(){
		return "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:12.0) Gecko/20100101 Firefox/12.0";
	}
	
	public function getPlatform() {
		return 'Linux';
	}
	
	public function getBrowser() {
		return 'Firefox';
	}
	
	public function getLocation(){
		return array('lat' => 35.1061038125824,
				       'lon' => 135.727367242386,
				       'Country' => 'Japan',
					   'Region' => 'Kinki',
					   'City' => 'Kyoto',
					   'Source' => 'IPInfoDB');
	}
}