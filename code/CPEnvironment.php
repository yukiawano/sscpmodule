<?php
class CPEnvironment {
	private $values = array();
	
	public static function getCPEnvironment(){
		return new CPEnvironment();
	}
	
	public function get($key){
		if(isset($values[$key])){
			return $values[$key];
		}else{
			return null;
		}
	}
	
	public function set($key, $value){
		$values[$key] = $value;
	}
}