<?php
/**
 * Base class for condition
 * @package sscp
 */
abstract class ConditionBase extends Object{
	
	/**
	 * Javascript file path, which is loaded for this condition.
	 * Set null, when this condition does not require any javascript file.
	 * 
	 * @var string javascript file path
	 * @example $javascript_file = 'sscp/code/condition/javascript/location.js'
	 */
	var $javascript_file;
	
	/**
	 * Evaluate the condition is satisfied under the environment.
	 * This method is assumed as stateless.
	 * It is strongly recommended NOT to have any state.
	 * This instance may be reused or stub environment may be passed.
	 * 
	 * @param CPEnvironment $env
	 * @param String $args
	 */
	abstract public function doesSatisfy(CPEnvironment $env, $args);
	
	function __construct() {
		parent::__construct();
		
		// Basis for condition
		Requirements::javascript('http://code.jquery.com/jquery-1.7.2.js');
		Requirements::javascript(FRAMEWORK_DIR.'/thirdparty/jquery-cookie/jquery.cookie.js');
		Requirements::javascript(SSCP_DIR.'/code/condition/javascript/conditionBase.js');
		
		if($this->javascript_file != null){
			Requirements::javascript($this->javascript_file);
		}
	}
	
}