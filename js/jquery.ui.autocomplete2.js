/**
*	JQuery UI Autocomplete Extension
*	
*	Adds the following functionality to the jquery ui autocomplete:
*	- Allows pending searches to complete even if the user tabs out of the field. 
*	  With certain forms, users quickly learn what key combinations will select a 
*	  particular item in an autocomplete. The base implementation can be frustrating to
*	  use, because if the user tabs out before the menu appears and is rendered, then
*	  the field is reverted to its previous value. This remedies that by completing the
*	  search and selecting the item that would have been selected.
*	- Looks inside the result item list for an item with the .selected attribute
*	  and selects that item. This allows you to specify a selected item that is not
*	  the top item in the list. Helpful when using categories or other special situations
*	  with autofocus.
*	- mustMatch option requires a selection from the list to be made, or it will return to prior selection
*	- renderHtml will use the label of the returned item as html to display in the list
*	- combo will render as a combobox
*	- comboWidth specifies the width of the combo box when you need to expressly set it
*
*	Usage:
*	- Place in your script immediately after inclusion of jquery ui. 
*
*	Known Issues:
*	- If you use this, you will not be able to attach blur event handlers to the field after
*	  attaching autocomplete() to the field. This is a quirk of the jquery ui implementation.
*	  They could help by making the blur event call in autocomplete a function so that it 
*	  is over-rideable. 
**/
 
