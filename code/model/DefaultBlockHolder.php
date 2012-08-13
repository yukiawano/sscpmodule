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
			if(!in_array($block->AudienceType, $relatedAudienceTypes)) {
				array_push($relatedAudienceTypes, $block->AudienceType);
			}
		}
		return $relatedAudienceTypes;
	}
	
	/**
	 * Return content for specified environment
	 */
	public function getContent(CPEnvironment $env) {
		$audienceTypeManager = new AudienceTypeManager();
		$audienceTypes = $env->getAudienceTypes();
		$currentAudienceTypes = $audienceTypeManager->getAudienceTypes($audienceTypes, $env, $this->getRelatedAudienceTypes());
	
		// Get blocks of this block holder
		$blocks = $this->Blocks();
		foreach($blocks as $block) {
			if(in_array($block->AudienceType, $currentAudienceTypes)) {
				return array(
						'Content' => $block->SnippetBase()->getContent(),
						'DebugInfo' => array(
								'AppliedAudienceType' => $block->AudienceType,
								'ConsideredAudienceTypes' => $this->getRelatedAudienceTypes(),
								'RenderedSnippetName' => $block->SnippetBase()->Title,
								'BlockHolderName' => $this->Title)
				);
			}
		}
	
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