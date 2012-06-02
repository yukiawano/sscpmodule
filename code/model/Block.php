<?php
class Block extends DataObject {
	static $db = array(
			'Title' => 'Varchar'
		);
	
	static $has_one = array(
			"BlockHolder" => "BlockHolder"
	);
}