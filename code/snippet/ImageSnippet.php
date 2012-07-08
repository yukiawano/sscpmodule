<?php
class ImageSnippet extends SnippetBase {
	
	public static $snippet_name = 'Image Snippet';
	
	static $has_one = array(
		'BannerImage' => 'Image'
	);
	
	public function getContent() {
		return "<img src='{$this->BannerImage()->getURL()}' />";
	}
	
}