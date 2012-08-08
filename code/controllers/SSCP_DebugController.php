<?php
class SSCP_DebugController extends Controller {
	
	/**
	 * Change Location
	 * @param SS_HTTPRequest $request
	 */
	public function changelocation(SS_HTTPRequest $request) {
		if(Permission::check(BlockHolderMain::ADMIN_PERSONALIZATION)) {
			$lat = $request->getVar('lat');
			$lon = $request->getVar('lon');
			
			$env = CPEnvironment::getCPEnvironment();
			$value = $env->setLocationManually($lat, $lon);
			
			$this->response->setStatusCode(200);
		} else {
			Security::permissionFailure();
		}
	}
	
	/**
	 * Clear location
	 */
	public function clearLocation(SS_HTTPRequest $request) {
		if(Permission::check(BlockHolderMain::ADMIN_PERSONALIZATION)) {
			$env = CPEnvironment::getCPEnvironment();
			$env->resetToDefaultLocation();
		
			$this->response->setStatusCode(200);
		} else {
			Security::permissionFailure();
		}
	}
}