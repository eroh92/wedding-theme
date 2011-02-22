<?php

function der_initialize() {

	do_action('der_initialize');

	der_on_activate();

	include(TEMPLATEPATH . '/functions/core-helpers.php');
	include(TEMPLATEPATH . '/functions/core-classes.php');
	include(TEMPLATEPATH . '/includes/theme-config.php');
	include(TEMPLATEPATH . '/functions/core-pagination.php');
	include(TEMPLATEPATH . '/functions/core-postmeta.php');
	include(TEMPLATEPATH . '/includes/theme-custom.php');
	include(TEMPLATEPATH . '/functions/admin/admin-functions.php');
	include(TEMPLATEPATH . '/includes/theme-options.php');

	$GLOBALS['_der'] = new DerThemeOptions();
	$GLOBALS['registered_modules'] = array();
	$GLOBALS['registered_post_types'] = array();
	$GLOBALS['prevent_redirect'] = array();

	include(TEMPLATEPATH . '/includes/theme-functions.php');
	include(TEMPLATEPATH . '/includes/theme-shortcodes.php');
	include(TEMPLATEPATH . '/includes/theme-queries.php');

	if ( defined('DER_FRAMEWORK_MODULES') ) { der_load_modules(DER_FRAMEWORK_MODULES); }

	add_action('init', 'der_init');
	add_action('wp_head', 'der_theme_head');
	add_action('admin_menu', 'der_admin_menu' );
	add_action('admin_menu', 'der_update_admin_options' );
	add_action('admin_print_styles', 'der_admin_styles');
	add_action('admin_print_scripts', 'der_admin_scripts');
	add_action('get_header', 'der_header_hook');
	add_action('pre_get_posts', 'der_prevent_paged_404');
	add_action('wp_ajax_der_ajax_upload', 'der_ajax_upload');
	add_action('wp_ajax_der_admin_actions', 'der_admin_actions');
	add_action('der_theme_init', 'der_redirect'); // redirects when post is accessed

	add_filter('the_excerpt', 'der_excerpt');
	add_filter('excerpt_length', 'der_excerpt_length');
	add_filter('excerpt_more', 'der_excerpt_more');
	add_filter('the_permalink', 'der_permalink_redirect'); // shows the redirected URL instead of the post's URL
	add_filter('post_class', 'remove_post_class_duplicates');
	add_filter('redirect_canonical', 'prevent_post_type_redirection', 10, 2);

}

function pre($data, $exit=true ) {

	if ( !is_admin() ) {

		echo '<pre>';

		print_r($data);

		echo '</pre>';

		if ($exit) { exit(); }

	}
}

function _pre($data, $exit=true ) {

	if ( is_admin() ) {

		echo '<pre>';

		print_r($data);

		echo '</pre>';

		if ($exit) { exit(); }

	}
}

function der_do_action($hook, $data=null) {

	if ($data) {

		do_action($hook, $data);

		modules_action($hook, $data);

	} else {

		do_action($hook);

		modules_action($hook);

	}

}

function modules_action($action, $data=null) { global $registered_modules;

	foreach ($registered_modules as $module) {

		if ($data) { $module->run_action($action, $data); }

		else { $module->run_action($action); }

	}

}

function der_init() { global $registered_modules, $_der;

	// Initialization routine

	der_set_theme_defines();

	remove_action('wp_head', 'wp_generator');

	der_do_action('der_init');


	// Register module defaults

	foreach ($registered_modules as $module) {

		$defaults = $module->get_default_options();

		if ($defaults) { $_der->extra_default_options[$module->default_db_entry] = $defaults; }

	}


	// Register Sidebars

	der_register_sidebars();


	// Register Navigation Menus

	der_register_nav_menus();

}

function der_theme_head() {

	if ( ! is_admin() ) {

		der_do_action('der_theme_styles');

		der_do_action('der_theme_scripts');

	}

}

function der_set_theme_defines() {

	$homepage_enabled = (get_option('show_on_front') == 'page' && get_option('page_on_front') != 0) ? false : true;

	define('DER_HOMEPAGE_ENABLED', $homepage_enabled);

	der_do_action('der_theme_defines');

}

