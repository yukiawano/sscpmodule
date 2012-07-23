<?php
class DebugController extends Controller {
	
	/**
	 * Change Location
	 * @param SS_HTTPRequest $request
	 */
	public function ChangeLocation(SS_HTTPRequest $request) {
		$lat = $request->getVar('Lat');
		$lon = $request->getVar('Lon');
		
		
	}
}