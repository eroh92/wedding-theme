<?php /* theme-queries */

/* @var $_der DerThemeOptions */

function der_homepage_query() { global $_der;

	 /* Homepage Query */

	 $showposts = $_der->getval('homepage_posts_limit');

	 $showposts = ( $showposts ) ? $showposts : '-1';

	 $exclude_categories = $_der->get_taxonomies('homepage_exclude_categories');

	 $include_categories = $_der->get_taxonomies('homepage_only_categories');

	 $cats = ( empty($include_categories) ) ? array2exclude($exclude_categories) : implode(',', $include_categories);

	 $homepage_query = new WP_Query("cat=${cats}&showposts=${showposts}");

	 $_der->last_query = $homepage_query;

	 return $homepage_query;

}

function der_slider_query($first=false) { global $post, $_der;

	/* Slideshow Query */

	$showposts = $_der->getval('slider_items_limit');

	$showposts = ( $showposts ) ? $showposts : '-1';

	if ($first) { $showposts = 1; }

	$slideshow_query = new WP_Query("post_type=slideshow&showposts=-1");

	$slideshow_posts = $slideshow_query->posts;

	$ordered = array();

	$unordered = array();

	for ( $i=0; $i < count($slideshow_posts); $i++ ) {

		$post = $slideshow_posts[$i];

		$order = der_postmeta('slideshow_position');

		if ( empty($order) ) { $unordered[] = $post; }

		else {

			$post->menu_order = $order;

			$ordered[] = $post;

		}

	}

	$ordered = sort_by_property($ordered, 'menu_order');

	$slideshow_posts = array_merge($unordered, $ordered);

	$slideshow_posts = array_slice($slideshow_posts, 0, $showposts);

	$filtered = array();

	foreach ($slideshow_posts as $post) {

		$post_image = get_post_meta($post->ID, 'post_image', true);

		if ($post_image) { $filtered[] = $post; }

	}

	$slideshow_posts = $filtered;


	$_der->last_query = $slideshow_posts;

	return $slideshow_posts;

}


function der_blog_query() { global $_der, $paged;

	/* Blog Loop */

	$showposts = $_der->getval('blog_posts_per_page');

	$showposts = ( $showposts ) ? $showposts : '-1';

	$exclude_categories = $_der->get_taxonomies('blog_exclude_posts');

	$include_categories = $_der->get_taxonomies('blog_only_posts');

	$cats = ( empty($include_categories) ) ? array2exclude($exclude_categories) : implode(',', $include_categories);

	$blog_query = new WP_Query("cat=${cats}&showposts=${showposts}&paged=${paged}");

	$_der->last_query = $blog_query;

	return $blog_query;

}

function der_portfolio_page_query() { global $_der, $paged, $wpdb;

	$showposts = der_postmeta('posts_per_page');

	$portfolio_layout = der_postmeta('portfolio_layout');

	if (empty($showposts)) {

		switch($portfolio_layout) {

			case 'Single Column':

				$showposts = $_der->getval('single_column_ppp');

				break;

			case 'Two Columns':

				$showposts = $_der->getval('two_columns_ppp');

				break;


			case 'Three Columns':

				$showposts = $_der->getval('three_columns_ppp');

				break;

		}

	}

	$categories = der_postmeta('portfolio_categories', true);

	$args = array(
		'post_type' => 'portfolio',
		'taxonomy' => 'portfolio-category',
		'terms' => $categories,
		'showposts' => $showposts
	);

	$orig_query = new DerCustomQuery($args);

	switch ($portfolio_layout) {

		case 'Single Column':

			$query = apply_filters('single_column_query', $query);

			$query = ( empty($query) ) ? $orig_query : $query;

			break;

		case 'Two Columns':

			$query = apply_filters('two_columns_query', $query);

			$query = ( empty($query) ) ? $orig_query : $query;

			break;

		case 'Three Columns':
			
			$query = apply_filters('three_columns_query', $query);

			$query = ( empty($query) ) ? $orig_query : $query;

			break;


	}

	$_der->last_query = $query;

	return $query;

}

?>