function der_theme_data($field=null) {

	$stylesheet = get_stylesheet_directory() . '/style.css';

	$theme_data = get_theme_data($stylesheet);

	if ($field) {

		return $theme_data[$field];

	} else {

		return $theme_data;

	}

}

function der_generate_id($string) {

	preg_match_all('/[a-zA-Z0-9 ]/', $string, $matches);

	$string = implode('', $matches[0]);

	$string = preg_replace('/\s+/', '-', $string);

	$string = strtolower($string);

	if ( is_numeric($string[0]) ) { $string = 's' . $string; }

	return $string;

}

function permalinks_enabled() {

	return ( get_option('permalink_structure') ) ? true : false;

}

function csv2array ($string) {

	if ( empty($string) ) { return array(); }

	$vals = explode(',', $string);

	for ( $i=0; $i < count($vals); $i++ ) {

		$vals[$i] = trim($vals[$i]);

	}

	return $vals;

}

function csv2pages($string) {

	$vals = csv2array($string);

	$page_ids = array();

	foreach ( $vals as $id ) {

		$page = ( is_numeric($id) ) ? get_page($id) : get_page_by_title($id);

		if ( $page ) { $page_ids[] = $page->ID; }

	}

	return array_unique( $page_ids );

}

function csv2cats($string, $taxonomy=null) {

	$vals = csv2array($string);

	$ids = array();

	foreach ( $vals as $id ) {

		if ( $taxonomy == null ) {

			// Default to categories

			$cat = ( is_numeric($id) ) ? get_category($id) : der_get_category_by_name($id);

			if ( $cat ) { $ids[] = $cat->term_id; }

		} else {

			// Use custom taxonomy

			$term = ( is_numeric($id) ) ? get_term($id, 'portfolio-category') : get_term_by('name', $id, $taxonomy);

			if ( $term AND $term->count > 0 ) { $ids[] = $term->term_id; }

		}

	}

	return array_unique( $ids );

}

function der_clean_array(&$array) {

	$new = array();

	foreach ( $array as $key => $val ) {

		if ( $val ) { $new[$key] = $val; }

	}

	$new = array_unique($new);

	$array = $new;

}

function der_get_category_by_name($catname) {

		$cat = get_term_by( 'name', $catname, 'category' );

		if ($cat) { _make_cat_compat($cat); }

		return $cat;

}

function der_load_modules($string) {

	der_register_theme_defines();

	$modules = csv2array($string);

	$errors = array();

	foreach ( $modules as $module ) {

		$modfile = TEMPLATEPATH . '/includes/modules/mod-' . $module . '.php';

		if ( file_exists($modfile) ) {

			include($modfile);

		} else {

			$errors[] = 'Unable to find module: ' . $module . "\n";

		}

	}

	if ( !empty($errors) ) {

		echo "<pre>\n";

		foreach ($errors as $error) { echo $error; }

		echo '</pre>';

		exit();
	}

	do_action('der_load_modules');

}

function der_register_theme_defines() { global $d, $_der;

	foreach ( $d->options as $section => $null ) {

		foreach ( $d->options[$section] as $option ) {

			switch ( $option['type'] ) {

				case 'text':
				case 'select':

					$define = $option['define'];

					$val = $_der->getval($option['id']);

					if ( empty($define) OR empty($val) ) { break; }

					$val = str_replace('"', '\"', $val);

					if ( $val == 'enabled' OR $val == 'on' ) {

						$val = true;

					} elseif ( $val == 'disabled' OR $val == 'off' ) {

						$val = false;

					}

					define($define, $val);

					break;

			}

		}

	}

}

function der_module($modname, $args=null) {

	$callback = $modname;

	if ( ! function_exists($callback) ) { return false; }

	if ( $args ) {

		$callback($args);

	} else {

		$callback();

	}

}

function t($string) {

	if ( function_exists('mod_translate_translate') ) {

		$after = str_replace(rtrim($string), '', $string);

		$before = str_replace(ltrim($string), '', $string);

		$s = mod_translate_translate($string, $before, $after);

		return ($s) ? $s : $string . $after;

	} else {

		return $string . $after;

	}

}

function _t($string) {

	echo t($string);

}

