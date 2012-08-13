<?php

/**
 * BlockHolderBase is a base calss of BlockHolder.
 * You can create customized BlockHolder by extending this class.
 * 
 * This class provieds Title, Description, and TemplateKey fields, 
 * and some utility methods for BlockHolder.
 */
class BlockHolderBase extends DataObject {
	
	static $db = array(
			'Title' => 'Varchar',
			'TemplateKey' => 'Varchar',
			'Description' => 'Text',
	);
	
	public static $blockholder_name = 'BlockHolderBase';
	
	/**
	 * Return a subclasses of BlockHolderBase as an array
	 * 
	 * @return An array that contains className and blockHolderName.
	 * array(
	 * 		'ClassName' => 'Block Holder Name',
	 * 		'DefaultBlockHolder' => 'Block Holder'
	 * );
	 */
	public static function getBlockHolderTypes() {
		$classes = ClassInfo::subclassesFor('BlockHolderBase');
		
		$subClasses = array();
		foreach($classes as $class) {
			if($class == 'BlockHolderBase') continue;
			$subClasses[$class] = $class::$blockholder_name;
		}
		
		return $subClasses;
	}
	
	/**
	 * Return audience types which is related to this BlockHolder.
	 * 
	 * TODO Implementation of this method is specific to DefaultBlockHolder.
	 * We need to move this method to DefaultBlockHolder and write here just an interface of this.
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
	 * Return content of this BlockHolder for the environment.
	 * Overwrite this method, and return content of the environment.
	 * 
	 * @param CPEnvironment $env
	 */
	public function getContent(CPEnvironment $env) {
		return 'Return content of this block holder here.';
	}
}