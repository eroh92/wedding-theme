<?php

// Actions
add_action('login_head', 'der_login_head');
add_action('der_admin_actions', 'der_check_for_updates');


// Functions

function der_options_page() { global $_der;

	$_der->set_options_context(null); // Make sure context is null for the main options page

	include(TEMPLATEPATH . '/functions/admin/admin-interface.php');

}

function der_admin_menu() {

	add_object_page('Page Title', DER_OPTIONS_MENU_TITLE , 8, 'theme_options', 'der_options_page', get_bloginfo('template_url') . '/core/images/branding/admin-icon.png');

	add_submenu_page('theme_options', 'Theme Options', 'Theme Options', 8, 'theme_options', 'der_options_page');

	der_do_action('der_admin_menu');

}

function der_admin_styles() {

	der_admin_basic_css();

	$prettyphoto_css = get_bloginfo('template_directory') . '/functions/admin/js/prettyPhoto/css/prettyPhoto.css';

	wp_enqueue_style('der-admin-prettyphoto', $prettyphoto_css );

	$colorpicker_css = get_bloginfo('template_directory') . '/functions/admin/js/colorpicker/css/colorpicker.css';

	wp_enqueue_style('der-admin-colorpicker', $colorpicker_css);

	der_do_action('der_admin_styles');

}

function der_admin_scripts() {

	// Export template_directory to JavaScript

	echo
'<script type="text/javascript">
template_directory="' . get_bloginfo('template_directory') . '";
</script>' . "\n";

	der_admin_basic_js();

	$admin_prettyphoto = get_bloginfo('template_directory') . '/functions/admin/js/prettyPhoto/js/jquery.prettyPhoto.js';

	wp_enqueue_script('der-admin-prettyphoto', $admin_prettyphoto);

	$admin_colorpicker = get_bloginfo('template_directory') . '/functions/admin/js/colorpicker/js/colorpicker.js';

	wp_enqueue_script('der-admin-colorpicker', $admin_colorpicker);

	der_do_action('der_admin_scripts');

}

function der_admin_basic_css() {

	$admin_css = get_bloginfo('template_directory') . '/functions/admin/css/admin.css';

	wp_enqueue_style('der-admin', $admin_css );

}

function der_admin_basic_js() {

	wp_enqueue_script('jquery');	// Included with WordPress

	wp_enqueue_script('jquery-form');	// Included with WordPress

	$admin_js = get_bloginfo('template_directory') . '/functions/admin/js/admin.js';

	wp_enqueue_script('der-admin', $admin_js);

	$admin_js_ajax = get_bloginfo('template_directory') . '/functions/admin/js/admin-ajax.js';

	wp_enqueue_script('der-admin-ajax', $admin_js_ajax);

	$admin_easing = get_bloginfo('template_directory') . '/functions/admin/js/jquery.easing.js';

	wp_enqueue_script('der-admin-easing', $admin_easing);

	$admin_cookie = get_bloginfo('template_directory') . '/functions/admin/js/jquery.c00kie.js'; // prevent "HTTP/406 Not Acceptable" Error on restrictive servers

	wp_enqueue_script('der-admin-cookie', $admin_cookie);

	$admin_ajaxupload = get_bloginfo('template_directory') . '/functions/admin/js/ajaxupload.js';

	wp_enqueue_script('der-admin-ajaxupload', $admin_ajaxupload);

	$admin_tabby = get_bloginfo('template_directory') . '/functions/admin/js/jquery.textarea.js';

	wp_enqueue_script('der-admin-bespin', $admin_tabby);

}

