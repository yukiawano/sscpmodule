<?php
/**
 * Base class for condition
 * @package sscp
 */
abstract class ConditionBase extends Object{
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
}