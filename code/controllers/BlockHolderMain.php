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
	/**
	 * @return Form
	 */
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
		
    	// $form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
		// $form->addExtraClass('cms-edit-form center ' . $this->BaseCSSClasses());
		
		 $this->extend('updateEditForm', $fields);
    	return $form;
	}
	
	/*
    static $url_segment = 'personalization';
    static $url_rule = '/$Action/$ID';
    static $menu_title = 'Personalization';
    static $menu_priority = -1;
    */
	/*
    public function init(){
    	parent::init();
    	Requirements::javascript(CMS_DIR . '/javascript/CMSMain.GridField.js');
		Requirements::css(CMS_DIR . '/css/screen.css');
    }*/
    
	/*
    function getEditForm($id = null, $fields = null){
    	return new Form($this, "EditForm", new FieldList(new LabelField("Foo", "Bar")), new FieldList());
    }*/
    	/*
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
    				$blockHolderTab = new Tab('Main',$gridField),
    				$snippetTab = new Tab('Access')));
    	$form = new Form($this, "EditForm", $fields, new FieldList());
    	
		$form->addExtraClass('root-form');
		$form->addExtraClass('cms-edit-form center'); // cms-panel-padded 
		if($form->Fields()->hasTabset()) $form->Fields()->findOrMakeTab('Root')->setTemplate('CMSTabSet');
		$form->setHTMLID('Form_EditForm');
		$form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
		
    	// $form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
		// $form->addExtraClass('cms-edit-form center ' . $this->BaseCSSClasses());
		
		 $this->extend('updateEditForm', $fields);
    	return $form;
    }*/
    	
    /*
    public function createholder(){
    	$blockHolder = new BlockHolder();
    	$blockHolder->Name = "Sample".time();
    	$blockHolder->TemplateKey = "Key".time();
    	$blockHolder->Description = "Type description here.";
    	$blockHolder->write();
    	echo '<script type="text/javascript">statusMessage("'.$blockHolder->Name.' is created.");</script>';
		return $this->getResponseNegotiator()->respond($this->request);
    }
    
    function LinkPreview() {
    	return false;
    }*/
    /*
    function LinkPreview() {
    	return false;
    }*/
    /*
    function Breadcrumbs($unlinked = false) {
    	return new ArrayList(array(
    			new ArrayData(array(
    					'Title' => $this->SectionTitle(),
    					'Link' => false
    			))
    	));
    }*/
    
    
}