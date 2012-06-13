<?php
/**
 * Snippet that holds fragment of HTML code.
 * @author yuki
 *
 */
class HTMLSnippet extends SnippetBase {
	
	static $db = array(
			'HTML' => 'Text'
	);
	
	public function getContent() {
		return $this->HTML;	
	}
}