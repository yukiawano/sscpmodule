<?php
require_once 'ConditionBase.php';

class Location extends ConditionBase{
	function doesSatisfy($env, $args){
		return true;
	}
}