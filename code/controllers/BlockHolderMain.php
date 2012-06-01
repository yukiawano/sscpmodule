<?php
/**
 * @package sscp
 */
// class BlockHolderMain extends CMSSettingsController {
class BlockHolderMain extends LeftAndMain {
	
	static $url_segment = 'personalization';
	static $url_rule = '/$Action/$ID';
	static $menu_title = 'Personalization';
	static $menu_priority = -1;
	
	public function init(){
		parent::init();
		Requirements::javascript(CMS_DIR . '/javascript/CMSMain.GridField.js');
		Requirements::css(CMS_DIR . '/css/screen.css');
	}
	
	function getEditForm($id = null, $fields = null) {
    	$fields = new FieldList();
    	$config = GridFieldConfig::create()->addComponents(
				new GridFieldToolbarHeader(),
				new GridFieldSortableHeader(),
				new GridFieldDataColumns(),
				new GridFieldPaginator(),
    			new GridFieldDeleteAction(),
    			new GridFieldEditButton(),
    			new GridFieldDetailForm(),
    			new GridFieldAddNewButton());
    	$gridField = new GridField('BlockHolders', null, BlockHolder::get(), $config);
    	
    	$fields = new FieldList(
    			new TabSet('Root',
    				$blockHolderTab = new Tab('BlockHolder',$gridField),
    				$snippetTab = new Tab('Snippet'),
    				$audienceTypeTab = new Tab('AudienceType')));
    	$form = new Form($this, "EditForm", $fields, new FieldList());
    	
		$form->addExtraClass('root-form');
		$form->addExtraClass('cms-edit-form center'); // cms-panel-padded 
		if($form->Fields()->hasTabset()) $form->Fields()->findOrMakeTab('Root')->setTemplate('CMSTabSet');
		$form->setHTMLID('Form_EditForm');
		$form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
		
		 $this->extend('updateEditForm', $fields);
    	return $form;
	}
}