<?php
require_once 'ConditionBase.php';

class OS extends ConditionBase{
	public function doesSatisfy($env, $args){
		return true;
	}
}