function der_postmeta($metavals, $multiple=false) { global $post;

	$post_id = ( is_numeric($post) ) ? $post : $post->ID;

	if ( $multiple ) {

		// Retrieve multiple ID's based on the naming convention:

		$meta = $metavals;

		$data = get_post_meta($post->ID, null);

		$values = array();

		foreach ($data as $key => $val ) { $val = strtolower($val[0]);

			if ( preg_match("/^${meta}_(\d+)$/", $key ) AND $val == 'on' ) {

				$values[] = (int) str_replace("${meta}_", '', $key);

			}
		}

		return $values;

	} else {

		// Normal behavior for post meta

		$metavals = csv2array($metavals);

		foreach ( $metavals as $meta ) {

			$data = get_post_meta($post_id, $meta, true );

			if ( $data ) { return $data; }

		}

	}

	return null;

}

function _der_postmeta($meta) {

	echo der_postmeta($meta);

}

function der_link($name, $url, $target='', $title='') {

	$target = ($target) ? "target=\"$target\"" : null;

	return "<a $target title=\"$title\" href=\"$url\">$name</a>";

}

function _der_link($name, $url, $target='', $title='') {

	echo der_link($name, $url);

}

function der_sidebar($names, $callback=null) { global $_der;

	$sidebars = csv2array($names);

	foreach ($sidebars as $name) {

		dynamic_sidebar($name);

	}

}

function der_register_sidebars() {

	if ( ! function_exists('register_sidebar') OR ! defined('DER_SIDEBARS') ) { return false; }

	$sidebars = csv2array(DER_SIDEBARS);

	$theme_name = der_theme_data('Name');

	for ( $i=0; $i < count($sidebars); $i++ ) {

		$name = $sidebars[$i];

		$before_widget = "<!-- widget -->\n" . '<div id="%1$s" class="widget %2$s">';

		$after_widget = "\n" . '</div><!-- widget -->' . "\n\n";

		$before_title = "\n" . '<h2 class="title">';

		$after_title = '</h2>' . "\n";

		register_sidebar(array(
			'name'			=> $name,
			'id'			=> 'sidebar-' . $i,
			'description'	=> $name . ' Sidebar',
			'before_widget'	=> $before_widget,
			'after_widget'	=> $after_widget,
			'before_title'	=> $before_title,
			'after_title'	=> $after_title )
		);

	}
}

function der_register_nav_menus() {

	if ( ! function_exists('register_nav_menu') OR ! defined('DER_NAV_MENUS') ) { return false; }

	$menus = csv2array(DER_NAV_MENUS);

	foreach ( $menus as $menu_desc) {

		$menu_id = preg_replace('/\s+/', '_', strtolower(trim($menu_desc)));

		register_nav_menu($menu_id, $menu_desc);

	}

}

define( 'DER_HOME', get_bloginfo('home') );

define ( 'DER_UPLOAD_PATH', get_option('upload_path') );

function url($url) {

	$url =  trim($url);

	$home = DER_HOME;

	$home .= ( ! preg_match('/\/$/', $home) ) ? '/' : null;

	$home_len = strlen($home);

	$extract = '';

	for ( $i=0; $i < strlen($url); $i++ ) {

		if ( $i == $home_len ) { break; }

		$extract .= $url[$i];

	}

	if ( $extract != $home ) { return $url; }

	if ( is_multisite() ) {

		if ( preg_match('/\/wp-content\/themes\//', $url) ) {

			$url = strstr($url, '/wp-content');

			return $url;

		}

		if ( ! preg_match('/\/files\/(\d+)\/(\d+)\//', $url) ) { return $url; } // not in server

		// (WPMU) Return a relative path

		$url = strstr($url, '/files/');

		$url = str_replace('/files/', UPLOADS, $url);

		return $url;

	} else {

		// (WP) Return a relative path

		return str_replace($home, '', $url);

	}

}

function thumb_src($image, $w, $h=0) { global $_der;

	if ( $_der->checked('disable_timthumb') ) { return $image; }

	$img_src = url($image);

	/* If the url contains 'http://', it's not in the server and
	 * therefore not supported by timthumb. Return the original image */

	if ( preg_match('/^http\:\/\//', $img_src ) ) { return $image; }

	$src = get_bloginfo('template_directory') . '/functions/scripts/timthumb.php';

	$src .= '?src=' . $img_src . '&amp;w=' . $w . '&amp;h=' . $h . '&amp;zc=1';

	return $src;

}

