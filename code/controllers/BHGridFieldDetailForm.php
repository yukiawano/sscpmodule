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
		);
	}
	
	public function edit($gridField, $request) {
		$controller = $gridField->getForm()->Controller();
		$this->gridField = $gridField;
		
		$return = $this->customise(array('ItemEditForm' => $this->ItemEditForm($gridField, $request, $controller)))->renderWith($this->template);
		return $return;
	}
	
	public function ItemEditForm($gridField, $request, $controller) {
		$fields = new FieldList();
		$fields->push(new TextField("Test"));
		$form = new Form(
				$controller,
				'ItemEditForm',
				$fields,
				new FieldList()
		);
		
		$form->addExtraClass('cms-edit-form cms-panel-padded center ' . $controller->BaseCSSClasses());
		$controller->extend('updateEditForm', $form);
		return $form;
	}
	
}