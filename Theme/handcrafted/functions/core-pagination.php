<?php 

// Actions & Filters

add_action('pre_get_posts', 'der_get_queried_object');
add_filter('previous_posts_link_attributes', 'der_previous_posts_link_class');
add_filter('next_posts_link_attributes', 'der_next_posts_link_class');
if ( permalinks_enabled() ) { add_filter('get_pagenum_link', 'fix_pagenum_link'); }

// Modify Core Parameters

global $core_pagination_args; // Set to be overridden in theme-config.php

$core_pagination_args = ( $core_pagination_args ) ? $core_pagination_args : array();

$core_pagination_defaults = array(
	'previous_posts_link_class' => null,
	'next_posts_link_class' => null
);

map_defaults($core_pagination_args, $core_pagination_defaults);



// Functions

function fix_pagenum_link($url) {

	$home = get_bloginfo('home');

	$bname = basename($home);

	$url = str_replace("/$bname/$bname", "/$bname", $url);

	return $url;
	
}

function der_pagination($query_object=null) { global $_der, $paged;

	$query_object = ( $query_object ) ? $query_object : $_der->last_query;

	if ( $query_object == null ) { return false; }

	$paged = (empty($paged)) ? 1 : $paged;

	$showposts = $query_object->query_vars['showposts'];

	$pagination_pages = ceil( $query_object->found_posts / $showposts );

	$offset = 2;

	$pagination = der_get_pagination($paged, $offset, $pagination_pages );

	if ( $pagination_pages <= 1 ) { return false; }

	do_action('der_pagination', $pagination, $pagination_pages, $offset);
	
}

function der_pagenum_link($paged) { 

	if ( is_archive() OR DER_HOMEPAGE_ENABLED ) {

		return get_pagenum_link($paged);

	} elseif ( permalinks_enabled() ) {

		return _get_page_link( DER_QUERIED_OBJECT_ID ) . "/page/{$paged}";

	} else {

		return _get_page_link( DER_QUERIED_OBJECT_ID ) . "&paged={$paged}";

	}

}

function der_get_pagination($current, $offset, $pages) {
	$max_pages = (2*$offset) + 2;
	$cond1 = ($pages >= ( (2*$offset) + 1 + 3 ) ) && ( $current >= ($pages - $max_pages) && $current <= $pages ) || ($pages <= ((2*$offset) + 1 + 2 ) );
	$cond2 = ( $current <= (2*$offset) + 1 );

	$pagination = array();

	if ($cond1) { // RIGHT LOOP
		$head = $pages - $max_pages;

		// Add previous button
		if ( $current > 1 && $pages >= ((2*$offset)+1+3) ) { $pagination[] = '<<'; }

		for ($i=$head; $i <= $pages; $i++) {
			// Skip non-pages
			if ( $i <= 0 ) { continue; }

			// Add active page
			if ( $i == $current ) { $pagination[] = "[$i]"; }

			// Add normal page
			else { $pagination[] = $i; }
		}

		return $pagination;

	} else {
		if ($cond2) { // CENTER WHEEL (LEFT LOOP)
			$tail = (2*$offset) + 1;
			for ($i=1; $i <= $tail; $i++) {
				// Add active page
				if ( $i == $current ) { $pagination[] = "[$i]"; }

				// Add normal page
				else { $pagination[] = $i; }

				if ($i == $pages) { return $pagination; }

			}

			// Add (...) if needed
			if ( $tail <= ($pages - 2) ) { $pagination[] = '(..)'; }

			// Add last page
			$pagination[] = $pages;

			// Add next
			$pagination[] = '>>';

			return $pagination;

		} else { //CENTER WHEEL
				$head = $current - $offset;
				$tail = $current + $offset;

				// Add previous
				$pagination[] = '<<';

				// Left loop
				for ($i=$head; $i < $current; $i++) { $pagination[] = "$i"; }

				// Add active
				$pagination[] = "[$current]";

				// Right loop
				for ($i=$current+1; $i <= $tail; $i++) { $pagination[] = "$i"; }

				// Add (...)
				$pagination[] = '(..)';

				// Add last page
				$pagination[] = $pages;

				// Add next
				$pagination[] = '>>';

				return $pagination;
		}
	}
}

function der_get_queried_object() { global $wp_query;

	$id = ($wp_query->queried_object) ? $wp_query->queried_object->ID : get_query_var('page_id');

	define('DER_QUERIED_OBJECT_ID', $id);

}

function der_posts_navigation($direction, $label, $query=null) { global $_der, $paged, $wp_query;

	$query = ( $query ) ? $query : $_der->last_query;

	$paged = ( $paged == 0 ) ? 1 : $paged;

	if ( $query != null ) { // Do not backup query if not needed

		der_set_query($query);

		$wp_query = $query;

	}

	switch ($direction) {

		case 'previous': previous_posts_link($label); break;

		case 'next': next_posts_link($label); break;

	}

	if ( $query != null ) { der_restore_query(); } // Do not restore if not needed

}

function der_set_query($query) { global $wp_query, $wp_query_orig;

	$wp_query_orig = $wp_query;

	$wp_query = $query;

}

function der_restore_query() { global $wp_query, $wp_query_orig;

	$wp_query = $wp_query_orig;

}

function der_next_posts_link_class($data) { global $core_pagination_args;

	$c = $core_pagination_args['next_posts_link_class'];

	$class = ( $c ) ? 'class="' . $c . '"' : null;

	return $class;

}

function der_previous_posts_link_class($data) { global $core_pagination_args;

	$c = $core_pagination_args['previous_posts_link_class'];

	$class = ( $c ) ? 'class="' . $c . '"' : null;

	return $class;
	
}

?>