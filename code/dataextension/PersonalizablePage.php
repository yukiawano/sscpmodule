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
		
		$blockHolder = BlockHolder::get()->filter(array('TemplateKey' => $templateKey))->First();
		if($blockHolder == null) {
			return "BlockHolder of {$templateKey} is not found.";
		} else {
			$showDebugToolbar = Permission::check(BlockHolderMain::ADMIN_PERSONALIZATION);
			return $this->renderPersonalizedContent($this->getPersonalizedContent($blockHolder), $showDebugToolbar);
		}
	}
	
	/**
	 * Get personalzied content
	 * 
	 * @param BlockHolder $blockHolder
	 * @return String of content
	 */
	private function getPersonalizedContent(BlockHolder $blockHolder) {
		$audienceTypeLoader = new AudienceTypeLoader();
		$audienceTypeManager = new AudienceTypeManager();
		
		$env = CPEnvironment::getCPEnvironment();
		$audienceTypes = $env->getAudienceTypes();
		$currentAudienceTypes = $audienceTypeManager->getAudienceTypes($audienceTypes, $env, $blockHolder->getRelatedAudienceTypes());
		
		// Get blocks of this block holder
		$blocks = $blockHolder->Blocks();
		foreach($blocks as $block) {
			if(in_array($block->AudienceType, $currentAudienceTypes)) {
				return array(
							'Content' => $block->SnippetBase()->getContent(),
							'DebugInfo' => array(
								'AppliedAudienceType' => $block->AudienceType,
								'ConsideredAudienceTypes' => $blockHolder->getRelatedAudienceTypes(),
								'RenderedSnippetName' => $block->SnippetBase()->Title,
								'BlockHolderName' => $blockHolder->Title)
						);
			}
		}
		
		// When there is no block that correspond to current session.
		return array(	'Content' => ($blockHolder->ShowDefaultSnippet ? $blockHolder->DefaultSnippet()->getContent() : ''),
						'DebugInfo' => array(
							'AppliedAudienceType' => null,
							'ConsideredAudienceTypes' => $blockHolder->getRelatedAudienceTypes(),
							'RenderedSnippetName' => ($blockHolder->ShowDefaultSnippet ? $blockHolder->DefaultSnippet()->Title : 'Nothing'),
							'BlockHolderName' => $blockHolder->Title)
		);
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
	private function renderPersonalizedContent($context, $renderDebugInfo) {
		if($renderDebugInfo) {
			return $context['Content'] . $this->renderDebugInfo($context['DebugInfo']);
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
	private function renderDebugInfo($debugInfo) {
		Requirements::css('sscp/css/DebugInfo.css');
		
		$ssViewer = new SSViewer('DebugInfo');
		
		$consideredAudienceTypes = new ArrayList();
		foreach($debugInfo['ConsideredAudienceTypes'] as $audienceType) {
			$consideredAudienceTypes->add(new DataObject(array('Name' => $audienceType)));
		}
		
		return $ssViewer->process(new DataObject(array(
					'AppliedAudienceType' 		=> (null === $debugInfo['AppliedAudienceType'] ? 'No AudienceType has matched.' : $debugInfo['AppliedAudienceType']),
					'ConsideredAudienceTypes' 	=> $consideredAudienceTypes,
					'RenderedSnippetName' 		=> $debugInfo['RenderedSnippetName'],
					'BlockHolderName'			=> $debugInfo['BlockHolderName']
				)));
	}
}