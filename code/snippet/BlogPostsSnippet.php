<?php
/**
 * Show blog posts based on meta tag
 * 
 * You need to install blog module for using this snippet.
 * @author yuki
 *
 */
class BlogPostsSnippet extends SnippetBase {
	
	const TagForEveryone = 'Everyone';
	
	static $db = array(
		'VisibleTag' => 'Varchar'
	);
	
	static $has_one = array(
		'BlogHolder' => 'BlogHolder'
	);
	
	/**
	 * Snippet name is shown in administration panel
	 */
	public static $snippet_name = 'Blog Posts';
	
	public function getContent() {
		$blogHolder = $this->BlogHolder();
		$entries = $blogHolder->Entries();
		$posts = new ArrayList();
		foreach($entries as $entry) {
			foreach($entry->TagsCollection() as $tag) {
				if($tag->Tag === $this->VisibleTag || $tag->Tag === self::TagForEveryone) {
					$posts->push($entry);
				}
			}
		}
		$template = new SSViewer('BlogPostsSnippet');
		return $template->process($this, array('Posts' => $posts));
	}
	
}