<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<h1 class="instruction_title">QAutocomplete2: Extensions to the jQuery UI Autocomplete</h1>

		<b>QAutocomplete2</b> is base on the jquery.ui.autocomplete.js file. It has 
		following additions:
		<ul>
			<li>Allows pending searches to complete even if the user tabs out of the field. 
				With certain forms, users quickly learn what key combinations will select a 
				particular item in an autocomplete. The base implementation can be frustrating to
				use, because if the user tabs out before the menu appears and is rendered, then
				the field is reverted to its previous value. This remedies that by completing the
				search and selecting the item that would have been selected.
			</li>
			<li>Looks inside the result item list for an item with the .selected attribute
				and selects that item. This allows you to specify a selected item that is not
				the top item in the list. Helpful when using categories or other special situations
				with autofocus.
			</li>
			<li>mustMatch option requires a selection from the list to be made, or it will 
				return to prior selection.
			</li>
			<li>renderHtml will use the label of the returned item as html to display in 
				the list.
			</li>
			<li>combo will render as a combobox. 
			</li>
			<li>comboWidth specifies the width of the combo box when you need to expressly set it.
				The widget will try to guess whether you want a fixed width or variable width
				control. jQuery doesn't offer the ability to know how you set this in 
				a style sheet. So, the widget will guess. If you don't want it to guess, then set
				it using this option.
			</li>
		</ul>
	</div>

	<p>Default: <?php $this->txtAutocomplete1->Render(); ?></p>
	<p>MustMatch: <?php $this->txtAutocomplete2->Render(); ?></p>
	<p>Combobox: <?php $this->txtAutocomplete3->Render(); ?></p>
			
	<?php $this->RenderEnd(); ?>
<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/footer.inc.php'); ?>