<?php
class PersonalizablePage extends DataExtension {
	
	/**
	 * Show debug toolbar when a user is logged in
	 */
	public function DebugToolbar() {
		$env = CPEnvironment::getCPEnvironment();
		$location = $env->getLocation();
		
		$getValue = function(& $value) {
			if(isset($value)) {
				return $value;
			} else {
				return null;
			}
		};
		
		$locationString = $getValue($location['Country']) . ' '
		. $getValue($location['Region']) . ' '
		. $getValue($location['City']) . ' '
		. $getValue($location['County']) . ' '
		. $getValue($location['Road']) . ' '
		. $getValue($location['PublicBuilding']) . ' '
		. $getValue($location['Postcode']);
		
		$template = new SSViewer('DebugToolbar');
		return $template->process($this, array(
				'Latitude' => $location['lat'],
				'Longitude' => $location['lon'],
				'LocationString' => $locationString,
				'LocationSource' => $location['Source'],
				'Platform' => $env->getPlatform(),
				'Browser' => $env->getBrowser(),
				'UserAgent' => $_SERVER['HTTP_USER_AGENT'],
				'RemoteAddr' => $_SERVER['REMOTE_ADDR']));
	}
	
	/**
	 * Get personalized content for specified templateKey
	 * @param string $templateKey
	 */
	public function PersonalizedContent(string $templateKey) {
		// TODO We need consider more, where should I put these requirements codes.
		$ipInfoDbAPIKey = Config::inst()->get("APIKey", "IPInfoDB");
		Requirements::javascriptTemplate('sscp/code/condition/javascript/vars.js', array('ipInfoDbAPIKey' => $ipInfoDbAPIKey));
		
		$blockHolder = BlockHolder::get()->filter(array('TemplateKey' => $templateKey))->First();
		if($blockHolder == null) {
			return "BlockHolder of {$templateKey} is not found.";
		}
		
		$audienceTypeLoader = new AudienceTypeLoader();
		$audienceTypeManager = new AudienceTypeManager();
		
		$audienceTypes = $audienceTypeLoader->load();
		$env = CPEnvironment::getCPEnvironment();
		$currentAudienceTypes = $audienceTypeManager->getAudienceTypes($audienceTypes, $env, $blockHolder->getRelatedAudienceTypes());
		
		// Get blocks of this block holder
		$blocks = $blockHolder->Blocks();
		foreach($blocks as $block) {
			if(in_array($block->AudienceType, $currentAudienceTypes)) {
				return $block->SnippetBase()->getContent();
			}
		}
		
		// When there is no block that correspond to current session
		if($blockHolder->ShowDefaultSnippet) {
			return $blockHolder->DefaultSnippet()->getContent();
		} else {
			return '';
		}
	}
}