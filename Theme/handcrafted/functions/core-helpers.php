<?php

function sort_by_property($array, $property, $orderby=null) {

	$order = array();

	$out = array();

	foreach ( $array as $item ) { 

		if ( $order[$item->$property] == null ) { $order[$item->$property] = array(); }

		$order[$item->$property][] = $item;

	}

	ksort($order);

	if ( !empty($orderby) ) {

		$existing = array();

		$keys = explode_and_trim($orderby);

		$order_keys = array_keys($order);

		foreach ($keys as $key) {

			if (array_key_exists($key, $order)) { array_push($existing, $key); }

		}

		foreach ($existing as $key) { array_push($out, $order[$key]); }

		foreach ($order_keys as $key) {

			if (!array_key_exists($key, array_flip($existing))) {

				array_push($out, $order[$key]);

			}
		}

		$ordered = array();

		foreach ($out as $array) {

			foreach ($array as $x) { $ordered[] = $x; }

		}

		return $ordered;

	} else {

		foreach ($order as $item ) { array_push($out, $item); }

		$ordered = array();

		foreach ($out as $array) {

			foreach ($array as $x) { $ordered[] = $x; }

		}

		return $ordered;

	}
}

function map_defaults(&$args, $defaults) {

	foreach ($defaults as $key => $val) { if ( array_key_exists($key, $args) ) { $defaults[$key] = $args[$key]; } }

	$args = $defaults;

}

function string2array($string) {

	$string = trim($string);

	$string = explode("\n", $string);

	$clean = array();

	foreach ($string as $s) { $s = trim( strip_tags($s) ); if ( ! empty($s) ) { $clean[] = $s; } }

	return $clean;

}

function string2list($string) {

	$array = string2array($string);

	$list = '';

	foreach ($array as $item) { $list .= "\n<li>$item</li>"; }

	echo $list;

}

function raw2html($content) {

	$content = wptexturize($content);

	$content = convert_smilies($content);

	$content = convert_chars($content);

	$content = wpautop($content);

	return $content;

}

function string2html($content) {

	$content = wptexturize($content);

	$content = convert_chars($content);

	$content = trim($content);

	return $content;

}

function is_category_child($id, $parent_id) {

	return in_array( $id, get_term_children($parent_id, 'category') );

}

function is_IE() {

    if ( isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) ) {

        return true;

	} else {

        return false;
		
	}

}

function array2exclude($array) {

	if ( empty($array) ) { return null; }

	return '-' . implode(',-', $array);

}

function array2include($array) {

	return implode(',', $array);

}

function filter_nav_pages($html) {

	preg_match_all('/page\-item\-(\d+)/', $html, $matches);

	$pages = $matches[1];

	for ( $i=0; $i < count($pages); $i++ ) {

		$pages[$i] = (int) $pages[$i];

	}

	return $pages;

}

function print_bool($val) {

	echo ( $val ) ? 'true' : 'false';

}

function explode_and_trim($string) {

	$string = explode(',', $string );

	for ($i=0; $i < count($string); $i++) {

		$string[$i] = trim($string[$i]);

	}

	return $string;

}

function css_rule($selector, $properties, $echo=true) {

	$rule = trim($selector) . ' { ';

	$counter = 0;

	foreach ($properties as $property => $val) {

		if ( !empty($val) && $val != 'default' ) {

			preg_match('/\[(.+)\]/', $property, $matches);

			$unit = ( $matches && ! is_numeric($matches[1]) )  ? $matches[1] : null;

			$property = ( $matches ) ? str_replace($matches[0], '', $property) : $property;

			$rule .= trim($property) . ': ' . $val . $unit . '; ';

			$counter++;
			
		}

	}

	$rule .= "}\n";

	if ( $counter == 0 ) { return false; }

	if ( $echo ) { echo $rule; } else { return $rule; }

}

function add_url_vars($url, $values) {

	$url .= ( preg_match('/\?/', $url) ) ? '&' : '?';

	$first = true;

	foreach ( $values as $key => $val ) {

		$sep = ( $first ) ? '' : '&';

		$url .= $sep . $key . '=' . $val;

		$first = false;

	}

	return $url;

}

function is_msie() {

	// http://www.anyexample.com/programming/php/how_to_detect_internet_explorer_with_php.xml

	if ( ! defined('IS_MSIE') ) { define('IS_MSIE', ($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) ); }

	return IS_MSIE;

}

function ie_conditionals() {

	if ( is_msie() ) {

		$template_directory = get_bloginfo('template_directory');

		echo '
<!--[if IE]> <link href="' . $template_directory . '/core/css/ie.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="' . $template_directory . '/core/js/ie.js"></script> <![endif]-->
<!--[if IE 7]><link href="' . $template_directory . '/core/css/ie7.css" rel="stylesheet" type="text/css" /> <![endif]-->
<!--[if IE 8]><link href="' . $template_directory . '/core/css/ie8.css" rel="stylesheet" type="text/css" /> <![endif]-->
';

	}

}

function get_post_images() { global $post;

	$more = true;

	$GLOBALS['HC_GALLERY_ONLY_IMAGES'] = true;

	$media = get_the_content();

	$media = apply_filters('the_content', $media); // Add support for WordPress galleries, shortcodes should be evaluated prior to processing

	preg_match_all('/<img(.+)\/>/i', $media, $matches); // Match all supported media

	$matches = $matches[0];

	$GLOBALS['HC_GALLERY_ONLY_IMAGES'] = false;

	// Filter

	for ( $i=0; $i < count($matches); $i++) {

		$current = trim($matches[$i]);

		if ( preg_match('/^<img/', $current) ) { // If it's an image

			$current = preg_replace('/class="(.*?)"|alt=""/', '', $current); // Remove class & alt attributes

			$current = preg_replace('/height=(\"|\')(\d+)(\"|\')/', '', $current);

		}

		$current = preg_replace('/\s+/', ' ', $current); // Replace trailing whitespace with single space

		$matches[$i] = $current;

	}


	// Print list items

	$images = array();

	foreach ($matches as $item) {

		preg_match('/src=(\'|")(.*?)(\'|")/', $item, $found);

		$src = trim($found[2]);

		if ( preg_match('/\.\.\/wp-includes\/js\/tinymce\/plugins\/wpgallery\/img\/t.gif$/', $src) ) { continue; } // Skip TynyMCE Gallery Image

		$info = pathinfo($src);

		$regex = "/\-(\d+)x(\d+).${info['extension']}$/";

		if ( preg_match($regex, $src, $new_matches) ) {

			// Return images that are part of a WordPress gallery, remove thumbnail format and get original image url

			$src = preg_replace("/(.+)-${new_matches[1]}x${new_matches[2]}.${info['extension']}$/", "$1.${info['extension']}", $src );

		}

		$images[] = $src; // source url

	}

	return $images;

}

function parse_queryurl($url) {

	$data = parse_url($url);

	$vars = array();

	parse_str($data['query'], $vars);

	return $vars;

}

function continue_query_str($string, $amp=true) {

	if ( preg_match('/\?/', $string) ) { $string .= ($amp) ? '&amp;' : '&'; }

	else { $string .= '?'; }

	return $string;

}

?>