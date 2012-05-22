<?php
abstract class ConditionBase{
	abstract public function doesSatisfy($env, $args);
}