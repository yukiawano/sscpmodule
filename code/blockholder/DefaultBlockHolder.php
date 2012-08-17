<?php
/**
 * DefaultBlockHolder is the most common block holder.
 * This can hold multiple blocks in it.
 */
class DefaultBlockHolder extends BlockHolderBase {
	
	static $db = array(
			'ShowDefaultSnippet' => 'Boolean',
			'SlideshowMode' => 'Boolean'
	);
	
	static $defaults = array(
			'ShowDefaultSnippet' => false,
			'SlideshowMode' => false
	);
	
	static $has_many = array(
			'Blocks'=>'SSCP_Block'
	);
	
	static $has_one = array(
			'DefaultSnippet' => 'SnippetBase'
	);
	
	/*
	 * If the slideshow mode is true, it will show multiple blocks at a time.
	 * */
	
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
	 * Return related SSCP_Blocks for the environment.
	 * @param CPEnvironment $env
	 * @return array of Blocks
	 */
	public function getBlocks(CPEnvironment $env) {
		$audienceTypeManager = new AudienceTypeManager();
		$currentAudienceTypes = $audienceTypeManager->getAudienceTypes($env, $this->getRelatedAudienceTypes());
		
		$relatedBlocks = array();
		$blocks = $this->Blocks();
		foreach($blocks as $block) {
			$intersect = array_intersect($block->getAudienceTypes(), $currentAudienceTypes);
			if(count($intersect) != 0) {
				array_push($relatedBlocks, $block);
			}
		}
		
		return $relatedBlocks;
	}
	
	/**
	 * Return content for specified environment
	 */
	public function getContent(CPEnvironment $env) {
		$blocks = $this->getBlocks($env);
		
		if($this->ShowDefaultSnippet) {
			array_push($blocks, $this->DefaultSnippet());
		}
		
		if(count($blocks) == 0) {
			return array('Content' => '', 'DebugInfo' => array('Notice' => 'No block was matched.'));
		}
		
		if($this->SlideshowMode) {
			// Combine them
			$blockTitles = array();
			$blockArray = new ArrayList();
			foreach($blocks as $block) {
				$blockArray->push(new ArrayData(array('Body' => $block->getContent())));
				array_push($blockTitles, $block->Title);
			}
			
			Requirements::javascript(SSCP_DIR . '/javascript/slides.jquery.js');
			Requirements::javascript(SSCP_DIR . '/javascript/slideshow.js');
			
			$template = new SSViewer('Slideshow');
			return array(
						'Content' => $template->process($this, array('Blocks' => $blockArray)),
						'DebugInfo' => array(
								'SlideshowMode' => 'true',
								'RenderedBlocks' => join(', ', $blockTitles)
								)
					);
		} else {
			$block = $blocks[0];
			return array(
						'Content' => $block->getContent(),
						'DebugInfo' => array(
								'SlideshowMode' => 'false',
								'RenderedBlock' => $block->Title
								)
					);
		}
		
		/* 
		 * BlockHolderName,
		 * DebugInfo - Debug info should be key value list.
		 *  */
		
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