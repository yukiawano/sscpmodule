<?php
/**
 * DefaultBlockHolder is the most common block holder.
 * This can hold multiple blocks in it.
 */
class DefaultBlockHolder extends BlockHolderBase {
	
	static $db = array(
			'ShowDefaultSnippet' => 'Boolean'
	);
	
	static $defaults = array(
			'ShowDefaultSnippet' => false
	);
	
	static $has_many = array(
			'Blocks'=>'SSCP_Block'
	);
	
	static $has_one = array(
			'DefaultSnippet' => 'SnippetBase'
	);
	
	
	public static $blockholder_name = 'Block Holder';
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeFieldFromTab('Root', 'Blocks');
	
		if($this->ID != 0) {
			$gridFieldConfig = GridFieldConfig_RecordEditor::create();
			$gridFieldConfig->addComponent(new GridFieldAddNewBlockButton($this->ID, 'buttons-before-left'));
			$gridFieldConfig->removeComponentsByType('GridFieldAddNewButton');
			$gridFields = new GridField('SSCP_Block', 'Block' , SSCP_Block::get()->filter(array('BlockHolderID' => $this->ID)), $gridFieldConfig);
			$gridFields->setAttribute('style', 'margin:15px;');
			$fields->push($gridFields);
		}
	
		return $fields;
	}
	
	
	/**
	 * Return audience types which is related to this BlockHolder.
	 * 
	 * @example
	 * There are AudienceType A, B, C, D, E and
	 * Block Holder A holds
	 *   BlockA : AudienceTypeA
	 *   BlockB : AudienceTypeC
	 *   BlockC : AudienceTypeE
	 *
	 * Then getRelatedAudienceTypes() of BlockHolder A returns
	 *   AudienceTypeA, AudienceTypeC, AudienceTypeE
	 */
	public function getRelatedAudienceTypes() {
		$blocks = $this->Blocks();
		$relatedAudienceTypes = array();
		foreach($blocks as $block) {
			$relatedAudienceTypes = array_merge($relatedAudienceTypes, $block->getAudienceTypes());
		}
		return array_merge(array_unique($relatedAudienceTypes)); // Use array merge for making the index starting from 0. (array_unique makes index starting from non-zero value)
	}
	
	/**
	 * Get SSCP_Block for the environment.
	 * @param CPEnvironment $env
	 */
	public function getBlock(CPEnvironment $env) {
		$audienceTypeManager = new AudienceTypeManager();
		$audienceTypes = $env->getAudienceTypes();
		$currentAudienceTypes = $audienceTypeManager->getAudienceTypes($env, $this->getRelatedAudienceTypes());
		
		// Get blocks of this block holder
		$blocks = $this->Blocks();
		foreach($blocks as $block) {
			$intersect = array_intersect($block->getAudienceTypes(),$currentAudienceTypes);
			if(count($intersect) != 0) {
				return $block;
			}
		}
		
		return null;
	}
	
	/**
	 * Return content for specified environment
	 */
	public function getContent(CPEnvironment $env) {
		if(($block = $this->getBlock($env))) {
			return array(
					'Content' => $block->SnippetBase()->getContent(),
					'DebugInfo' => array(
							'AppliedAudienceType' => $block->AudienceType,
							'ConsideredAudienceTypes' => $this->getRelatedAudienceTypes(),
							'RenderedSnippetName' => $block->SnippetBase()->Title,
							'BlockHolderName' => $this->Title)
			);
		} else {
			// When there is no block that correspond to current session.
			return array(	'Content' => ($this->ShowDefaultSnippet ? $this->DefaultSnippet()->getContent() : ''),
					'DebugInfo' => array(
							'AppliedAudienceType' => null,
							'ConsideredAudienceTypes' => $this->getRelatedAudienceTypes(),
							'RenderedSnippetName' => ($this->ShowDefaultSnippet ? $this->DefaultSnippet()->Title : 'Nothing'),
							'BlockHolderName' => $this->Title)
			);
		}
	}
	
}