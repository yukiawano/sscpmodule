<?php
class BlockHolderMain extends LeftAndMain {
    static $url_segment = 'personalization';
    static $url_rule = '$Action/$ID';
    static $menu_title = 'Personalization';
    static $menu_priority = 60;
	static $tree_class = "BlockHolder";
    static $allowed_actions = array('EditForm', 'createholder');
	
    public function init(){
    	parent::init();
    	Requirements::javascript(CMS_DIR . '/javascript/CMSMain.GridField.js');
		Requirements::css(CMS_DIR . '/css/screen.css');
    }
    
    public function getEditForm($id = null, $fields = null){
    	$fields = new FieldList();
    	$config = GridFieldConfig::create();
    	$config->addComponent(new GridFieldDeleteAction());
    	$config->addComponent(new GridFieldDataColumns());
    	$config->addComponent(new GridFieldSortableHeader());
    	$config->addComponent(new GridFieldEditButton());
    	$config->addComponent(new GridFieldDetailForm());
    	$config->addComponent(new GridFieldAddNewButton());
    	$gridField = new GridField('BlockHolders', null, BlockHolder::get(), $config);
    	
    	$actions = new FieldList();
    	$actions->push(new FormAction("createholder","New BlockHolder"));
    			
    	$form = new Form($this, "EditForm",
    				new FieldList($gridField), new FieldList());
		$form->addExtraClass('root-form');
    	$form->addExtraClass('cms-edit-form cms-panel-padded center');
    	
    	// $form->setTemplate('LeftAndMain_EditForm');
    	// $form->addExtraClass('cms-content cms-edit-form center ss-tabset');
    	// if($form->Fields()->hasTabset()) $form->Fields()->findOrMakeTab('Root')->setTemplate('CMSTabSet');
    	
		//$this->extend('updateEditForm', $form);
    	return $form;
    }
    
    public function createholder(){
    	$blockHolder = new BlockHolder();
    	$blockHolder->Name = "Sample".time();
    	$blockHolder->TemplateKey = "Key".time();
    	$blockHolder->Description = "Type description here.";
    	$blockHolder->write();
    	echo '<script type="text/javascript">statusMessage("'.$blockHolder->Name.' is created.");</script>';
		return $this->getResponseNegotiator()->respond($this->request);
    }
}