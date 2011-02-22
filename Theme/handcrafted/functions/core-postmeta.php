<?php /* mod-postmeta */ global $d;

/* Defines */
define('DER_POST_METADATA', 'post_metabox');
define('DER_PAGE_METADATA', 'page_metabox');


/* Actions */
add_action('admin_menu', 'der_postmeta_metabox');
add_action('save_post', 'der_postmeta_save');
add_action('der_admin_styles', 'der_postmeta_styles');
add_action('der_admin_scripts', 'der_postmeta_scripts');


/* Functions */

function der_postmeta_styles() {

	if ( ( $_GET['action'] == 'edit' AND is_numeric($_GET['post']) ) OR basename($_SERVER['SCRIPT_FILENAME']) == 'post-new.php' OR basename($_SERVER['SCRIPT_FILENAME']) == 'page-new.php' ) {

		der_admin_basic_css();

		$prettyphoto_css = get_bloginfo('template_directory') . '/functions/admin/js/prettyPhoto/css/prettyPhoto.css';

		wp_enqueue_style('der-admin-prettyphoto', $prettyphoto_css );

		echo '<!-- mod-postmeta -->
<style type="text/css">
#der-post-metabox {  padding-bottom: 10px; }
#der-post-metabox .option { width: 500px; }
#der-post-metabox .option input, #der-post-metabox .option textarea { font-size: 11px; padding: 5px 10px 5px 10px; }
#der-post-metabox .option label { color: #464646; display: inline-block; font-size: 11px; font-weight: bold; margin: 15px 0 4px 2px; }
#der-post-metabox .option label:hover { cursor: text; }
#der-post-metabox .option input + label { margin: 0; padding: 0; top: 0; left: 0; font-weight: normal; }
#der-post-metabox .option input + label:hover { cursor: pointer; }
#der-post-metabox .option input[type=text] { width: 450px; }
#der-post-metabox .option small { display: block; margin: 5px 0 0 2px; font-size: 10px; width: 450px; color: #8e8e8e; }
#der-post-metabox .option textarea { width: 450px; }
#der-post-metabox .option select { width: 450px; padding: 2px; height: auto; }
#der-post-metabox .radio-option label, #der-post-metabox .radio-option small { position: relative; left: 5px; }
#der-post-metabox .radio-option p:hover { cursor: pointer; }
#der-post-metabox .option input[type=radio] { margin: -2px 10px 0 2px; }
#der-post-metabox .checkbox-option { padding: 2px 0 6px 0; }
#der-post-metabox .checkbox-option label, #der-post-metabox .checkbox-option small { position: relative; left: 5px; }
#der-post-metabox .checkbox-option input[type=checkbox] { margin: -2px 10px 0 2px; }
#der-post-metabox .metadesc p { line-height: 1.8em; font-style: italic; color: #777; font-size: 10px; }
#der-post-metabox .metadesc b { font-style: normal; color: #464646; margin-left: 5px; }
#der-post-metabox .option a.image-preview { position: relative; display: inline-block; width: 21px; height: 20px; margin: 0 0 0 0;
background: url(' . get_bloginfo('template_directory') . '/functions/admin/images/preview-image-btn.png); top: 4px; left: 3px; }

#der-post-metabox .option .buttons-container { padding: 0 0 10px 7px; }

</style>
';
		wp_enqueue_style('der-admin-prettyphoto', $prettyphoto_css );

		$colorpicker_css = get_bloginfo('template_directory') . '/functions/admin/js/colorpicker/css/colorpicker.css';

	}

}

function der_postmeta_scripts() {

	if ( ( ( $_GET['action'] == 'edit' AND is_numeric($_GET['post']) ) OR basename($_SERVER['SCRIPT_FILENAME']) == 'post-new.php' ) ) {

		der_admin_basic_js();

		$admin_prettyphoto = get_bloginfo('template_directory') . '/functions/admin/js/prettyPhoto/js/jquery.prettyPhoto.js';

		wp_enqueue_script('der-admin-prettyphoto', $admin_prettyphoto);

		echo '<!-- mod-postmeta -->
<script type="text/javascript">
wp_home = "' . get_bloginfo('home') . '";
der_upload_context = "mod-postmeta";

window.onload = function() {
	(function($) {
	$("#der-post-metabox .option .mime-image").each(function() {
		var val = $(this).val();
		if ( val.length > 0 ) {
			var extract = "";
			for ( i=0; i < wp_home.length; i++ ) {
				if ( i == val.length ) { break; }
				extract += val[i];
			}
			if ( extract != wp_home ) {
				var msg = "\
The \"" + $(this).parent().find("label").html() + "\" URL that you have specified is not located \
on your server. THUMBNAILS WILL NOT BE GENERATED for images located outside your server.\n\n\
\
To make sure the Thumbnails work, you can upload the Images from your WordPress Media Library, or by \
placing a directory with images inside your WordPress Installation.\n\n \
\
If you\'re absolutely sure that the image is on your server, then it might be a typo or maybe you included \
a www. when it\'s not needed. Look at the address bar on your browser. If you do not see any www. on your \
domain name, do not include it in the URL.";
				alert(msg);
			}
		}

	});
	})(jQuery);
}
</script>
';

	}

}

