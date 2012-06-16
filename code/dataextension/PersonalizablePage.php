<?php
class PersonalizablePage extends DataExtension {
	
	/**
	 * Get personalized content for specified templateKey
	 * @param string $templateKey
	 */
	public function PersonalizedContent(string $templateKey) {
		$blockHolder = BlockHolder::get()->filter(array('TemplateKey' => $templateKey))->First();
		if($blockHolder == null) {
			return "BlockHolder of {$templateKey} is not found.";
		}
		
		$audienceTypeLoader = new AudienceTypeLoader();
		$audienceTypeManager = new AudienceTypeManager();
		
		$audienceTypes = $audienceTypeLoader->load();
		$env = CPEnvironment::getCPEnvironment();
		$currentAudienceTypes = $audienceTypeManager->getAudienceTypes($audienceTypes, $env);
		
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