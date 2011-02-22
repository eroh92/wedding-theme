<?php /* @var $_der DerThemeOptions */

	global $_der, $d, $options_array_name, $options_context, $module_instance, $title;

	if ($options_context) {  $_der->set_options_context($options_context); } // Set options context ($slot)

	if ( empty($options_array_name) ) { $options_array_name = 'options'; }	// as parameter

	$options_array = $d->$options_array_name;

	if ( empty($options_array) ) { $options_array = array($options_array_name => array()); }		// as array


	// Check for updates

	if ( defined('DER_UPDATE_CHECK_SERVER') ) {

		$cookie_prefix = der_generate_id(der_theme_data('Name')) . '_';

		$last_version_checked = $_COOKIE[$cookie_prefix . 'last_version_checked'];

		if ($last_version_checked) {

			/* last_version_checked is available */

			$themedata = unserialize(base64_decode($last_version_checked));

			$current_version = der_theme_data('Version');

			if ( $themedata['version'] > $current_version ) {

				$update_string = '(New version <a target="_blank" title="' . der_theme_data('Name') . ' ' . $themedata['version'] . '" href="' . $themedata['product_page'] . '">available</a>)';

			}

			$version_check = '';

		} else {

			/* last_version_checked is not available */

			$version_check = 'check_for_updates();';

		}

	}

	// ---

	echo '
<script type="text/javascript">
(function($) {
wp_home = "' . get_bloginfo('home') . '";
der_upload_context = "admin-interface";
options_context = "' . $options_context . '";
cookie_prefix = "' . $cookie_prefix . '";
$(document).ready(function() { ' . $version_check . '
	$("#der-admin-body .code-option textarea").tabby();
});
})(jQuery);
</script>

<div id="der-admin">';

	der_admin_postmsg();

	echo '
<div id="der-admin-head">

	<h2 class="theme-name shadow">' . $title . ' <span class="theme-version" style="display: block;">' . der_theme_data('Name') . ' ' . der_theme_data('Version') . ' ' . $update_string . '</span></h2>

	<ul class="reset sections">' . "\n";

	$active = true;

	foreach ($options_array as $section => $null) {

		if ( $active ) { $class=' class="active"'; $active = false; } else { $class=''; }

		echo '		<li><a ' . $class . ' rel="' . der_generate_id($section) . '" href="#' . der_generate_id($section) . '">' . $section . '</a></li>' . "\n";

	}

	echo '	</ul><!-- sections -->' . "\n";

	der_admin_metalinks();

	echo '

	<img id="ajax-loader" alt="" src="' . get_bloginfo('template_directory') . '/functions/admin/images/ajax-loader.gif' . '" />

</div><!-- admin-head -->

<div id="der-admin-body">

	<div class="hidden">
		<a id="lightbox-cache" href="#" rel="lightbox"></a>
	</div><!-- hidden -->

	<form id="options-form" method="post">
		<p class="reset form-buttons">
			<input type="submit" value="Save Options" />
			<input type="hidden" name="action" value="theme_save" />
		</p>

	<!-- start options -->
