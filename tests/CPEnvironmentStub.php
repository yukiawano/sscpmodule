<?php
class CPEnvironmentStub extends CPEnvironment{
	
	/**
	 * Return stub CPEnvironment (This actually writes to cookie)
	 * @return CPEnvironment
	 */
	public static function getCPEnvironment(){
		$env = new CPEnvironmentStub();
		if(Cookie::get("CPEnvironment") != null){
			$env->values = unserialize(Cookie::get("CPEnvironment"));
			$env->valuesForRead = $env->values;
		}
		return $env;
	}
	
	public function getAgent(){
		return "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:12.0) Gecko/20100101 Firefox/12.0";
	}
	
	public function getLocation(){
		return array('Country' => 'JAPAN', 'Region' => 'SHIGA', 'CITY' => 'Otsu');
	}
}