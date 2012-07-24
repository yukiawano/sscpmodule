<?php
class SSCP_DebugController extends Controller {
	
	/**
	 * Change Location
	 * @param SS_HTTPRequest $request
	 */
	public function changelocation(SS_HTTPRequest $request) {
		$lat = $request->getVar('lat');
		$lon = $request->getVar('lon');
		
		$env = CPEnvironment::getCPEnvironment();
		$value = $env->setLocationManually($lat, $lon);
		
		var_dump($value);
		print_r("OK: Changed with ({$lat},{$lon})");
	}
}