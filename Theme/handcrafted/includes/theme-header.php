<?php /* theme-header */

	global $_der, $post;

	$tdir = get_bloginfo('template_directory') . '/';

	wp_enqueue_script('jquery');

	wp_enqueue_script('der-script',	$tdir . 'core/script.php', array('jquery'), null);

	if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1) ) { wp_enqueue_script('comment-reply'); }

	// wp_enqueue_script('HANDLE',	$tdir . 'core/js/FILENAME', array('DEPENDENCY'), 'VERSION');

	// -- Script includes start --

	$enable_caching = (! $_der->checked('disable_js_caching'));

	wp_enqueue_script('jquery-easing',	$tdir . 'core/js/jquery.easing.js', array('der-script'), null);

	wp_enqueue_script('jquery-prettyphoto',	$tdir . 'extra/prettyphoto/js/jquery.prettyPhoto.js', array('der-script'), null);

	if ( $enable_caching ) {
		wp_enqueue_script('jquery-cookie',	$tdir . 'core/js/jquery.c00kie.js', array('der-script'), null);  // prevent "HTTP/406 Not Acceptable" Error on restrictive servers
	}

	wp_enqueue_script('jquery-base64',	$tdir . 'core/js/jquery.base64.js', array('der-script'), null);

	wp_enqueue_script('jquery-twitter',	$tdir . 'core/js/jquery.twitter.js', array('der-script'), null);

	wp_enqueue_script('jquery-lib',	$tdir . 'core/js/lib.js', array('der-script'), null);
	
	wp_enqueue_script('jquery-core',	$tdir . 'core/js/core.js', array('jquery-lib'), null);

?>
