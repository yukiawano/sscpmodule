<?php
class ImageSnippet extends SnippetBase {
	
	public static $snippet_name = 'Image Snippet';
	
	static $db = array(
			'ImageUrl' => 'Varchar'
	);
	
	public function getContent() {
		return "<img src='{$this->ImageUrl}' />";
	}
	
}