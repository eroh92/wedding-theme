<?php /* mod-bgimage */ global $d;

// Layout Dimensions Module
// ========================

// Create Module Instance
$layout_module = new DerModule('layout', 'Layout Options');


/* Layout */

$d->layout['Layout Dimensions'] = array();

$d->layout['Layout Dimensions'][] = array(
	'name'			=>	'Homepage Posts Thumbnail Height  <small>(pixels)</small>',
	'id'			=>	'homepage_posts_height',
	'type'			=>	'text',
	'default'		=>	'140',
	'description'	=>	'Height to use for the Homepage Post Thumbnails.<br/><br/>

<strong>Note:</strong> <em>Set to -1 to preserve <u>Aspect Ratio</u>.</em>'
);

$d->layout['Layout Dimensions'][] = array(
	'name'			=>	'Full Width Post Image Width  <small>(pixels)</small>',
	'id'			=>	'fw_post_image_width',
	'type'			=>	'text',
	'default'		=>	'830',
	'description'	=>	'Width to use for the Full Width Single Post Layout. The Image\'s Aspect Ratio will be preserved.'
);

$d->layout['Layout Dimensions'][] = array(
	'name'			=>	'Blog Posts Thumbnail Height, Normal Layout <small>(pixels)</small>',
	'id'			=>	'blog_posts_height',
	'type'			=>	'text',
	'default'		=>	'215',
	'description'	=>	'Height to use for the Blog Post Thumbnails, on the <strong>Normal Blog Layout</strong>.<br/><br/>

<strong>Note:</strong> <em>Set to -1 to preserve <u>Aspect Ratio</u>.</em>'
);

$d->layout['Layout Dimensions'][] = array(
	'name'			=>	'Blog Posts Thumbnail Height, Full Width Layout <small>(pixels)</small>',
	'id'			=>	'blog_fw_posts_height',
	'type'			=>	'text',
	'default'		=>	'320',
	'description'	=>	'Height to use for the Blog Post Thumbnails, on the <strong>Full Width Blog Layout</strong>.<br/><br/>

<strong>Note:</strong> <em>Set to -1 to preserve <u>Aspect Ratio</u>.</em>'
);

$d->layout['Layout Dimensions'][] = array(
	'name'			=>	'Thumbnail Height for Two Columns Portfolio Layout <small>(pixels)</small>',
	'id'			=>	'two_columns_image_height',
	'type'			=>	'text',
	'default'		=>	'230',
	'description'	=>	'Height to use for the <strong>Two Columns</strong> Portfolio Layout Thumbnails'
);

$d->layout['Layout Dimensions'][] = array(
	'name'			=>	'Thumbnail Height for Three Columns Portfolio Layout <small>(pixels)</small>',
	'id'			=>	'three_columns_image_height',
	'type'			=>	'text',
	'default'		=>	'150',
	'description'	=>	'Height to use for the <strong>Three Columns</strong> Portfolio Layout Thumbnails'
);

?>
