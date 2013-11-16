<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/header.inc.php'); ?>

	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<h1 class="instruction_title">QAutocomplete2: Extensions to the jQuery UI Autocomplete</h1>

		<b>QAutocomplete2</b> is based on the jquery.ui.autocomplete.js file, which is an extension
		of the jQuery UI Autocomplete. It has the
		following additions:
		<ul>
			<li>When AutoFocus is on, allows pending searches to complete even if the user tabs out of the field. 
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
				the list. Note that this plugin also prevents the filter from looking inside the 
				html tags.
			</li>
			<li>combo will render as a combobox. 
			</li>
			<li>comboWidth specifies the width of the combo box when you need to expressly set it.
				The widget will try to guess whether you want a fixed width or variable width
				control. jQuery doesn't offer the ability to know how you set this in 
				a style sheet. So, the widget will guess. If you don't want it to guess, then set
				it using this option.
			</li>
			<li>multiValDelim The delimiter that separates the items in a multiple selection situation. Setting this
				puts this in a mode where multiple selections are allowed.
			</li>
			<li>Sends "this" to the filter function so that the filter can respond differently based on options.
			</li>
			<li>Added regEx option so you can more easily change the filtering expression on non-ajax filtering.</li>

		</ul>
	</div>

	<table>
		<tr>
			<td>Default</td>
			<td><?php $this->txtAutocomplete1->Render(); ?></td>
		</tr>
		<tr>
			<td>MustMatch</td>
			<td><?php $this->txtM->Render(); ?></td>
		</tr>
		<tr>
			<td>AutoFocus</td>
			<td><?php $this->txtF->Render(); ?></td>
		</tr>
		<tr>
			<td>AutoFocus, MustMatch, DisplayHtml</td>
			<td><?php $this->txtMFH->Render(); ?></td>
		</tr>
		<tr>
			<td>Combo</td>
			<td><?php $this->txtC->Render(); ?></td>
		</tr>
		<tr>
			<td>Multi-Select</td>
			<td><?php $this->txtMulti->Render(); ?></td>
		</tr>
		<tr>
			<td>Multi-Select, MustMatch</td>
			<td><?php $this->txtMultiM->Render(); ?></td>
		</tr>
		<tr>
			<td>Multi-Select, AutoFocus</td>
			<td><?php $this->txtMultiF->Render(); ?></td>
		</tr>
		<tr>
			<td>Multi-Select, AutoFocus, MustMatch, DisplayHtml</td>
			<td><?php $this->txtMultiMFH->Render(); ?></td>
		</tr>
		<tr>
			<td>StartsWith Filter</td>
			<td><?php $this->txtFilterStartsWith->Render(); ?></td>
		</tr>
	</table>


	<?php $this->RenderEnd(); ?>
<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/footer.inc.php'); ?>