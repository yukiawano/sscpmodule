<?php
/**
 * Provides view and edit forms at GridField-specific URLs.  These can be placed into pop-ups by an appropriate front-end.
 *
 * The URLs provided will be off the following form:
 *  - <FormURL>/field/<GridFieldName>/item/<RecordID>
 *  - <FormURL>/field/<GridFieldName>/item/<RecordID>/edit
 *  
 *  Refered URL: https://gist.github.com/1584550
 */
class BHGridFieldDetailForm extends RequestHandler implements GridField_URLHandler {
	protected $gridField;
	protected $template = 'GridFieldDetailForm';
	
	public function getURLHandlers($gridField) {
		return array(
				'item/$ID/edit' => 'edit',
				'item/$ID/ItemEditForm' => "ItemEditForm",
		);
	}
	
	public function edit($gridField, $request) {
		$controller = $gridField->getForm()->Controller();
		$this->gridField = $gridField;
		
		$return = $this->customise(array('ItemEditForm' => $this->ItemEditForm($gridField, $request)))->renderWith($this->template);
		return '<div class="cms-content center BlockHolderMain LeftAndMain">'.$return.'</div>';
	}
	
	public function ItemEditForm($gridField, $request) {
		$obj = $gridField->getList()->byID($request->param('ID'));
		$form = new Form(
				$this,
				'ItemEditForm',
				$obj->getCMSFields(),
				new FieldList($saveAction = new FormAction('doSave', _t('GridFieldDetailsForm.Save', 'Save')))
		);
		$form->loadDataFrom($obj);
		$form->setFormAction(Controller::join_links($gridField->Link('item'), $obj->ID, 'ItemEditForm'));
		// $form->addExtraClass('cms-edit-form cms-panel-padded center ' . $controller->BaseCSSClasses());
		// $controller->extend('updateEditForm', $form);
		return $form;
	}
	
	public function doSave($data, Form $form) {
		// var_dump($form);
		$form->Controller()->redirectBack();
	}
}