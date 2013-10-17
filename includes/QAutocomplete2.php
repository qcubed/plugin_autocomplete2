<?php
	class QAutocomplete2 extends QAutocompleteBase
	{
		/** @var boolean */
		protected $blnComboBox = false;
		/** @var boolean */
		protected $blnDisplayHtml = false;
		/** @var boolean */
		protected $blnMustMatch = false;

		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
		
			$this->AddPluginJavascriptFile("QAutocomplete2", "jquery.ui.autocomplete2.js");
		}
						
		protected function makeJqOptions() {
			$strJqOptions = parent::makeJqOptions();
			$strJqOptions .= ', ';
			
			$strJqOptions .= $this->makeJsProperty('MustMatch', 'mustMatch');
			$strJqOptions .= $this->makeJsProperty('ComboBox', 'combo');
			$strJqOptions .= $this->makeJsProperty('DisplayHtml', 'displayHtml');
			if ($strJqOptions) $strJqOptions = substr($strJqOptions, 0, -2);
			return $strJqOptions;
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
				case 'DisplayHtml': return $this->blnDisplayHtml;
				
				default: 
					try { 
						return parent::__get($strName); 
					} catch (QCallerException $objExc) { 
						$objExc->IncrementOffset(); 
						throw $objExc; 
					}
			}
		}
		
	}
?>