function der_postmeta_metabox() { global $registered_post_types, $d;

	add_meta_box( 'metabox', der_theme_data('Name') . ' ' . der_theme_data('Version') . ' &#151; Post Configuration', 'der_postmeta_post_metabox', 'post', 'normal', 'high' );

	if ( ! defined('DER_DISABLE_PAGE_METABOX') OR DER_DISABLE_PAGE_METABOX == false ) {

		add_meta_box( 'metabox',der_theme_data('Name') . ' ' . der_theme_data('Version') . ' &#151; Page Configuration', 'der_postmeta_page_metabox', 'page', 'normal', 'high' );

	}

	// Add Custom Post Types metaboxes

	foreach ($registered_post_types as $post_type) {

		$entry = str_replace('-', '_', $post_type) . '_metabox';

		if ( $d->$entry ) {

			$title = ucwords(str_replace('-',' ',$post_type));

			$title .= ' Entry Configuration';

			add_meta_box( 'metabox', der_theme_data('Name') . ' ' . der_theme_data('Version') . ' &#151; ' . $title , 'der_custom_post_metabox', $post_type, 'normal', 'high' );

		}

	}

}

function der_custom_post_metabox() {

	global $d, $post_type;

	$context = str_replace('-', '_', $post_type) . "_metabox";

	if ( empty($d->$context) ) { return false; }

	else { der_postmeta_structure($d->$context); }

}

function der_postmeta_post_metabox() { global $d;

	if ( ! defined('DER_POST_METADATA') ) { return false; }

	else {

		$meta_options = DER_POST_METADATA;

		$postmeta = $d->$meta_options;

	}

	der_do_action('der_postmeta_script');

	der_postmeta_structure($postmeta);

}

function der_postmeta_page_metabox() { global $d;


	if ( ! defined('DER_PAGE_METADATA') ) { return false; }

	else {

		$meta_options = DER_PAGE_METADATA;

		$postmeta = $d->$meta_options;

	}

	der_do_action('der_pagemeta_script');

	der_postmeta_structure($postmeta);

}

function der_postmeta_structure($postmeta) { global $d, $_der, $post, $post_type;

	echo '<div id="der-post-metabox">
<input type="hidden" name="der_postmeta_noncename" id="der_postmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />' . "\n";

	foreach ( $postmeta as $name => $option ) {

		switch( $option['type'] ) {

			/* Text Option */

			case 'text':

				$imgclass = ( $option['mime'] == 'image' ) ? ' class="mime-image" ' : null;

				$numeric_style = ( $option['scope'] == 'numeric' ) ? 'style="width: 60px;" ' : null;

				$value = der_postmeta($option['id']);

				if ( empty($value) AND $option['default'] ) {

					$value = $option['default'];

				}

				echo '<div class="option">

<p>
	<label>' . $name . '</label> <br/>

	<input ' . $numeric_style . $imgclass . ' type="text" autocomplete="off" tabindex="2"  name="' . $option['id'] . '" value="' . $value . '" />' . "\n";

	$button_style = ( ! der_postmeta($option['id']) ) ? 'style="display: none;"' : null;

	if ( ( $option['mime'] == 'image' OR $option['mime'] == 'video' ) ) {

		echo '<a ' . $button_style . ' href="' . der_postmeta($option['id']) . '" rel="lightbox" class="image-preview" title="Preview"></a>
			
	<div class="buttons-container">
		<button name="' . $option['id'] . '" id="post_' . $post->ID . '" class="image_upload button ">Upload Image</button>
		<button ' . $button_style . ' name="' . $option['id'] . '" id="post_' . $post->ID . '" class="image_remove button">Remove</button>
	</div><!-- .buttons-container -->';

	}

	echo '	<small>' . $option['description'] . '</small>
</p>

</div><!-- option -->
';

				break;

			/* Textarea Option */

			case 'textarea':

				$rows = ($option['rows']) ? $option['rows'] : 2;

				echo '<div class="option">

<p>
	<label>' . $name . '</label> <br/>

	<textarea name="' . $option['id'] . '" tabindex="2"  rows="' . $rows . '" >' . der_postmeta($option['id']) . '</textarea>

	<small>' . $option['description'] . '</small>
</p>

</div><!-- option -->
';

				break;

			/* Select Option */

			case 'select':

				echo '<div class="option">

<p>
	<label>' . $name . '</label> <br/>

	<select tabindex="2"  name="' . $option['id'] . '">';

				$vals = csv2array($option['values']);

				foreach ($vals as $opt) {

					$selected = $_der->meta_selected($option['id'], $opt);

					echo '	<option value="' . $opt . '" ' . $selected . '>' . $opt . '</option>' . "\n";

				}

				echo '	</select>

	<small>' . $option['description'] . '</small>
</p>

</div><!-- option -->
';

				break;

			/* Radio Option */

			case 'radio':

				echo '<div class="option radio-option">

	<label class="radio">' . $name . '</label>' . "\n";

				$vals = csv2array($option['values']);

				$default = ( der_postmeta($option['id']) ) ? false : true;

				foreach ($vals as $opt) {

					if ($default) { $checked = ($default) ? ' checked="checked" ' : null; $default = null; }

					else {

						$checked = $_der->meta_checked($option['id'], $opt);

					}

					echo '<p><input tabindex="2"  type="radio" name="' . $option['id'] . '" value="' . $opt . '" ' . $checked . ' />' . $opt . '</p>' . "\n";

				}

				echo '
	<small>' . $option['description'] . '</small>

</div><!-- option -->
';

				break;

			/* Checkbox Option */

			case 'checkbox':

				echo '<div class="option checkbox-option">

	<label class="checkbox">' . $name . '</label>' . "\n";

				$vals = $option['values'];

				if ( $option['taxonomy'] ) {

					$vals = array();

					$taxonomies = get_categories('taxonomy=' . $option['taxonomy'] . '&hide_empty=0');

					foreach ($taxonomies as $tax) { $vals[ $option['id'] . '_' . $tax->term_id ] = string2html($tax->name); }

				}

				foreach ($vals as $id => $opt) {

					$checked = $_der->meta_checked($id);

					echo '<p><input tabindex="2"  type="checkbox" name="' . $id . '"' . $checked . ' id="' . $id . '" /><label for="' . $id . '">' . $opt . '</label></p>' . "\n";

				}

				if ( empty($vals) AND $option['empty_text']) {

					echo '<p>' . $option['empty_text'] . '</p>';

				}

				echo '
	<small>' . $option['description'] . '</small>

</div><!-- option -->
';

					break;

			/* Metadesc Option */

			case 'metadesc':

				echo '<div class="option metadesc">
<br/>
<b>' . $name . '</b>
<p>' . $option['contents'] . '</p>

</div><!-- option -->
';

					break;

				break;

		}

	}

	echo '
</div><!-- der-post-metabox -->' . "\n";

}

