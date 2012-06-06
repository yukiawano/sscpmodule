<?php
class Block extends DataObject {
	static $db = array(
			'Title' => 'Varchar',
			'AudienceType' => 'Varchar'
		);
	
	static $has_one = array(
			'BlockHolder' => 'BlockHolder',
			'Snippet' => 'Snippet'
	);
}