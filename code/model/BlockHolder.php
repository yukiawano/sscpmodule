<?php
class BlockHolder extends DataObject {
	static $db = array(
			'Title' => 'Varchar',
			'TemplateKey' => 'Varchar',
			'Description' => 'Text',
			'ShowDefaultSnippet' => 'Boolean'
			);
	
	static $defaults = array(
			'ShowDefaultSnippet' => false
	);
	
	static $summary_fields = array(
			'Title',
			'TemplateKey',
			'Description'
			);
	
	static $has_many = array(
			'Blocks'=>'SSCP_Block'
	);
	
	static $has_one = array(
			'DefaultSnippet' => 'SnippetBase'
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeFieldFromTab('Root', 'Blocks');
		
		$gridFieldConfig = GridFieldConfig_RecordEditor::create();
    	$gridFieldConfig->addComponent(new GridFieldAddNewBlockButton($this->ID, 'buttons-before-left'));
    	$gridFieldConfig->removeComponentsByType('GridFieldAddNewButton');
		$gridFields = new GridField('SSCP_Block', null, SSCP_Block::get()->filter(array('BlockHolderID' => $this->ID)), $gridFieldConfig);
		$fields->push($gridFields);
		
		return $fields;
	}
	
	/**
	 * Return audience types which is related to this BlockHolder.
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
}