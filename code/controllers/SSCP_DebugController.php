<?php
class SSCP_DebugController extends Controller implements PermissionProvider {
	
	const SSCP_DEBUG_PERMISSION_KEY = "SSCPDebug"; 
	
	function providePermissions() {
		return array(SSCP_DebugController::SSCP_DEBUG_PERMISSION_KEY => "Debug Personalized Content");
	}
	
	/**
	 * Change Location
	 * @param SS_HTTPRequest $request
	 */
	public function changelocation(SS_HTTPRequest $request) {
		if(Permission::check(SSCP_DebugController::SSCP_DEBUG_PERMISSION_KEY)) {
			$lat = $request->getVar('lat');
			$lon = $request->getVar('lon');
			
			$env = CPEnvironment::getCPEnvironment();
			$value = $env->setLocationManually($lat, $lon);
			
			$this->response->setStatusCode(200);
		} else {
			$this->response->setStatusCode(400);
		}
	}
	
	/**
	 * Clear location
	 */
	public function clearLocation(SS_HTTPRequest $request) {
		if(Permission::check(SSCP_DebugController::SSCP_DEBUG_PERMISSION_KEY)) {
			$env = CPEnvironment::getCPEnvironment();
			$env->resetToDefaultLocation();
		
			$this->response->setStatusCode(200);
		} else {
			$this->response->setStatusCode(400);	
		}
	}
}