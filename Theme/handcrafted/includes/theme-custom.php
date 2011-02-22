<?php /* Custom Posts */ global $_der;

/* Defines */

define('DER_DISABLE_CUSTOM_COLUMNS', false);


/* Actions */

add_action('der_init', 'theme_internals');
add_action('manage_posts_custom_column', 'manage_slideshow_columns', 10, 2);
add_action('manage_posts_custom_column', 'manage_post_columns', 10, 2);
add_action('manage_posts_custom_column', 'manage_portfolio_columns', 10, 2);
add_action('manage_posts_custom_column', 'manage_services_columns', 10, 2);
add_action('manage_pages_custom_column', 'manage_page_columns', 10, 2);
add_action('manage_pages_custom_column', 'manage_portfolio_page_columns', 10, 2);


/* Filters */

add_filter('manage_edit-slideshow_columns', 'add_new_slideshow_columns');
add_filter('manage_edit-post_columns', 'add_new_post_columns');
add_filter('manage_edit-page_columns', 'add_new_page_columns');
add_filter('manage_edit-portfolio_columns', 'add_new_portfolio_columns');
add_filter('manage_edit-portfolio-page_columns', 'add_new_portfolio_page_columns');
add_filter('manage_edit-services_columns', 'add_new_services_columns');


/* Functions */

function theme_internals() {

	define('POST_TYPE_ICON', get_bloginfo('template_directory') . '/core/images/branding/post-type.png');

	der_add_post_type('slideshow', 'Slider Posts', 'Slider Post', 'Slider Posts');
	der_add_default_taxonomies('slideshow', 'Slider');

	der_add_post_type('portfolio-page', 'Portfolio Pages', 'Portfolio Page', 'Portfolio Pages', array(
		'hierarchical'=>true,
		'supports' => 'title, revisions',
		'prevent_canonical_redirection' => true
	));
	
	der_add_post_type('portfolio', 'Portfolio Posts', 'Portfolio Post', 'Portfolio Posts');
	der_add_default_taxonomies('portfolio');

}

function add_new_post_columns($data) { global $_der;

	if ( DER_DISABLE_CUSTOM_COLUMNS ) { return $data; }

	$cols = array();
	$cols['cb'] = '<input type="checkbox" />';
	$cols['title'] = __('Title');
	$cols['author'] = __('Author');
	$cols['categories'] = __('Categories');
	$cols['tags'] = __('Tags');
	$cols['layout'] = __('Layout');
	$cols['redirect_url'] = __('Redirect');
	$cols['comments'] = '<div class="vers"><img alt="Comments" src="' . admin_url() . 'images/comment-grey-bubble.png" /></div>';
	$cols['date'] = __('Date');

	return $cols;

}

function add_new_page_columns($data) { global $_der;

	$cols = array();
	$cols['cb'] = $data['cb'];
	$cols['title'] = __('Title');
	$cols['author'] = __('Author');
	$cols['layout'] = __('Layout');
	$cols['redirect_url'] = __('Redirect');
	$cols['comments'] = '<div class="vers"><img alt="Comments" src="' . admin_url() . 'images/comment-grey-bubble.png" /></div>';
	$cols['date'] = __('Date');

	return $cols;

}

function add_new_slideshow_columns($data) { global $_der;

	if ( DER_DISABLE_CUSTOM_COLUMNS ) { return $data; }

	$cols = array();
	$cols['cb'] = $data['cb'];
	$cols['title'] = __('Title');
	$cols['author'] = __('Author');
	$cols['slideshow_categories'] = __('Categories');
	$cols['slideshow_tags'] = __('Tags');
	$cols['slideshow_position'] = __('Position');
	$cols['post_image'] = __('Image');
//	$cols['layout'] = __('Layout');
	$cols['redirect_url'] = __('Redirect');
	$cols['comments'] = '<div class="vers"><img alt="Comments" src="' . admin_url() . 'images/comment-grey-bubble.png" /></div>';
	$cols['date'] = __('Date');
	
	return $cols;

}

function add_new_portfolio_columns($data) { global $_der;

	if ( DER_DISABLE_CUSTOM_COLUMNS ) { return $data; }

	$cols = array();
	$cols['cb'] = $data['cb'];
	$cols['title'] = __('Title');
	$cols['author'] = __('Author');
	$cols['portfolio_categories'] = __('Categories');
	$cols['portfolio_tags'] = __('Tags');
	$cols['layout'] = __('Layout');
	$cols['redirect_url'] = __('Redirect');
	$cols['comments'] = '<div class="vers"><img alt="Comments" src="' . admin_url() . 'images/comment-grey-bubble.png" /></div>';
	$cols['date'] = __('Date');

	return $cols;

}

