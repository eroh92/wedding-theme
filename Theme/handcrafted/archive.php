<?php

// PROTECTION
if ( ! defined('ABSPATH') ) { die(''); }

/* archive */ global $_der, $paged, $wp_query;

	get_header();

	/* Query */

	$showposts = $_der->getval('blog_posts_per_page');

	set_query_var('showposts', $showposts);

	set_query_var('paged', $paged);

	$wp_query->get_posts();

	/* Page Title */

	$description = null;

	if ( is_year() ) {

		$title = get_the_time('Y');
		
		$description = t('Displaying All Posts Published in ') . ' ' . $title;

	} elseif ( is_month() ) {

		$title = get_the_time('F') . ' ' . get_the_time('Y');

		$description = t('Displaying All Posts Published in ') . ' ' . $title;

	} elseif ( is_day() ) {

		$title = get_the_time('F') . ' ' . get_the_time('j') . ', ' . get_the_time('Y');

		$description = t('Displaying All Posts Published in') . ' ' . $title;

	} elseif ( is_category() ) {

		$title = get_cat_name( get_query_var('cat') );

		$description = t('Displaying All Posts Filed under the') . ' &quot;' . $title . '&quot; ' . t('Category');

	} elseif ( is_tag() ) {

		$title = $tag;

		$description = t('Displaying All Posts Tagged as') . ' &quot;' . $title . '&quot;';

	} elseif ( is_search() ) {

		$title = attribute_escape( get_search_query() );

		$description = t('Search Results: Found % Posts containing') . ' &quot;' . $title . '&quot;';

		$description = str_replace('%', $wp_query->found_posts, $description);

	} elseif ( is_author() ) { global $post;

		$author_id = get_query_var('author');

		$name = get_author_name($author_id);

		$title = $name;

		$description = t('Displaying All Posts Published by') . ' ' . $title;

	} elseif ( is_404() ) {

		$title = '';

	} elseif ( is_tax() ) {

		$term = $wp_query->get_queried_object();

		$title = string2html($term->name);

		switch ($term->taxonomy) {

			case 'portfolio-category':

				$category_name = t('Portfolio Category');

				break;

			case 'slideshow-category':

				$category_name = t('Slideshow Category');

				break;

		}

		$description = t('Displaying All Posts Filed under the') . ' &quot;' . $title . '&quot; ' . $category_name;
		

	} else {

		$title = 'Archive';

		$description = 'Archive';

	}

	// Share Blog Layout

	include(TEMPLATEPATH . '/template_blog.php');

	exit();

?>