function der_update_admin_options() { global $d;

	$updated_options = array();

	// Adjust data accordingly for the type of Request

	if ( isset( $_POST['ajax'] ) ) {

		$DATA = $_POST;

		// Update $_GET['page'] on AJAX Request

		$referrer = $_SERVER['HTTP_REFERER'];

		$page = explode('?page=', $referrer);

		$page = $page[1];

		$_GET['page'] = $page;

	} else {

		$DATA = $_REQUEST;
		
	}

	switch ( $DATA['action'] ) {

		case 'theme_save':

			der_do_action('der_theme_save', $DATA);

			/* Continue updating the options, if no break is detected.
			 * The breaks are set by the functions hooked up to the
			 * 'der_theme_save' action. */

			if ( ! defined('DER_THEME_SAVE_BREAK') ) {

				$default_options = der_get_default_options();

				foreach ( $DATA as $key => $val ) {

					if ( $key == 'page' OR $key == 'action' OR $key == 'ajax' ) { continue; }

					// Only save option if there's a default value for it

					if ( empty( $DATA[$key] ) && $default_options[$key] ) {

						$updated_options[$key] = stripslashes( $default_options[$key] );

					} else {

						$updated_options[$key] = stripslashes( $DATA[$key] );

					}

				}

				update_option(DER_OPTIONS_DB_ENTRY, $updated_options);

			}

			if ( empty( $_POST['ajax'] ) ) {

				// Redirect non-AJAX Requests
				
				$redirect = 'Location: ' . admin_url() . 'admin.php?page=' . $_REQUEST['page'] . '&saved=1';

				header($redirect);	// Redirect to drop POST data
				
			} else {

				// AJAX Success Message

				echo "Settings saved.";

			}

			break;

		case 'theme_reset':

			der_do_action('der_theme_reset');

			/* Continue updating the options, if no break is detected.
			 * The breaks are set by the functions hooked up to the
			 * 'der_theme_reset' action. */

			if ( ! defined('DER_THEME_RESET_BREAK') ) {

				// Save the (empty) $updated_options to the database

				update_option(DER_OPTIONS_DB_ENTRY, $updated_options);
				
			}

			if ( empty( $_POST['ajax'] ) ) {

				// Redirect non-AJAX Requests

				$redirect = 'Location: ' . admin_url() . 'admin.php?page=' . $_REQUEST['page'] . '&reset=1';

				header($redirect);	// Redirect to drop POST data

			} else {

				// AJAX Success Message

				echo "Settings reset.";

			}

			break;

		default:

			// On init, retrieve the theme options and set its defaults

			$GLOBALS['_der'] = new DerThemeOptions(DER_OPTIONS_DB_ENTRY);

			der_do_action('der_admin_init');

			//include(TEMPLATEPATH . '/includes/admin-init.php');

			break;

	}

}

function der_get_default_options() {

	global $d;

	$default_options = array();

	foreach ( $d->options as $section => $null ) {

		foreach ( $d->options[$section] as $option ) {

			if ( $option['type'] == 'checkbox' ) { continue; }

			if ( preg_match('/,/', $option['fields'] ) ) {

				$vals = explode(',', $option['fields']);

				$default_options[ $option['id'] ] = trim( $vals[0] );

			} elseif ( $option['default'] ) {

				$default_options[ $option['id'] ] = $option['default'];

			}

		}

	}

	return $default_options;

}

function der_admin_postmsg() {

	if ( $_REQUEST['saved'] == '1' AND empty( $_REQUEST['ajax'] ) ) {

		echo '<div class="updated fade" id="message"><p><strong>Settings saved.</strong></p></div>';

	} elseif ( $_REQUEST['reset'] == '1' AND empty( $_REQUEST['ajax'] ) ) {

		echo '<div class="updated fade" id="message"><p><strong>Settings reset.</strong></p></div>';

	}

}

function der_get_all_page_ids() {

	if ( $GLOBALS['DER_ALL_PAGE_IDS'] ) { return $GLOBALS['DER_ALL_PAGE_IDS']; }

	$pages = get_pages();

	$ids = array();

	foreach ($pages as $p) {

		$ids[] = $p->ID;

	}

	$GLOBALS['DER_ALL_PAGE_IDS'] = $ids;

	return $ids;

}

function der_load_template() { global $d, $_der, $post;

	if ( is_single() OR is_page() ) { the_post(); }

	der_do_action('der_load_template');

	// Exit if it's not a page or a category (nothing to redirect)

	if ( ! is_page() AND ! is_category() ) { return false; }

	// Get templates defined in options

	$templates = array();

	foreach ( $d->options as $section => $null ) {

		foreach ( $d->options[$section] as $option ) {

			if ( $option['type'] == 'page' AND $option['template'] ) {

				$templates[$option['id']] = $option['template'];

			}

		}

	}

	// Determine if $post is a template

	foreach ( $templates as $id => $template_filename ) {

		if ( $_der->equal($id, $post->ID) ) {

			$template_filename = apply_filters('der_template_name', $template_filename);

			define('DER_PAGE_TEMPLATE', $template_filename);

			include(TEMPLATEPATH . '/' . $template_filename);

			get_footer();

			exit(); // Prevent page.php from being included

		}

	}

	// Load Category Redirected Templates

	if ( is_category() ) { 
		
		$templates = array();

		foreach ( $d->options as $section => $null ) {

			foreach ( $d->options[$section] as $option ) {

				if ( $option['type'] == 'category' AND $option['template'] ) {

					$templates[$option['id']]  = $option['template'];

				}

			}

		}

		// Determine if category needs to be redirected

		foreach ( $templates as $id => $template_filename ) {

			$cat = get_query_var('cat');

			if ( $_der->equal($id, $cat) ) {

				$template_filename = apply_filters('der_template_name', $template_filename);

				define('DER_PAGE_TEMPLATE', $template_filename);

				include(TEMPLATEPATH . '/' . $template_filename);

				get_footer();

				exit(); // Prevent category.php from being included

			}

		}
		
	}

}

