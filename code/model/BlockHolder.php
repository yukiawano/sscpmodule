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
}