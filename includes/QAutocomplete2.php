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

		const FILTER_CONTAINS ='function(term) { return $.ui.autocomplete2.escapeRegex(term);}'; // this is the default filter
		const FILTER_STARTS_WITH ='function(term) { return ("^" + $.ui.autocomplete2.escapeRegex(term)); }';

		/**
		 * Set a filter to use when using a simple array as a source (in non-ajax mode). Note that ALL non-ajax autocompletes on the page
		 * will use the new filter.
		 *
		 * @static
		 * @throws QCallerException
		 * @param string|QJsClosure $filter represents a closure that will be used as the global filter function for jQuery autocomplete.
		 * The closure should take two arguments - array and term. array is the list of all available choices, term is what the user typed in the input box.
		 * It should return an array of suggestions to show in the drop-down.
		 * <b>Example:</b> <code>QAutocomplete::UseFilter(QAutocomplete::FILTER_STARTS_WITH)</code>
		 * @return void
		 *
		 * @see QAutocomplete::FILTER_CONTAINS
		 * @see QAutocomplete::FILTER_STARTS_WITH
		 */
		static public function UseFilter($filter) {
			if ($filter instanceof QJsClosure) {
				$filter = $filter->toJsObject();
			} else if (!is_string($filter)) {
				throw new QCallerException("filter must be either a string or an instance of QJsClosure");
			}
			$strJS = '(function($, undefined) { $.ui.autocomplete2.regEx = ' . $filter . '} (jQuery))';
			QApplication::ExecuteJavaScript($strJS);
		}

		public function getJqSetupFunction() {
			return 'autocomplete';
		}


		protected function makeJqOptions() {
			$strJqOptions = parent::makeJqOptions();
			if ($strJqOptions) $strJqOptions .= ', ';
			
			$strJqOptions .= $this->makeJsProperty('MustMatch', 'mustMatch');
			$strJqOptions .= $this->makeJsProperty('ComboBox', 'combo');
			$strJqOptions .= $this->makeJsProperty('ComboWidth', 'comboWidth');
			$strJqOptions .= $this->makeJsProperty('DisplayHtml', 'renderHtml');
			$strJqOptions .= $this->makeJsProperty('MultipleValueDelimiter', 'multiValDelim');
			if ($strJqOptions) $strJqOptions = substr($strJqOptions, 0, -2);
			return $strJqOptions;
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

		public static function GetMetaParams() {
			return array_merge(parent::GetMetaParams(), array(
				new QMetaParam (get_called_class(), 'ComboBox', 'Should this be displayed as a combobox', QType::Boolean),
				new QMetaParam (get_called_class(), 'DisplayHtml', 'Are we trying to display HTML in the list.', QType::Boolean),
				new QMetaParam (get_called_class(), 'MultipleValueDelimiter', 'Enables this as a multi-value displayer, and sets the delimiter.', QType::String),
				new QMetaParam (get_called_class(), 'MustMatch', 'Require a value that is in the list. Default allows text to be entered that does not match an item in the list.', QType::Boolean)
			));
		}
	}
?>