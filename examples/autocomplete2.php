<?php
	require('../../../framework/qcubed.inc.php');
	
	class SampleForm extends QForm {
		protected $txtAutocomplete1;
		protected $txtAutocomplete2;
		protected $txtAutocomplete3;
		
		//tbd
		
		protected function Form_Create() {
			$this->txtAutocomplete1 = new QAutocomplete2($this);
			
			$this->txtAutocomplete2 = new QAutocomplete2($this);
			$this->txtAutocomplete2->MustMatch = true;
			
			$this->txtAutocomplete3 = new QAutocomplete2($this);
			$this->txtAutocomplete3->ComboBox = true;
		}		
	}

	SampleForm::Run('SampleForm');
?>