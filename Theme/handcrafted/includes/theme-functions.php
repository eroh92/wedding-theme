<?php /* theme-functions */ global $_der;

/* @var $_der DerThemeOptions */
/* @var $wp_query WP_Query */

/* Actions */

add_action('der_style', 'theme_style');
add_action('der_script', 'theme_script');
add_action('der_pagination', 'theme_pagination', 10, 3);
add_action('admin_init', 'der_add_mce_styles');

/* Editor Styles */

add_editor_style('core/css/blueprint.css');
add_editor_style('core/css/editor/editor-style.css' );

/* Filters */

add_filter('pre_comment_author_url', 'theme_sanitize_author_url');

/* Defines */


/* Functions */

function body_id() { global $post, $_der;

	$id = '';

	if ( is_home() ) {

		$id = 'home';

	} elseif ( $post->post_type == 'portfolio-page' ) {

		$id = 'portfolio';

	} elseif ( $_der->equal('blog_page', $post->ID) OR is_archive() OR is_search() ) {

		$id = 'blog';

	} elseif ( is_single() ) {

		$id = 'single';

	} elseif ( is_page() OR is_404() ) {

		$id = 'page';

	}

	echo ( $id ) ? ' id="' . $id . '"' : null;

}

function theme_style() { global $_der;

	background_image_css();

	if ( $_der->equal('color_theme', 'Wood') ) {

		echo '#home .post-image img { margin-bottom: -7px !important; }' . "\n";

	}

	if ( $_der->getval('remove_slideshow_controls', MOD_NIVO) AND $_der->equal('slider_manager', 'Nivo Slider') ) {

		echo '#slideshow-controls { display: none !important; }
#slideshow { margin-bottom: 27px; }' . "\n";

	}

}

function background_image_css() { global $_der;

	$selector = '#content-wrap-bg';

	$background_image = $_der->getval('background_image');

	$background_image = ($background_image) ? 'url(' . $background_image . ')' : null;

	$color = $_der->getval('background_color');

	if ($background_image OR $color) {

		$position = $_der->getval('background_position');

		$repeat = $_der->getval('background_repeat');

		$attachment = $_der->getval('background_attachment');

		echo "$selector { background: $background_image $color $position $repeat $attachment !important; }";

	}

}

function theme_logo() { global $_der;

	$logo = $_der->getval('logo_image', null, false);

	if ( empty($logo) ) {

		$color_theme = strtolower( $_der->getval('color_theme') );

		switch($color_theme) {

			case 'default':
				$logo =  get_bloginfo('template_directory') . '/core/images/logo.png';
				break;

			case 'dark':
			case 'wood':
				$logo = get_bloginfo('template_directory') . "/core/styles/${color_theme}/logo.png";
				break;

		}

	}

	echo $logo;

}

function theme_navigation() { global $_der;

	$theme_location = 'header_navigation';

	$show_home_menu = ! $_der->checked('disable_home_menu_item');

	if ( der_wp_nav_menu_enabled($theme_location) ) {

		// Menu Manager

		$menu_args = array(
			'theme_location' => $theme_location,
			'container' => null,
			'menu_id' => 'navigation',
			'menu_class' => $class,
			'depth' => 0,
			'echo' => false
		);

		$header_nav = wp_nav_menu($menu_args) . '<!-- navigation -->';

		if ($show_home_menu) {

			$active = ( is_home() ) ? ' current-menu-item' : null;

			$home_menu = "\n" . '<li class="page_item' . $active . '"><a href="' . get_bloginfo('home') . '">' . t('Home') . "</a></li>\n";

		} else { $home_menu = null; }

		$header_nav = str_replace('<ul id="navigation">', '<ul id="navigation">' . $home_menu, $header_nav );

	} else {

		// Page-Based Navigation

		$menu_args = array(
			'title_li' => '',
			'depth' => 0,
			'echo' => false
		);

		$ul_open_tag = '<ul id="navigation">';

		$ul_close_tag = '</ul><!-- navigation -->';

		if ($show_home_menu) {

			$active = ( is_home() ) ? ' current_page_item' : null;

			$home_menu = "\n" . '<li class="page_item' . $active . '"><a href="' . get_bloginfo('home') . '" title="' . t('Home') . '">' . t('Home') . "</a></li>\n";

		} else { $home_menu = null; }

		$header_nav = $ul_open_tag . $home_menu . wp_list_pages($menu_args) . $ul_close_tag;

	}

	echo $header_nav;

}

