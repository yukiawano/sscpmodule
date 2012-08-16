<?php
class GridFieldAddNewBlockButton implements GridField_HTMLProvider {
	
	protected $targetFragment;
	protected $id;
	
	public function __construct($id, $targetFragment = 'before') {
		$this->targetFragment = $targetFragment;
		$this->id = $id;
	}
	
	public function getHTMLFragments($gridField) {
		$newLink = Controller::join_links(
				$gridField->stat('url_base', true),
				"admin/personalization/sscpblock/addForm/?block_holder_id={$this->id}"
		);
	
		$data = new ArrayData(array(
				'NewLink' => $newLink,
				'ButtonName' => 'Add Block',
		));
	
		return array(
				$this->targetFragment => $data->renderWith('GridFieldAddNewbutton'),
		);
	}
	
}