(function( $, undefined ) {


$.widget( "ui.autocomplete", $.ui.autocomplete, {
	options: {
		mustMatch: false,	// disallow items that aren't in the list
		renderHtml: false,	// treat the item label as html
		combo: false,
		comboWidth: null
	},
	_create: function() {
		if (this.options.combo) {
			var that = this;
			var input = this.element;
			var fldHeight = input.outerHeight() - 2;
			var fldWidth = input.css("width");
			
			// wrap the input in a structure that will support combo box styling with an additional button
			var inputWrapper = input				
				.wrap ('<div>')
				.parent();
			
			var wrapper = inputWrapper.wrap('<div>')
				.parent();
			
			// get initial size for comparison later
			var w1 = input.width();
			var w2 = wrapper.width();
			
			input.css ({
				marginTop: '0px',		// reset for webkit
				marginBottom: '0px',	// reset for webkit
				width: "100%"
			});
			//.addClass( "ui-corner-left" ); removes focus ring on firefox and opera, bad for accessability
			
			inputWrapper.addClass ("ui-combobox-input")
				.css ({
					marginRight: '19px'	// make room for arrow button
				});	
				
			wrapper.addClass ("ui-combobox")
				.css ({
					display:"inline-block", // same as input textbox
					position:"relative"		// required for absolutely positioned arrow button
				});
			
			// There is no easy way for us to find out if the width was specified as a percent, so
			// we try to guess if the width was specified as 100%, or 
			// allow user to specify an additional width. We guess by testing if the input is
			// taking up entire width of its parent.
			
			if (this.options.comboWidth) {
				wrapper.width(this.options.comboWidth);	
			} 
			else if (w1 == w2) {
				wrapper.width('100%');	// should work in most cases
			} else {
				wrapper.width(fldWidth);	// transfer width from input
			}
			
			// add drop down arrow button
			$("<div></div>")
				.data ('input', input)
				.appendTo (wrapper)
				.css ({
					position:"absolute",
					display:"inline-block",
					top:"0px",
					bottom: "0px",
					right: "0px",
					width: '17px'
				})
				.button({
					icons: {
						primary: "ui-icon-triangle-1-s"
					},
					text: false
				})			
				.removeClass( "ui-corner-all" )
				.addClass( "ui-corner-right" )
				.addClass ("ui-combobox-button")	
				.click(function() {
					var input = $(this).data('input');
					if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
						input.autocomplete( "close" );
						input.focus();
						return;
					}
					
					$( this ).blur();
					input.autocomplete( "search", "" );
					input.focus();
				});
				/*.wrap("<div>")
				.parent()
				.css ({
					display: 'table-cell',
					paddingBottom:'4px'
				});*/

		};
		
		this._on( this.element, {
			keydown: function( event ) {
				if ( this.element.prop( "readOnly" ) ) {
					return;
				}

				suppressKeyPress = false;
				suppressInput = false;
				suppressKeyPressRepeat = false;
				var keyCode = $.ui.keyCode;
				switch( event.keyCode ) {
					case keyCode.TAB:
						if ( this.menu.active && this._value().length > 0 && !this.searching && !this.pending) {
							this.menu.select( event );
						}
						else {
							this.menu.blur();
						}
						// safe to propagate. Superclass will do nothing because menu will not be active
						break;
					
				}

			},
			focus: function() {
				this.cancelMenu = false;
				// superclass will do these next things
				//this.selectedItem = null;
				//this.previous = this._value();
				
			},
			blur: function( event ) {
				if ( this.cancelBlur ) {
					delete this.cancelBlur;
					return;
				}

				//clearTimeout( this.searching ); don't do this, but do the next lines instead
				if (this.pending || this.searching) {
					this.cancelMenu = true;
				}
				
				this.close( event );
				this._change( event );

				event.stopImmediatePropagation();	// Must prevent superclass from canceling search
			}

		});
		this._super();
		this._setOption('mustMatch', this.options.mustMatch);
		this._setOption('renderHtml', this.options.renderHtml);
		this._setOption('combo', this.options.combo);
		this._setOption('comboWidth', this.options.comboWidth);
	},
	_searchTimeout: function( event ) {
		clearTimeout( this.searching );
		this.searching = this._delay(function() {
			// only search if the value has changed
			
			// checking the term is not an accurate reflection of whether the value changed
			//if ( this.term !== this._value() ) {
				this.selectedItem = null;
				this.search( null, event );
				this.searching = null;
			//}
		}, this.options.delay );
	},
	search: function( value, event ) {
		value = value != null ? value : this._value();

		// always save the actual value, not the one passed as an argument
		this.term = this._value();

		if ( value.length < this.options.minLength) { 
			return this.close( event );
		}

		if ( this._trigger( "search", event ) === false ) {
			return;
		}

		this.searchEvent = event;	// save to repost later if we get a late response
		return this._search( value );
	},
	__response: function( content ) {
		if ( content ) {
			content = this._normalize( content );
		}
		this._trigger( "response", null, { content: content } );
		if ( !this.options.disabled && content && content.length && !this.cancelSearch ) {
			if (this._suggest( content )) {
				this._trigger( "open" );
			}
		} else {
			// use ._close() instead of .close() so we don't cancel future searches
			this._close();
		}
	},
	close: function( event ) {
		//this.cancelSearch = true;
		this._close( event );
	},
	_change: function( event ) {
		if ( this.previous !== this._value() && 
				!this.pending &&
				!this.searching) { // still have pending searches, so don't change yet
			if (this.mustMatch && !this.selectedItem) {
				this._value( "" );
				this.term = '';
			}
			this._trigger( "change", event, { item: this.selectedItem } );
		}
	},
	_renderItem: function( ul, item ) {
		if (this.options.renderHtml) {
            return jQuery( "<li>" )
            .data( "item.autocomplete", item )
            .append( jQuery( "<a></a>" ).html(item.label) )
            .appendTo( ul );			
		} else {
			return	this._super(ul, item);
		}
	},
	_suggest: function( items ) {
		var t = this.term;
		var startPos, endPos;
		var newVal;
					    
		if (!this.cancelMenu) {
			var ul = this.menu.element
				.empty()
				.zIndex( this.element.zIndex() + 1 );
			this._renderMenu( ul, items );
			this.menu.refresh();
	
			// size and position menu
			ul.show();
			this._resizeMenu();
			ul.position( $.extend({
				of: this.element
			}, this.options.position ));
	
			// find a selected item
			if ( this.options.autoFocus ) {
				var filteredItems = jQuery.grep (items, function(item, index) {return (item.selected);});
				if (filteredItems.length > 0) {
					for (var i = 0; i < items.length; i++) {
						this.menu.next();
						if (items[i].selected) break;
					}
				} else {
					this.menu.next();
				}
			}
			return true;
		} else {  // get data even when we don't want to pop up menu
			// todo: test for exact match so we can select an item even when autofocus is off?
			if ( this.options.autoFocus) {
				if (items[0] && this.term.length > 0)  {
					var filteredItems = jQuery.grep (items, function(item, index) {return (item.selected);});
					if (filteredItems.length > 0) {
						newVal = filteredItems[0].value;
						this.selectedItem = filteredItems[0];
					} else {
						newVal = items[0].value;
						this.selectedItem = items[0];
					}
				} else {
					newVal = "";
					this.selectedItem = null;
				}
				this._value(newVal);
				
				// special case, trigger a change, we have received a response, but we previously lost focus
				if (this.pending < 2) {
					this._trigger( "change", this.searchEvent, { item: this.selectedItem } );
					this.cancelMenu = false;
				}

			}
			return false;
		}
	},
	_initSource: function() {	// add sending "this" to search filter so we can modify filter based on options
		var array;
		if ( $.isArray(this.options.source) ) {
			array = this.options.source;
			this.source = function( request, response ) {
				response( $.ui.autocomplete.filter( array, request.term, this ) );
			};
		} else {
			this._super();
		}
	}

});

$.extend( $.ui.autocomplete, {
	escapeRegex: function( value ) {
		return value.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&");
	},
	filter: function(array, term, instance) {
		var matcher = new RegExp( $.ui.autocomplete.escapeRegex(term), "i" );
		return $.grep( array, function(value) {
			return matcher.test( instance.options.renderHtml ? (value.value || value) : (value.label || value.value || value) );
		});
	}
});


}( jQuery ));