';

	/* Render the HTML For each of the sections */

	$first_section = true;

	foreach ( $options_array as $section => $contents ) {

		if ( $first_section ) { $option_class =''; $first_section = false; } else { $option_class = ' secondary'; }

		echo "\n\t" . '<h2 class="title">' . $section . '</h2>

	<div id="' . der_generate_id($section) . '" class="root-section' . $option_class . '" >' . "\n";

		foreach ( $options_array[$section] as $option ) {

			if ($option['intro_message']) {

				echo '
		<div class="custom">
			<h3 class="reset">' . $option['intro_message']['title'] . '</h3>

			<p>
				' . $option['intro_message']['content'] . '
			</p>

		</div>';

			}

			switch ( $option['type'] ) {

				/* Text Option */

				case 'text':

					$mime_class = ( $option['mime'] == 'image' ) ? 'mime-image-container ' : '';

					echo '
		<div class="option ' . $mime_class . 'clearfix">
			
			<h3 class="reset">' . $option['name'] . '</h3>
';

				if ( $option['mime'] == 'image' && $_der->getval($option['id']) ) {

					$mime_class = ' class="mime-image" ';

					$image_preview_style = '';

				} else {

					$mime_class = '';

					$image_preview_style = ' style="display: none;" ';

				}

					echo '
			<a class="image-preview" rel="lightbox" href="#image-preview" ' . $image_preview_style . '" title="' . $option['name'] . '"></a>

			<div class="left">
				<input autocomplete="off" ' . $mime_class . 'type="text" tabindex="2" name="' . $option['id'] . '" value="' . $_der->getval($option['id']) . '" />';

		if ( $option['mime'] == 'image' ):
				echo '
<div class="buttons-container">
	<button class="image_upload button" name="' . $option['id'] . '">Upload Image</button>
	<button ' . $image_preview_style . ' class="image_remove button" name="' . $option['id'] . '">Remove</button>
</div><!-- .buttons-container -->';
		endif;

echo '			</div><!-- left -->

			<div class="right">
				<p>' . $option['description'] . '</p>
			</div><!-- right -->

		</div><!-- option -->
';

					break;

				/* Select Option */

				case 'select':

					echo '
		<div class="option clearfix">
		
			<h3 class="reset">' . $option['name'] . '</h3>

			<div class="left">
				<select tabindex="2" name="' . $option['id'] . '" >
';

				$vals = csv2array($option['fields']);

				foreach ( $vals as $opt ) {

					$selected = $_der->selected($option['id'], $opt);

					echo '					<option value="' . $opt . '"' . $selected . '>' . $opt . '</option>' . "\n";

				}

					echo '				</select>
			</div><!-- left -->

			<div class="right">
				<p>' . $option['description'] . '</p>
			</div><!-- right -->

		</div><!-- option -->
';
					break;

				/* Textarea Option */

				case 'textarea':

					$rows = ( $option['rows'] ) ? $option['rows'] : 5;

					echo '
		<div class="option clearfix">

			<h3 class="reset">' . $option['name'] . '</h3>

			<div class="left">
				<textarea tabindex="2" rows="' . $rows . '" name="' . $option['id'] . '">' . $_der->getval($option['id']) . '</textarea>
			</div><!-- left -->

			<div class="right">
				<p>' . $option['description'] . '</p>
			</div><!-- right -->

		</div><!-- option -->
';
					break;

				/* Checkbox Option */

				case 'checkbox':

					echo '
		<div class="option checkbox-option clearfix">

			<h3 class="reset checkbox-type">' . $option['name'] . '</h3>
';

					foreach ( $option['fields'] as $id => $desc ) {

						$checked = $_der->checked($id);

						echo "\n" . '			<p><input tabindex="2" type="checkbox" name="' . $id . '" ' . $checked . ' id="' . $id . '" /><label for="' . $id . '">' . $desc . '</label></p>' . "\n";

					}

					echo '
		</div><!-- option -->
';

					break;

				/* Multiple Categories Option */

				case 'taxonomies_checkbox':

					echo '
		<div class="option checkbox-option clearfix">

			<h3 class="reset checkbox-type">' . $option['name'] . '</h3>
';

					$values = array();

					$taxonomies = get_categories('taxonomy=' . $option['taxonomy'] . '&hide_empty=0');

					foreach ($taxonomies as $tax) { $values[ $option['id'] . '_' . $tax->term_id ] = string2html($tax->name); }

					foreach ( $values as $id => $desc ) {

						$checked = $_der->checked($id);

						echo "\n" . '			<p><input tabindex="2" type="checkbox" name="' . $id . '" ' . $checked . ' id="' . $id . '" /><label for="' . $id . '">' . $desc . '</label></p>' . "\n";

					}

					echo '
		</div><!-- option -->
';

					break;

					/* Radio Option */

					case 'radio':

					echo '
		<div class="option radio-option clearfix">

			<h3 class="reset radio-type">' . $option['name'] . '</h3>

			<div class="left">
';

					$vals = csv2array($option['fields']);

					$style = ( $option['flow'] == 'inline' ) ? 'style="display: inline-block; padding-right: 30px;" ' : null;

					foreach ( $vals as $opt ) {

						$checked = $_der->checked($option['id'], $opt);

						echo '				<p ' . $style . '><input tabindex="2" type="radio" name="' . $option['id'] . '" value="' . $opt . '"' . $checked . ' /> ' . $opt . ' </p>' . "\n";

					}
					
					echo '			</div><!-- left -->

			<div class="right">
				<p>' . $option['description'] . '</p>
			</div><!-- right -->

		</div><!-- option -->
';
					break;

				/* Category / Page */

				case 'category':
				case 'page':

					echo '
		<div class="option page-category-option clearfix">

			<h3 class="reset">' . $option['name'] . '</h3>

			<div class="left">
				<select tabindex="2" name="' . $option['id'] . '" >
';

				$ids = ( $option['type'] == 'category' ) ? get_all_category_ids() : der_get_all_page_ids();

				if ( $option['type'] == 'category' AND isset($option['children_of']) ) {

					$ids = get_term_children( $_der->getval($option['children_of']), 'category' );

					$option['description'] .= ' <a class="button" href="">Refresh to Update</a>.';

				}

				if ( ( $option['type'] == 'page' AND !$_der->getval($option['id']) ) OR ( ! get_permalink($_der->getval($option['id']) ) ) ) {
					$button_style = null;
				} else {
					$button_style = ' style="display: none;"';
				}

					$option['description'] .= '<br/><br/>
<a' . $button_style . ' class="button new-page" href="#">Create Page</a>
';

				array_unshift($ids, 'novalue');

				foreach ( $ids as $id ) {

					$selected = $_der->selected($option['id'], $id);

					if ($option['type'] == 'category') { 

						if ( $id == 'novalue' ) { $name = 'Select a ' . ucwords($option['type']);  }
						else { $name = get_cat_name($id); }

					} else {

						if ( $id == 'novalue' ) { $name = 'Select a ' . ucwords($option['type']); }
						else { $page = get_page($id); $name = $page->post_title; }

					}

					echo '					<option value="' . $id . '"' . $selected . '>' . $name . '</option>' . "\n";

				}

					echo '				</select>
			</div><!-- left -->

			<div class="right">
				<p>' . $option['description'] . '</p>
			</div><!-- right -->

		</div><!-- option -->
';
					break;

				/* Color */

				case 'color':

					echo '
		<div class="option clearfix">

			<h3 class="reset">' . $option['name'] . '</h3>

			<a href="#" rel="' . $option['id'] . '" class="colorpicker-icon" title="Pick Color"></a>

			<div class="left">
				<input tabindex="2" autocomplete="off" class="colocpicker-input" type="text" name="' . $option['id'] . '" value="' . $_der->getval($option['id']) . '" />
			</div><!-- left -->

			<div class="right">
				<p>' . $option['description'] . '</p>
			</div><!-- right -->

		</div><!-- option -->
';

					break;

				/* Code Option */

				case 'code':

					$rows = ( $option['rows'] ) ? $option['rows'] : 5;

					$syntax = $option['syntax'];

					echo '
		<div class="option clearfix code-option">

			<h3 class="reset">' . $option['name'] . '</h3>

			<div class="left">

				<div class="custom">
					<p>' . $option['description'] . '</p>
				</div>

				<textarea tabindex="2" rows="' . $rows . '" name="' . $option['id'] . '">' . $_der->getval($option['id']) . '</textarea>
			</div><!-- left -->

		</div><!-- option -->
';
					break;

				case $options_array_name . '_module':

					if ($module_instance) { $module_instance->render_interface(); }

					break;

			}

		}

		if ( empty($options_array[$section]) ) { echo '<div class="option"></div><!-- option -->' . "\n"; }

		echo "\n\t" . '</div><!-- '. der_generate_id($section) . ' -->' . "\n";

	}

echo '
	<!-- end options -->

		<div class="form-bottom">
			<p>
				<input type="submit" value="Save Options" />
				<input type="hidden" name="action" value="theme_save" />
			</p>
		</div><!-- form-bottom -->

	</form><!-- options-form -->

	<form id="reset-options-form" method="post">
		<p class="reset form-reset">
			<input type="submit" class="reset-button" value="Reset Options" />
			<input type="hidden" name="action" value="theme_reset" />
		</p>

		<p>
			<input type="submit" class="reset-button" id="reset-button-bottom" value="Reset Options" />
		</p>
	</form><!-- reset-options-form -->

	<a id="back-to-top" href="#der-admin-head"></a>

</div><!-- der-admin-body -->

';

	echo '</div><!-- der-admin -->';

?>