function _thumb_src($image, $w, $h=0) {

	echo thumb_src($image, $w, $h);

}

function der_compat_scandir($dir) {

	/* Compatibility function to handle scandir(), which was
	 * implemented on PHP5 */

	if ( function_exists('scandir') ) {

		return scandir($dir);

	} else {

		$dh  = opendir($dir);

		while ( false !== ( $filename = readdir($dh) ) ) {

			$files[] = $filename;

		}

		sort($files);

		return $files;

	}

}

function post_thumb($metavals, $w, $h=0, $placeholder=null) {

	if ( $metavals == null ) { return false; }

	$metavals = csv2array($metavals);

	foreach ( $metavals as $meta ) {

		$img = der_postmeta($meta);

		if ( $img ) {

			return thumb_src($img, $w, $h);

		}

	}

	if ( $placeholder ) {

		return thumb_src($placeholder, $w, $h);

	}

}

function _post_thumb($meta, $w, $h=0, $placeholder=null) {

	echo post_thumb($meta, $w, $h, $placeholder);

}

function der_excerpt($data=null) { global $post;

	if ( $post->post_excerpt ) { return $data; }

	if ( preg_match('/<!--more(.*?)?-->/', $post->post_content ) ) {

		// More tag present

		$GLOBALS['more'] = false;

		$content = get_the_content('', 0);

		$content = apply_filters('the_content', $content);

		$content = str_replace(']]>', ']]&gt;', $content);

		return $content;

	} else {

		// More tag not present [...]

		$data = apply_filters('the_content', $data);

		return $data;

	}

}

function der_excerpt_length($data) { global $_der;

	return $_der->getval('excerpt_length');

}

function der_excerpt_more($data) { global $_der;

	return ' ' . trim($_der->getval('excerpt_more'));

}

function der_paged_fix() {

	/* WP $paged fix */

	global $wp_query, $paged;

	$paged = $wp_query->query['paged'];

	$paged = ( $paged ) ? $paged : 1;

}

function der_header_hook() { global $wp_query, $paged;

	der_paged_fix();

	include(TEMPLATEPATH . '/includes/theme-debug.php');

	include(TEMPLATEPATH . '/includes/theme-header.php');

	der_do_action('der_theme_init');

}

function der_month_link($month_format=null, $uppercase=false) { global $post;

	$month_url = get_month_link( get_the_time('Y'), get_the_time('n') );

	if ( $post->post_type != 'post' and $post->post_type != 'page' ) {

		$month_url = add_url_vars($month_url, array('post_type' => $post->post_type));

	}

	$month_format = ( $month_format ) ? $month_format : 'F';

	$month_name = get_the_time($month_format);

	$month_name = ( $uppercase ) ? strtoupper($month_name) : $month_name;

	$link = '<a href="' . $month_url . '">' . $month_name . '</a>';

	return $link;

}

function der_day_link($day_format=null, $uppercase=false) { global $post;

	$day_url = get_day_link( get_the_time('Y'), get_the_time('n'), get_the_time('j') );

	if ( $post->post_type != 'post' and $post->post_type != 'page' ) {

		$day_url = add_url_vars($day_url, array('post_type' => $post->post_type));

	}

	$day_format = ( $day_format ) ? $day_format : 'jS';

	$day_string = get_the_time($day_format);

	$day_string = ( $uppercase ) ? strtoupper($day_string) : $day_string;

	$link = '<a href="' . $day_url . '">' . $day_string . '</a>';

	return $link;

}

function der_year_link($year_format=null) { global $post;

	$year_url = get_year_link( get_the_time('Y') );

	if ( $post->post_type != 'post' and $post->post_type != 'page' ) {

		$year_url = add_url_vars($year_url, array('post_type' => $post->post_type));

	}

	$year_format = ( $year_format ) ? $year_format : 'Y';

	$year_num = get_the_time($year_format);

	$link = '<a href="' . $year_url . '">' . $year_num . '</a>';

	return $link;

}

