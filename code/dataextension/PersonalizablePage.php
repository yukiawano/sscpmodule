<?php
class PersonalizablePage extends DataExtension {
	
	public function PersonalizedContent($templateKey) {
		$blockHolder = BlockHolder::get()->filter(array('TemplateKey' => $templateKey))->First();
		
		$audienceTypeLoader = new AudienceTypeLoader();
		$audienceTypeManager = new AudienceTypeManager();
		
		$audienceTypes = $audienceTypeLoader->load();
		$env = CPEnvironment::getCPEnvironment();
		$currentAudienceTypes = $audienceTypeManager->getAudienceTypes($audienceTypes, $env);
		
		/* Get blocks of this block holder */
		$blocks = $blockHolder->Blocks();
		foreach($blocks as $block) {
			if(in_array($block->AudienceType, $currentAudienceTypes)) {
				return $block->Snippet()->Snippet;
			}
		}
		
		return '<strong>NO Block had found.</strong>';	
	}
}