function der_get_color_stylesheets() {

	$p = TEMPLATEPATH . '/core/styles/';

	$stylesheets = array();

	foreach ( der_compat_scandir($p) as $file ) {

		if ( $file[0] == '.' ) { continue; }

		$files[] = $file;

		$path = $p . '/' . $file;

		if ( is_dir($path) ) {

			$stylesheets[] = $file . '/' . $file . '.css';

		} else {

			$pathinfo = pathinfo($path);

			if ( $pathinfo['extension'] == 'css' ) { $stylesheets[] = $file; }

		}
		
	}

	return $stylesheets;

}

function der_admin_metalinks() { global $d;

	if ( empty($d->metalinks) ) { return false; }

	$html = "\n" . '	<ul class="reset meta-links">' . "\n";

	$metalinks = $d->metalinks;

	foreach ( $metalinks as $section => $options ) {

		$target = $options['target'];

		$target = ($target) ? 'target="' . $target . '" ' : null;

		$html .= '		<li><a ' . $target . ' style="background-image: url('. get_bloginfo('template_directory') . '/core/images/branding/' . $options['icon'] .');" href="' . $options['url'] . '">' . $section . '</a></li>' . "\n";

	}

	$html .= '	</ul><!-- meta -->';

	echo $html;
}

function der_ajax_upload() {

	switch ($_POST['type']) {

		case 'upload':

			$optionID = $_POST['data'];
			$context = $_POST['context'];
			$post_ID = $_POST['post_ID'];
			$options_context = $_POST['options_context'];

			$filename = $_FILES[$optionID];
			$filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']);

			// Handle File Upload
			$overrides['test_form'] = false;
			$overrides['action'] = 'wp_handle_upload';
			$uploaded_file = wp_handle_upload($filename,$overrides);

			// Response
			if( !empty($uploaded_file['error']) ) { echo 'Upload Error: ' . $uploaded_file['error']; }
			else {
				$url = $uploaded_file['url'];

				if ( $context == 'admin-interface' ) {

					// Update Theme Options
					$db_entry = ( $options_context ) ? $options_context : DER_OPTIONS_DB_ENTRY;
					$options = get_option($db_entry);
					$options[$optionID] = $url;
					update_option($db_entry, $options);

				} elseif ($context == 'mod-postmeta') {

					// Update Post Meta
					update_post_meta($post_ID, $optionID, $url);

				}

				// Return URL to be inserted in <input>
				echo $url;
				
			} // Response

			break;

		case 'image_remove':

			$optionID = $_POST['data'];
			$context = $_POST['context'];
			$post_ID = $_POST['post_ID'];
			$options_context = $_POST['options_context'];

			if ( $context == 'admin-interface' ) {

				// Remove Image URL from Theme Option
				$db_entry = ( $options_context ) ? $options_context : DER_OPTIONS_DB_ENTRY;
				$options = get_option($db_entry);
				unset($options[$optionID]);
				update_option($db_entry, $options);

			} elseif ( $context == 'mod-postmeta' ) {

				// Remove Image URL from Post Meta
				delete_post_meta($post_ID, $optionID);

			}

			break;


		case 'new_page':

			$page_title = $_POST['page_title'];
			$options_context = $_POST['options_context'];
			$optionID = $_POST['optionID'];

			// Create new Page
			$page_id = wp_insert_post(array(
				'post_type' => 'page',
				'post_title' => $page_title,
				'post_status' => 'publish'
			));

			// Update Theme Options
			$db_entry = ( $options_context ) ? $options_context : DER_OPTIONS_DB_ENTRY;
			$options = get_option($db_entry);
			$options[$optionID] = $page_id;
			update_option($db_entry, $options);

			// Return Page ID
			echo $page_id;

			break;

	}

	exit();
}

function der_login_head() {

	$style = '<style type="text/css">h1 a { background-image: url(' . get_bloginfo('template_directory') . '/core/images/branding/logo-login.gif); }</style>';

	$style = apply_filters('der_login_image', $style);

	echo $style . "\n";

}

function der_check_for_updates() {

	switch($_POST['type']) {

		case 'check_for_updates':

			$name = der_theme_data('Name');

			$name = str_replace(' ', '+', $name);

			$data = file_get_contents(DER_UPDATE_CHECK_SERVER . '/?themedata=' . $name );

			echo $data;

			break;

	}

}

?>
