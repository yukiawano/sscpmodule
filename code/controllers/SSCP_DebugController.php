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
		
		$this->response->setStatusCode(200);
	}
	
	/**
	 * Clear location
	 */
	public function clearLocation(SS_HTTPRequest $request) {
		$env = CPEnvironment::getCPEnvironment();
		$env->resetToDefaultLocation();
		
		$this->response->setStatusCode(200);
	}
}