function der_author_link() { global $post;

	$link = get_author_link(false, $post->post_author);

	if ( $post->post_type != 'post' and $post->post_type != 'page' ) {

		$link = add_url_vars($link, array('post_type' => $post->post_type));

	}

	return $link;

}

function der_get_core_categories() { global $_der;

	if ( defined('DER_CORE_CATEGORIES') AND DER_CORE_CATEGORIES ) {

		$cats = csv2array(DER_CORE_CATEGORIES);

		$core_cats = array();

		foreach ( $cats as $opt ) {

			if ( preg_match('/(.+)\>children$/', $opt, $matches) ) {

				$parent = $_der->getval($matches[1]);

				$core_cats[] = $parent;

				$child_cats = get_term_children($parent, 'category');

				foreach ( $child_cats as $cat ) {

					$core_cats[] = $cat;

				}

			} else {

				$core_cats[] = $_der->getval($opt);

			}

		}

		der_clean_array($core_cats);

		$core_cats = apply_filters('der_get_core_cats', $core_cats);

		der_clean_array($core_cats);

		return $core_cats;

	}

}

function der_theme_stylesheet() { global $_der;

	$stylesheet = $_der->getval('theme_stylesheet');

	if ( defined('DER_THEME_STYLESHEET') ) { $stylesheet = DER_THEME_STYLESHEET; }

	echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_directory') . '/core/styles/' . $stylesheet . '" />' . "\n";

}

function der_get_all_page_templates() {

	$page_ids = der_get_all_page_ids();

	$page_templates = array();

	foreach ( $page_ids as $page_id ) {

		$page_template = get_post_meta($page_id, '_wp_page_template', true);

		if ( $page_template != 'default' ) {

			$page_templates[$page_id] = $page_template;

		}

	}

	return $page_templates;

}

function der_prevent_paged_404() {

	/* The framework uses its own Posts per Page settings, which generate different pagination patterns,
	 * affecting the $paged variable, which makes WP_Query trigger a 404 page if the page requested
	 * by the $paged variable does not exist, since WordPress uses the original posts_per_page setting
	 * to calculate the pagination. */

	if ( !defined('DER_PREVENTED_PAGED_404') AND ! is_admin() ) {

		define('DER_PREVENTED_PAGED_404', true);

		set_query_var('showposts', '1');

	}

}

function der_sendmsg($recipient_email, $disable_mailer=false) {

	extract($_POST);	// returns:  name, email, subject, message

	if (count($_POST) > 0 ) {

		require( TEMPLATEPATH . '/functions/scripts/phpmailer.php' );
		$mail = new PHPMailer();

		$mail->From = $email;
		$mail->FromName = $name;
		$mail->AddAddress($recipient_email,"");

		$mail->WordWrap = 60;
		$mail->IsHTML(false);
		$mail->CharSet = "UTF-8";

		$mail->Subject  =  "[ " . get_bloginfo('name') . " ] New Message: " .  $subject;
		$mail->Body     =  $message;

		if ( ( $disable_mailer ) ? true : $mail->Send() ) {

			if ( $ajax == 'true' ) {

				echo 'success';

			} else {

				$home = get_bloginfo('home');

				header('Location: ' . $home);

			}

		} else {

			echo 'error';

		}

	}

}

function set_custom_post_metabox_titles($array) {

	define( 'CUSTOM_POST_METABOX_TITLES', serialize($array) );

}

function der_custom_post_taxonomies_links() { global $post, $post_type;

	$tags = get_the_terms($post->ID, 'slideshow-tags');

	if ( empty($tags) ) { echo __('No Tags'); return; }

	$links = array();

	foreach ($tags as $tag) {

		$links[] = '<a href="' . admin_url() . 'edit.php?post_type=' . $post_type . '&taxonomy_name=' . $tag->slug . '">' . $tag->name . '</a>';

	}

	echo implode(', ', $links);

}