function theme_script() { global $_der;

	$enable_caching = (! $_der->checked('disable_js_caching')) ? 'true' : 'false';

	echo "enable_caching = " . $enable_caching . ";\n";

}

function theme_long_title() { global $post;

	$long_title = der_postmeta('long_title');

	return ($long_title) ? $long_title : get_the_title();

}

function theme_pagination($pagination, $pagination_pages, $offset) { global $paged;

	$add_previous = ! in_array('<<', $pagination);

	$add_next = ! in_array('>>', $pagination);

	$html = array();

	$open_tag = $inner_tab . '<ul id="pagination">';

	$close_tag = $inner_tab . '</ul><!-- pagination -->';

	foreach ($pagination as $p) {

		if ( $p == '<<') {

			$html[] = '	<li class="prev"><a href="' . der_pagenum_link($paged-1) . '">' . t('Previous Page') . '</a></li>'; continue;

		} elseif ( $p == '>>') {

			$html[] = '	<li class="next"><a href="' . der_pagenum_link($paged+1) . '">' . t('Next Page') . '</a></li>'; continue;

		} elseif ( preg_match('/\[(\d+)\]/', $p, $matches) ) {

			$html[] = '	<li><a class="active">' . $matches[1] . '</a></li>'; continue;

		} elseif ( $p == '(..)' ) {

			$html[] = '	<li><a>&hellip;</a></li>'; continue;

		} elseif ( is_numeric($p) ) {

			$html[] = '	<li><a href="' . der_pagenum_link($p) . '">' . $p . '</a></li>'; continue;

		}

	}

	if ($add_previous) { 

		$href = ($paged == 1) ? '#prev' : der_pagenum_link($paged-1);

		$style = ($paged == 1) ? 'style="visibility: hidden;"' : null;

		array_unshift($html, '	<li class="prev"><a href="' . $href . '" ' . $style . '>' . t('Previous Page') . '</a></li>');

	}

	if ($add_next) {

		$href = ($paged >= $pagination_pages) ? '#next' : der_pagenum_link($paged+1);

		$style = ($paged >= $pagination_pages) ? 'style="visibility: hidden"' : null;

		$html[] = '	<li class="next"><a href="' . $href . '" ' . $style . '>' . t('Previous Page') . '</a></li>';

	}

	array_unshift($html, $open_tag);

	$html[] = $close_tag;

	echo implode("\n", $html);

}

function sanitize_video_url($post_video) {

	$post_video = str_replace('/www.vimeo.com/', '/vimeo.com/', $post_video);

	$post_video = str_replace('&', '&amp;', $post_video);

	return $post_video;

}

function theme_date_links() {

	echo der_month_link('m') . '.' . der_day_link('d') . '.' . der_year_link('Y');

}

function der_add_mce_styles() {

	if ( basename($_SERVER['SCRIPT_FILENAME']) == 'post.php' AND ! empty($_GET['post']) ) {

		$post = $_GET['post'];

		$fullwidth = get_post_meta($post, 'use_fullwidth', true);

		if ( $fullwidth ) {

			add_editor_style('core/css/editor/fullwidth.css' );

		}

	}

}

function validateURL($url) {

	// http://www.blog.highub.com/regular-expression/php-regex-regular-expression/php-regex-validating-a-url/

	$pattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
	
	return preg_match($pattern, $url);
	
}

function theme_sanitize_author_url($data) {

	return (validateURL($data)) ? $data : false;

}

?>