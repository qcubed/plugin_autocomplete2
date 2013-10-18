# jquery.ui.autocomplete2.js and QAutocomplete2

This repo hosts both the jquery.ui.autocomplete2.js file and the corresponding QAutocomplete2 plugin.

## QAutocomplete2

QAutocomplete2 is a QCubed wrapper for the jQuery jquery.ui.autocomplate2.js plugin. 

This control is installable by Composer. To install, add the following to the corresponding sections of your composer.json root file:
```
	"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/qcubed/QAutocomplete2"
        }
    ],
```    
and
```
	"require": {
		"qcubed/plugins/autocomplete2": "dev-master"
	},

```

## jquery.ui.autocomplete.js

This plugin adds a few features that are missing from the standard jQuery UI autocomplete and which the jQuery UI team has said they won't incorporate into their code. 

* Allows pending searches to complete even if the user tabs out of the field. With certain forms, users quickly learn what key combinations will select a particular item in an autocomplete. The base implementation can be frustrating to use, because if the user tabs out before the menu appears and is rendered, then the field is reverted to its previous value. This plugin remedies that by completing the search and selecting the item that would have been selected. Because of this, you DO have to be ready to have the autocomplete change AFTER a blur event.
* Issues a change event when the autocomplete changes, even after a blur event when resolving a pending ajax search.
* Looks inside the result item list for an item with the .selected attribute and selects that item. This allows you to specify a selected item that is not the top item in the list. Helpful when using categories or other special situations with autofocus.
* mustMatch option requires a selection from the list to be made, or it will return to prior selection.
* renderHtml will use the label of the returned item as html to display in the list. Based on Scott Gonzales code, but with additional fix so that the html is not included when searching.
* combo option will render as a combobox. Combobox can be fixed with or percent based, and will try to guess.
* comboWidth option specifies the width of the combo box when you need to expressly set it. Helpful when the guessing code can't correctly figure out if you want a fixed width or percentage width combobox.
