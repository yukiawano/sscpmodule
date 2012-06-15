<?php
/**
 * Snippet that holds fragment of HTML code.
 * @author yuki
 *
 */
class HTMLSnippet extends SnippetBase {
	
	public static $snippet_name = 'HTML Snippet';
	
	static $db = array(
			'HTML' => 'Text'
	);
	
	public function getContent() {
		return $this->HTML;	
	}
}