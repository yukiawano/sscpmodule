<?php
class BlockHolderMain extends LeftAndMain {
    static $url_segment = 'personalization';
    static $url_rule = '$Action/$ID';
    static $menu_title = 'Personalization';
    static $menu_priority = 60;
	static $tree_class = "BlockHolder";
    static $allowed_actions = array('EditForm', 'createholder');
	
    public function getEditForm($id = null, $fields = null){
    	$fields = new FieldList();
    	$config = GridFieldConfig::create();
    	$config->addComponent(new GridFieldDeleteAction());
    	$config->addComponent(new GridFieldDataColumns());
    	$config->addComponent(new GridFieldSortableHeader());
    	$config->addComponent(new GridFieldEditButton());
    	// $config->addComponent($gridFieldDetail = new GridFieldDetailForm());
    	// $gridFieldDetail->setTemplate("BHGridFieldEditForms");
    	$config->addComponent(new BHGridFieldDetailForm());
    	$gridField = new GridField('BlockHolders', null, BlockHolder::get(), $config);
    	$fields->push($gridField);
    	
    	$actions = new FieldList();
    	$actions->push(new FormAction("createholder","New BlockHolder"));
    	
    	$form = new Form($this, "EditForm", $fields, $actions);
    	
    	$form->addExtraClass('cms-edit-form cms-panel-padded center ' . $this->BaseCSSClasses());
		$this->extend('updateEditForm', $form);
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