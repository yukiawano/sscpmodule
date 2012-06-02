<?php
class BlockHolder extends DataObject {
	static $db = array(
			'Name' => 'Varchar',
			'TemplateKey' => 'Varchar',
			'Description' => 'Text'
			);
	
	static $summary_fields = array(
			'Name',
			'TemplateKey',
			'Description'
			);
	
	
	static $has_many = array(
			'Blocks'=>'Block'
	);
	
	public function getCMSFields(){
		$fields = parent::getCMSFields();
		
		$config = GridFieldConfig_RelationEditor::create();
		$gridFields = new GridField('Blocks', 'Blocks that is shown in this BlockHolder.', $this->Blocks(), $config);
		$fields->push($gridFields);
		return $fields;
	}
}