function der_show_admin_category_links($csv, $post_type=null) {

	$post_type = ( $post_type ) ? '&post_type=' . $post_type : null;

	$cats_array = csv2array($csv);

	$links = array();

	foreach ($cats_array as $cat_name) {

		$cat = der_get_category_by_name($cat_name);

		if ( $cat ) {

			$links[] = '<a href="' . admin_url() . 'edit.php?category_name=' . $cat->slug . $post_type . '">' . $cat_name . '</a>';

		} else {

			$links[] = $cat_name;

		}


	}

	echo implode(', ', $links);

}

function der_show_admin_taxonomy_links($post_id, $taxonomy, $post_type, $empty_text=null) {

	$terms = get_the_terms($post_id, $taxonomy);

	if ( empty($terms) ) { echo $empty_text; return; }

	$terms = ( $terms ) ? $terms : array();

	$links = array();

	foreach ( $terms as $term ) {

		$links[] = '<a href="' . admin_url() . 'edit.php?post_type=' . $post_type . '&taxonomy=' . $taxonomy . '&' . $taxonomy . '=' . $term->slug . '">' . string2html($term->name) . '</a>';

	}

	echo implode(', ', $links);

}

function der_get_cufon_fonts() {

	$files = der_compat_scandir(TEMPLATEPATH . '/core/fonts');

	$fonts = array();

	foreach ( $files as $file ) {

		if ( preg_match('/\.js$/', $file) ) { $fonts[] = $file; }

	}

	return $fonts;

}

function der_do_robots() {

	echo ( ( is_single() || is_page() || is_home() ) && ( !is_paged() ) ) ? '<meta name="robots" content="index, follow" />' : '<meta name="robots" content="noindex, follow" />';

	echo "\n";

}


function der_add_favicon($option, $fallback=null) { global $_der;

	$path = $_der->getval($option);

	if ( empty($path) AND empty($fallback) ) { return false; }

	$path = ($path) ? $path : get_bloginfo('template_directory') . '/' . $fallback;

	$info = pathinfo($path);

	$extension = strtolower($info['extension']);

	switch ($extension) {
		case 'jpg':
		case 'jpeg':
			$mime = 'image/jpeg';
			break;
		case 'gif':
			$mime = 'image/gif';
			break;
		case 'png':
			$mime = 'image/png';
			break;
		case 'ico':
			$mime = 'image/x-icon';
			break;
		default:
			$mime = 'image/x-icon';
			break;
	}

	echo "\n" . '<link rel="shortcut icon" type="' . $mime . '" href="' . $path . '" />' . "\n";

}

function der_load_stylesheets($string) {

	$array = csv2array($string);

	foreach ($array as $stylesheet) {

		echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_directory') . '/' . $stylesheet . '" />' . "\n";

	}

//	echo "\n";

}

function der_redirect() {

	if ( is_single() OR is_page() ) {

		$redirect_url = der_postmeta('redirect_url');

		if ( $redirect_url ) { header("Location: $redirect_url"); }

	}

}

function der_wp_nav_menu_enabled($theme_location) {

	if ( ! function_exists('register_nav_menu') ) { return false; } // Return false if WP < 3.0

	$menu_locations = get_nav_menu_locations();

	return ! (empty($menu_locations[$theme_location]) );

}

function der_permalink_redirect($data) { global $post;

	$redirect_url = der_postmeta('redirect_url');

	return ( $redirect_url ) ? $redirect_url : $data;

}

function der_add_post_type($slug, $menu_title, $singular_name, $description, $args=array()) {

	global $registered_post_types, $prevent_redirect;

	$defaults = array(
		'type' => null,
		'type_plural' => null,
		'supports' => null,
		'show_in_nav_menus' => true,
		'hierarchical' => false,
		'prevent_canonical_redirection' => false
	);

	map_defaults($args, $defaults);

	extract($args);

	if ($prevent_canonical_redirection) { $prevent_redirect[] = $slug; }

	$registered_post_types[] = $slug;

	$supports = ( $supports ) ? csv2array($supports) : array('title', 'editor', 'excerpt', 'trackbacks', 'comments', 'revisions');

	register_post_type($slug, array(
		'labels' => array(
			'name'	=>	$menu_title,
			'singular_name'	=>	"$singular_name",
			'add_new'	=>	"Add New",
			'add_new_item' => "Add New $singular_name",
			'edit_item' => "Edit $singular_name",
			'new_item' => "New $singular_name",
			'view_item' => "View $singular_name",
			'search_items' => "Search ${singular_name}s",
			'not_found' => "No ${singular_name}s found",
			'not_found_in_trash' => "No ${singular_name}s found in Trash",
		),
		'public' => true,
		'show_in_nav_menus' => $show_in_nav_menus,
		'hierarchical' => $hierarchical,
		'description' => $description,
		'exclude_from_search' => false,
		'show_ui' => true,
		'supports' => $supports,
		'menu_position' => 5,
		'menu_icon' => POST_TYPE_ICON,
	));

}

