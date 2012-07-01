<?php

define('SSCP_DIR', 'sscp');
define('SSCP_PATH', BASE_PATH . '/' . SSCP_DIR);

Object::add_extension('Page', 'PersonalizablePage');

// Log accesse
$browsingActivity = new BrowsingActivity();
$env = CPEnvironment::getCPEnvironment();
$browsingActivity->logAccesse($env, $_SERVER["REQUEST_URI"]);
$env->commit();

// Add URL Rules
Director::addRules(50, array('manualFlag/$Action/$FlagName' => 'ManualFlagController'));