function add_new_services_columns($data) { global $_der;

	if ( DER_DISABLE_CUSTOM_COLUMNS ) { return $data; }

	$cols = array();
	$cols['cb'] = $data['cb'];
	$cols['title'] = __('Title');
	$cols['author'] = __('Author');
	$cols['services_categories'] = __('Categories');
	$cols['services_tags'] = __('Tags');
	$cols['redirect_url'] = __('Redirect');
	$cols['comments'] = '<div class="vers"><img alt="Comments" src="' . admin_url() . 'images/comment-grey-bubble.png" /></div>';
	$cols['date'] = __('Date');

	return $cols;

}

function add_new_portfolio_page_columns($data) { global $_der;

	if ( DER_DISABLE_CUSTOM_COLUMNS ) { return $data; }

	$cols = array();
	$cols['cb'] = $data['cb'];
	$cols['title'] = __('Title');
	$cols['portfolio_categories'] = __('Portfolio Categories');
	$cols['portfolio_layout'] = __('Layout');
	$cols['author'] = __('Author');
	$cols['redirect_url'] = __('Redirect');
	$cols['comments'] = '<div class="vers"><img alt="Comments" src="' . admin_url() . 'images/comment-grey-bubble.png" /></div>';
	$cols['date'] = __('Date');

	return $cols;

}

function manage_post_columns($column_name, $id) {

	switch ($column_name) {

		case 'layout':

			echo ( der_postmeta('use_fullwidth') ) ? 'Full Width' : 'Normal';

			break;

		case 'redirect_url':

			$link = der_postmeta('redirect_url');

			if ( $link ) {

				echo der_link('Link', $link, '_blank', $link);

			}

			break;

		case 'post_image':

			$post_image = der_postmeta('post_image');

			if ($post_image) {

				echo '<a rel="lightbox" title="' . $post_image . '" href="' . $post_image . '">' . __('Yes') . '</a>';

			} else { echo __('No'); }

			break;

	}

}

function manage_page_columns($column_name, $id) {

	switch ($column_name) {

		case 'layout':

			$layout = der_postmeta('use_fullwidth');

			echo ( der_postmeta('use_fullwidth') ) ? 'Full Width' : 'Normal';

			break;

		case 'redirect_url':

			$link = der_postmeta('redirect_url');

			if ( $link ) {

				echo der_link('Link', $link, '_blank', $link);

			}

			break;

	}

}

add_action('der_admin_actions', 'slideshow_position_request');

function slideshow_position_request() {

	switch($_POST['type']) {

		case 'slideshow_position':

			$order = $_POST['order'];

			$post_id = $_POST['p'];

			if ( empty($order) ) { return false; }

			$success = update_post_meta($post_id, 'slideshow_position', $order);

			if ( $success ) { echo 'Success'; }

			break;

	}

}


function manage_slideshow_columns($column_name, $id) {

	switch( $column_name ) {

		case 'slideshow_position':

			$slideshow_position = der_postmeta('slideshow_position');

			$slideshow_position = ( $slideshow_position != null ) ? $slideshow_position : 'Set';

			   echo "<a href='#' onclick=\"var theLink = jQuery(this); var order=prompt('New Position: '); if (order) { jQuery.post('" . admin_url('admin-ajax.php') . "', {action:'der_admin_actions',p:$id,order:order,type:'slideshow_position'}, function() { theLink.html(order); } ); } return false; \">" . $slideshow_position . '</a>';

			break;

		case 'slideshow_categories':

			der_show_admin_taxonomy_links($id, 'slideshow-category', 'slideshow', 'Uncategorized');

			break;

		case 'slideshow_tags':

			der_show_admin_taxonomy_links($id, 'slideshow-tags', 'slideshow', 'No Tags');

			break;

	}

}

function manage_portfolio_columns($column_name, $id) {

	switch ($column_name) {

		case 'portfolio_categories':

			der_show_admin_taxonomy_links($id, 'portfolio-category', 'portfolio', 'Uncategorized');

			break;

		case 'portfolio_tags':

			der_show_admin_taxonomy_links($id, 'portfolio-tags', 'portfolio', 'No Tags');

			break;

	}

}

function manage_services_columns($column_name, $id) {

	switch ($column_name) {

		case 'services_categories':

			der_show_admin_taxonomy_links($id, 'services-category', 'services', 'Uncategorized');

			break;

		case 'services_tags':

			der_show_admin_taxonomy_links($id, 'services-tags', 'services', 'No Tags');

			break;

	}

}

function manage_portfolio_page_columns($column_name, $id) {

	switch ($column_name) {

		case 'portfolio_layout':

			echo der_postmeta('portfolio_layout');

			break;

		case 'portfolio_categories':

			$categories = der_postmeta('portfolio_categories', true);

			$links = array();

			foreach ($categories as $cat) {

				$term = get_term($cat, 'portfolio-category');

				if (! $term) { continue; }

				$links[] = '<a href="' . admin_url('edit.php?post_type=portfolio&taxonomy=portfolio-category&portfolio-category=' . $term->slug) . '">' . $term->name . '</a>';

			}

			echo implode(', ', $links);

			break;

	}

}

?>