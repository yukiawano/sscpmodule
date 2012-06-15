<?php

class GridFieldAddNewSnippetButton implements GridField_HTMLProvider {

	protected $targetFragment;

	public function __construct($targetFragment = 'before') {
		$this->targetFragment = $targetFragment;
	}

	public function getHTMLFragments($gridField) {
		$newLink = Controller::join_links(
				$gridField->stat('url_base', true),
				'admin/personalization/add/'
		);
		
		$data = new ArrayData(array(
				'NewLink' => $newLink,
				'ButtonName' => 'Add Snippet',
		));

		return array(
				$this->targetFragment => $data->renderWith('GridFieldAddNewbutton'),
		);
	}

}
