<?php
abstract class ConditionBase{
	abstract public function doesSatisfy(CPEnvironment $env, $args);
}