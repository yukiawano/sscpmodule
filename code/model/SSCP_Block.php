<?php
class SSCP_Block extends DataObject {
	static $db = array(
			'Title' => 'Varchar',
			'AudienceType' => 'Varchar'
		);
	
	static $has_one = array(
			'BlockHolder' => 'BlockHolder',
			'SnippetBase' => 'SnippetBase'
	);
}