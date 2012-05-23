<?php
require_once 'ConditionBase.php';

class NewComer extends ConditionBase {
	public function doesSatisfy(CPEnvironment $env, $args){
		$hasVisited = $env->get("HasVisited", false);
		$isNewComer = ($args == "true" ? true : false);
		$env->set("HasVisited", true);
		
		return $isNewComer == (!$hasVisited);
	}
}