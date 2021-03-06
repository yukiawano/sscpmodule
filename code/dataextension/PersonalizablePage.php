<?php
class PersonalizablePage extends DataExtension {
	
	/**
	 * Show debug toolbar when a user is logged in
	 */
	public function DebugToolbar() {
		if(Permission::check(BlockHolderMain::ADMIN_PERSONALIZATION)) {
			Requirements::javascript('sscp/javascript/DebugToolbar.js');
			
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
		} else {
			return '';
		}
	}
	
	/**
	 * Get personalized content for specified templateKey
	 * @param string $templateKey
	 */
	public function PersonalizedContent(string $templateKey) {
		$env = CPEnvironment::getCPEnvironment();
		
		Requirements::javascriptTemplate('sscp/code/condition/javascript/vars.js', array('ipInfoDbAPIKey' => $env->getIpInfoDbAPIKey()));
		
		$blockHolder = BlockHolderBase::get()->filter(array('TemplateKey' => $templateKey))->First();
		if($blockHolder == null) {
			return "BlockHolder of {$templateKey} is not found.";
		} else {
			$showDebugToolbar = Permission::check(BlockHolderMain::ADMIN_PERSONALIZATION);
			return $this->renderPersonalizedContent($blockHolder->getContent($env), $showDebugToolbar, $templateKey);
		}
	}
	
	/**
	 * Render content
	 * 
	 * @param array $context
	 * @param bool $renderDebugInfo
	 * @example
	 * Example of context array is below.
	 * 
	 * array(
	 * 	'Content' 	=> "String of Content",
	 * 	'DebugInfo' => array( For more details, check phpdoc of renderDebugInfo. )
	 * )
	 */
	private function renderPersonalizedContent($context, $renderDebugInfo, $templateKey) {
		if($renderDebugInfo) {
			return $context['Content'] . $this->renderDebugInfo($context['DebugInfo'], $templateKey);
		} else {
			return $context['Content'];
		}
	}
	
	/**
	 * Render debug info
	 * 
	 * @param array $debugInfo
	 * @return string of rendered DebugInfo into HTML
	 * @example
	 * Example of debugInfo array is below.
	 * 
	 * array(	'AppliedAudienceType'		=> 'Test',
	 * 			'ConsideredAudienceTypes'	=> array('Foo', 'Bar', 'Test'),
	 * 			'RenderedSnippetName'		=> 'TestSnippet',
	 * 			'BlockHolderName'			=> 'BannerBlockHolder' );
	 */
	private function renderDebugInfo($debugInfo, $templateKey) {
		Requirements::css('sscp/css/DebugInfo.css');
		
		$valueList = new ArrayList();
		foreach($debugInfo as $key => $value) {
			$valueList->push(new ArrayData(array(
						'Key' => $key,
						'Value' => $value
					)));	
		}
		
		$ssViewer = new SSViewer('DebugInfo');
		return $ssViewer->process(new DataObject(array(
					'TemplateKey' => $templateKey,
					'ValueList' => $valueList
				)));
	}
}