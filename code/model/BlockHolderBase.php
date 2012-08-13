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
	 * Only the returned audience types are considered in matching process.
	 * 
	 * You should overwrite this method in subclass.
	 * 
	 * @return Array of related audience types
	 * e.g. array('TypeA', 'TypeB');
	 */
	public function getRelatedAudienceTypes() {
		return null;
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