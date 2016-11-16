<?php
	require('../../../qcubed/qcubed.inc.php');
	
	class SampleForm extends QForm {
		protected $txtAutocomplete1;
		protected $txtM;
		protected $txtF;
		protected $txtMFH;
		protected $txtC;
		protected $txtMulti;
		protected $txtMultiM;
		protected $txtMultiF;
		protected $txtMultiMFH;
		protected $txtFilterStartsWith;

		protected function Form_Create() {
			$this->txtAutocomplete1 = new QAutocomplete2($this);
			$this->txtAutocomplete1->SetDataBinder("update_autocompleteList");

			$this->txtM = new QAutocomplete2($this);
			$this->txtM->MustMatch = true;
			$this->txtM->SetDataBinder("update_autocompleteList");
			$this->txtM->AddAction (new QAutocomplete_ChangeEvent(), new QAjaxAction ('ajaxautocomplete_change'));

			$this->txtF = new QAutocomplete2($this);
			$this->txtF->AutoFocus = true;
			$this->txtF->SetDataBinder("update_autocompleteList");
			$this->txtF->AddAction (new QAutocomplete_ChangeEvent(), new QAjaxAction ('ajaxautocomplete_change'));

			$this->txtMFH = new QAutocomplete2($this);
			$this->txtMFH->MustMatch = true;
			$this->txtMFH->AutoFocus = true;
			$this->txtMFH->SetDataBinder("update_autocompleteList");
			$this->txtMFH->AddAction (new QAutocomplete_ChangeEvent(), new QAjaxAction ('ajaxautocomplete_change'));
			$this->txtMFH->DisplayHtml = true;

			$this->txtC = new QAutocomplete2($this);
			$this->txtC->ComboBox = true;
			$this->txtC->SetDataBinder("update_autocompleteList");
			
			$this->txtMulti = new QAutocomplete2($this);
			$this->txtMulti->MultipleValueDelimiter = ',';
			$this->txtMulti->SetDataBinder("update_autocompleteList");
			$this->txtMulti->Columns = 50;

			$this->txtMultiM = new QAutocomplete2($this);
			$this->txtMultiM->MultipleValueDelimiter = ',';
			$this->txtMultiM->SetDataBinder("update_autocompleteList");
			$this->txtMultiM->Columns = 50;
			$this->txtMultiM->MustMatch = true;

			$this->txtMultiF = new QAutocomplete2($this);
			$this->txtMultiF->MultipleValueDelimiter = ',';
			$this->txtMultiF->SetDataBinder("update_autocompleteList");
			$this->txtMultiF->Columns = 50;
			$this->txtMultiF->AutoFocus = true;

			$this->txtMultiMFH = new QAutocomplete2($this);
			$this->txtMultiMFH->MultipleValueDelimiter = ',';
			$this->txtMultiMFH->SetDataBinder("update_autocompleteList");
			$this->txtMultiMFH->Columns = 50;
			$this->txtMultiMFH->MustMatch = true;
			$this->txtMultiMFH->AutoFocus = true;
			$this->txtMultiMFH->DisplayHtml = true;

			$this->txtFilterStartsWith = new QAutocomplete2($this);
			$this->txtFilterStartsWith->Source = $this->getList();
			$this->txtFilterStartsWith->UseFilter(QAutocomplete2::FILTER_STARTS_WITH);
		}

		protected function getList($strTerm = null, $blnHtml = false) {
			if ($strTerm) {
				$cond = QQ::OrCondition (
					QQ::Like (QQN::Person()->FirstName, '%' . $strTerm . '%'),
					QQ::Like (QQN::Person()->LastName, '%' . $strTerm . '%')
				);
			} else {
				$cond = QQ::All();
			}

			$clauses[] = QQ::OrderBy (QQN::Person()->LastName, QQN::Person()->FirstName);

			$lst = Person::QueryArray ($cond, $clauses);

			$a = array();
			foreach ($lst as $objPerson) {
				$item = new QListItem ($objPerson->FirstName . ' ' . $objPerson->LastName, $objPerson->Id);
				if ($blnHtml) {
					$item->Label = '<em>' . $objPerson->FirstName . ' ' . $objPerson->LastName . '</em>';
				}
				$a[] = $item;
			}
			return $a;
		}


		protected function update_autocompleteList($strFormId, $strControlId, $strParameter) {
			$objControl = $this->GetControl ($strControlId);
			$a = $this->getList($strParameter, $objControl->DisplayHtml);
			$objControl->DataSource = $a;
		}
	}

	SampleForm::Run('SampleForm');
?>
