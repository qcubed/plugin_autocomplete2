<?php
	class QAutocomplete2 extends QAutocompleteBase
	{
		/** @var boolean */
		protected $blnComboBox = false;
		/** @var integer */
		protected $intComboWidth = 0;
		/** @var boolean */
		protected $blnDisplayHtml = false;
		/** @var boolean */
		protected $blnMustMatch = false;
		/** @var string */
		protected $strMultiValDelim = false;

		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
		
			$this->AddPluginJavascriptFile("autocomplete2", "jquery.ui.autocomplete2.js");
		}

		public function getJqSetupFunction() {
			return 'autocomplete';
		}


		protected function MakeJqOptions() {
			$jqOptions = parent::MakeJqOptions();
			if (!is_null($val = $this->MustMatch)) {$jqOptions['mustMatch'] = $val;}
			if (!is_null($val = $this->ComboBox)) {$jqOptions['combo'] = $val;}
			if (!is_null($val = $this->ComboWidth)) {$jqOptions['comboWidth'] = $val;}
			if (!is_null($val = $this->DisplayHtml)) {$jqOptions['renderHtml'] = $val;}
			if (!is_null($val = $this->MultipleValueDelimiter)) {$jqOptions['multiValDelim'] = $val;}
			return $jqOptions;
		}
		
		protected function JsReturnParam() {
			if ($this->strMultiValDelim) {
				return 'this.curTerm()';
			}
			return parent::JsReturnParam();
		}

		
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "ComboBox":				
					try {
						$mixValue = QType::Cast($mixValue, QType::Boolean);
						if ($mixValue) {
							$this->MinLength = 0;
						}
						return ($this->blnComboBox = $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
					
				case "ComboWidth":				
					try {
						$this->intComboWidth = QType::Cast($mixValue, QType::Integer);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
					
				case 'DisplayHtml':
					try {
						$this->blnDisplayHtml = QType::Cast($mixValue, QType::Boolean);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
					
				case 'MustMatch':
					try {
						$this->blnMustMatch = QType::Cast($mixValue, QType::Boolean);
						$this->blnModified = true;	// Be sure control gets redrawn
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
					
				case 'MultipleValueDelimiter':
					$a = $this->GetAllActions('QAutocomplete_SourceEvent');
					if (!empty ($a)) {
						throw new Exception('Must set MultipleValueDelimiter BEFORE calling SetDataBinder');
					}				
					try {
						$this->strMultiValDelim = QType::Cast($mixValue, QType::String);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
									
				case 'DisplayHtml':
					try {
						$this->blnDisplayHtml = QType::Cast($mixValue, QType::Boolean);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
					

				default:
					try {
						return parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
		
		
		public function __get($strName) {
			switch ($strName) {
				case 'SelectedValue': return $this->SelectedId;
				case 'ComboBox': return $this->blnComboBox;
				case 'ComboWidth': return $this->intComboWidth;
				case 'DisplayHtml': return $this->blnDisplayHtml;
				case 'MultipleValueDelimiter': return $this->strMultiValDelim;
				case 'MustMatch': return $this->blnMustMatch;
				
				default: 
					try { 
						return parent::__get($strName); 
					} catch (QCallerException $objExc) { 
						$objExc->IncrementOffset(); 
						throw $objExc; 
					}
			}
		}

		public static function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'ComboBox', 'Should this be displayed as a combobox', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ComboWidth', 'Use this to specify a pixel width for the combo box, if the control guesses wrong.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'DisplayHtml', 'Are we trying to display HTML in the list.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'MultipleValueDelimiter', 'Enables this as a multi-value displayer, and sets the delimiter.', QType::String),
				new QModelConnectorParam (get_called_class(), 'MustMatch', 'Require a value that is in the list. Default allows text to be entered that does not match an item in the list.', QType::Boolean)
			));
		}
	}
?>