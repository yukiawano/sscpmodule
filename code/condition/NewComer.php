<?php
require_once 'ConditionBase.php';

class NewComer extends ConditionBase {
	public function doesSatisfy($env, $args){
		return true;
	}
}