<?php

class GridFieldAddNewBlockHolderButton implements GridField_HTMLProvider {
	
	protected $targetFragment;
	
	public function __construct($targetFragment = 'before') {
		$this->targetFragment = $targetFragment;
	}
	
	public function getHTMLFragments($gridField) {
		$newLink = Controller::join_links(
				$gridField->stat('url_base', true),
				'admin/personalization/AddBlockHolderForm/'
		);
	
		$data = new ArrayData(array(
				'NewLink' => $newLink,
				'ButtonName' => 'Add Block Holder',
		));
	
		return array(
				$this->targetFragment => $data->renderWith('GridFieldAddNewbutton'),
		);
	}
	
	
}