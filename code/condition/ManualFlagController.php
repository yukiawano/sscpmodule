<?php
class ManualFlagController extends Controller {
	
	const ManualFlagPrefix = 'MF';
	
	/**
	 * Mark a flag, and redirect to the designated url.
	 * 
	 * Format of URL is /manualFlag/flag/$FlagName?BackURL=$UrlEncodedRedirectURL
	 * 
	 * @param SS_HTTPRequest $request
	 * @example
	 * When user want to mark "AccessedToGithubPage" flag,
	 * and want to redirect visitors to http://github.com/foobar/hogehoge,
	 * visitors should access to the below url.
	 * 
	 * /manualFlag/flag/AccessedToGithubPage?BackURL=http%3A%2F%2Fgithub.com%2Ffoobar%2Fhogehoge
	 */
	public function flag(SS_HTTPRequest $request) {
		$flagName = $request->param('FlagName');
		$redirectUrl = $request->getVar('BackURL');
		
		if($flagName == NULL) {
			user_error("Flag name is not set.", E_USER_ERROR);
		}
		
		if($redirectUrl == NULL) {
			user_error("BackURL(Redirect Url) is not set.", E_USER_ERROR);
		}
		
		$CPEnv = CPEnvironment::getCPEnvironment();
		$CPEnv->set("{self::ManualFlagPrefix}_{$flagName}", true);
		$CPEnv->commit();
		
		$this->redirect($redirectUrl, 303);
	}
	
}