function der_add_default_taxonomies($post_type, $name=null) {

	$tax_name = ( $name ) ? $name : ucwords(str_replace('-',' ', $post_type));

	register_taxonomy($post_type . '-category', array($post_type), array(
		'hierarchical' => true,
		'labels' => array(
			'name'	=>	"${tax_name} Categories",
			'singular_name' => "${tax_name} Category",
			'search_items' => "Search ${tax_name} Categories",
			'all_items' => "All ${tax_name} Categories",
			'parent_item' => "Parent ${tax_name} Category",
			'parent_item_colon' => "Parent ${tax_name} Category:",
			'edit item' => "Edit ${tax_name} Category",
			'update_item' => "Update ${tax_name} Category",
			'add_new_item' => "Add New ${tax_name} Category",
			'new_item_name' => "New ${tax_name} Category Name"
			),
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array('slug' => $post_type . '-category')
		)
	);

	register_taxonomy($post_type . '-tags', array($post_type), array(
		'hierarchical' => false,
		'labels' => array(
			'name'	=>	"${tax_name} Tags",
			'singular_name' => "${tax_name} Tag",
			'search_items' => "Search ${tax_name} Tags",
			'all_items' => "All ${tax_name} Tags",
			'edit item' => "Edit ${tax_name} Tag",
			'update_item' => "Update ${tax_name} Tag",
			'add_new_item' => "Add New ${tax_name} Tag",
			'new_item_name' => "New ${tax_name} Tag"
			),
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array('slug' => $post_type . '-tag')
		)
	);

}

function der_on_activate() { global $pagenow;

	if  ( is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {

		header( 'Location: ' . admin_url('admin.php?page=theme_options') );

	}

}

function remove_post_class_duplicates($data) {

	return array_unique($data);

}

function der_the_category($before='', $separator=', ', $after='', $echo=false) { global $post;

	if ( $post->post_type == 'page') { return; }

	$categories = ( $post->post_type == 'post' ) ? 'category' : $post->post_type . '-category';

	$categories_list = get_the_term_list($post->ID, $categories, $before, $separator, $after);

	if ( is_object($categories_list) ) { return null; }

	if ( $echo ) { echo $categories_list; return; }

	else { return $categories_list; }

}

function der_the_tags($before, $separator, $after, $echo=false) { global $post;

	$tags = ( $post->post_type == 'post' ) ? 'post_tag' : $post->post_type . '-tags';

	$tags_list = get_the_term_list($post->ID, $tags, $before . ' ', $separator, $after);

	if ( is_object($tags_list) ) { return null; }

	if ( $echo ) { echo $tags_list; return; }

	else { return $tags_list; }

}

function prevent_post_type_redirection($redirect_url, $requested_url) { global $post_type, $prevent_redirect;

	return ( in_array($post_type, $prevent_redirect) ) ? false : $redirect_url;

}

function der_admin_actions() {

	do_action('der_admin_actions');

	exit();

}

function der_style_process_css($options, $context=null) { global $_der;

	foreach ( $options as $section => $null ) {

		foreach ( $options[$section] as $option ) {

			$selector = $option['selector'];

			$property = $option['property'];

			$val = $_der->getval($option['id'], $context);

			$unit = $option['unit'];

			$unit = ($unit) ? $unit : (is_numeric($val) ) ? 'px' : null; // heh!

			if ( $selector AND $property AND !empty($val) ) {

				echo $selector . ' { ' . $property . ': ' . $val . $unit . '; }' . "\n";

			} else {

				continue;

			}

		}

	}

}

?>