function der_postmeta_save( $post_id ) { global $d;

	$post_type = $_POST['post_type'];

	$post_types = csv2array('post, page');

	if ( defined(CUSTOM_POST_METABOX_OPTIONS) ) {

		$post_types = array_merge($post_types, csv2array(CUSTOM_POST_METABOX_OPTIONS));
		
	}

	$post_ID = $_POST['post_ID'];

	// Authenticate wp_nonce

	if ( ! wp_verify_nonce( $_POST['der_postmeta_noncename'], plugin_basename(__FILE__) ) ) { return $post_id; }

	// Ignore Autosave

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) { return $post_id; }

	// Authenticate user

	switch($post_type) {

		case 'post':
			
			if ( ! current_user_can( 'edit_post', $post_id ) ) { return $post_id; }

			$meta_options = DER_POST_METADATA;

			$postmeta = $d->$meta_options;

			break;

		case 'page':

			if ( ! current_user_can( 'edit_page', $post_id ) ) { return $post_id; }

			$meta_options = DER_PAGE_METADATA;

			$postmeta = $d->$meta_options;

			break;

		default:

			// The 'post' is the default capability type for custom posts
			// http://codex.wordpress.org/Function_Reference/register_post_type

			if ( ! current_user_can( 'edit_post', $post_id ) ) { return $post_id; }

			$meta_options = str_replace('-', '_', $post_type) . '_metabox';

			$postmeta = $d->$meta_options;

	}

	if (! $postmeta ) { return false; }

	// Get the ID's

	$meta_ids = der_postmeta_get_ids($postmeta);

	foreach ($meta_ids as $meta ) {

		$val = $_POST[$meta];

		$val = stripslashes($val);

		$current_val = get_post_meta( $post_ID, $meta, true );

		if ( empty($val) ) {

			delete_post_meta($post_ID, $meta);

			continue;

		}

		if ( empty($current_val) ) {

			add_post_meta( $post_ID, $meta, $val );

		} else {

			update_post_meta($post_ID, $meta, $val);

		}

	}

}

function der_postmeta_get_ids($postmeta) {

	$meta_ids = array();

	foreach ($postmeta as $name => $option) {

		switch( $option['type'] ) {

			case 'metadesc':

				break;

			case 'text':
			case 'textarea':
			case 'select':
			case 'radio':

				$meta_ids[] = $option['id'];

				break;

			case 'checkbox':

				if ( $option['taxonomy'] ) {

					$vals = array();

					$taxonomies = get_categories('taxonomy=' . $option['taxonomy'] . '&hide_empty=0');

					foreach ($taxonomies as $tax) { $vals[ $option['id'] . '_' . $tax->term_id ] = string2html($tax->name); }

					foreach ($vals as $id => $desc) { $meta_ids[] = $id; }

				} else {

					foreach ($option['values'] as $id => $desc) { $meta_ids[] = $id; }

				}

				break;

		}

	}

	return $meta_ids;

}
?>