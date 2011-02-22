<?php /* theme-shortcodes */ global $_der;

add_shortcode('download', 'download_shortcode');
add_shortcode('button', 'button_shortcode');
add_shortcode('info', 'info_shortcode');
add_shortcode('warning', 'warning_shortcode');
add_shortcode('note', 'note_shortcode');
add_shortcode('hc_gallery', 'hc_gallery_shortcode');

function download_shortcode($atts, $content='Download', $code="") {

	extract(shortcode_atts(array(
		'url' => '#download-url',
	), $atts));

	return '<div class="download-box extra-box"><span><a href="' . $url . '">' . string2html($content) . '</a></span></div>';
	
}

function button_shortcode($atts, $content='Button', $code="") {

	extract(shortcode_atts(array(
		'url' => '#button-url',
		'display' => 'inline',
		'target' => 'default'
	), $atts));

	$open_tag = ( $display == 'block' ) ? '<p>' : null;

	$close_tag = ( $display == 'block' ) ? '</p>' : null;

	$target = ( $target == 'blank' ) ? ' target="_blank"' : null;

	return $open_tag . '<a class="button" href="' . $url . '"' . $target . '>' . string2html($content) . '</a>' . $close_tag;

}

function info_shortcode($atts, $content='Info Box', $code="") {

	return '<div class="info-box extra-box"><span>' . string2html($content) . '</span></div>';

}

function warning_shortcode($atts, $content='Warning Box', $code="") {

	return '<div class="warning-box extra-box"><span>' . string2html($content) . '</span></div>';

}

function note_shortcode($atts, $content='Note Box', $code="") {

	return '<div class="note-box extra-box"><span>' . string2html($content) . '</span></div>';

}

function hc_gallery_shortcode($atts, $content='', $code="") { global $post;

	extract(shortcode_atts(array(
		'columns' => 3,
		'height' => false
	), $atts));

	if ( $columns < 1 OR $columns > 3 ) { return null; }

	$use_fullwidth = der_postmeta('use_fullwidth');

	switch($columns) {

		case 1:
			$thumbs_width = ($use_fullwidth) ? 878 : 548;
			$default_height = ($use_fullwidth) ? 582 : 362;
			$thumbs_height = ( $height ) ? $height : $default_height;
			break;

		case 2:
			$thumbs_width = ($use_fullwidth) ? 413 : 258;
			$default_height = ($use_fullwidth) ? 271 : 168;
			$thumbs_height = ( $height ) ? $height : $default_height;
			break;

		case 3:
			$thumbs_width = ($use_fullwidth) ? 268 : 158;
			$default_height = ($use_fullwidth) ? 175 : 101;
			$thumbs_height = ( $height ) ? $height : $default_height;
			break;

	}

	$content = strip_tags($content);

	$html = array();

	$html[] = '<ul class="hc-gallery hc-gallery-' . $columns . ' layout-list clearfix">';

	$images = string2array($content);


	// Output only images, not the whole gallery

	if ( $GLOBALS['HC_GALLERY_ONLY_IMAGES'] == true ) {

		for ( $i=0; $i < count($images); $i++ ) { $images[$i] = '<img src="' . $images[$i] . '" />'; }

		return implode("\n", $images);

	}

	// Output the whole gallery

	foreach ($images as $image) {

		$thumb = thumb_src($image, $thumbs_width, $thumbs_height);

		$html[] = "\t" . '<li><a rel="lightbox[hc-gallery-' . $columns . ']" href="' . $image . '"><img width="' . $thumbs_width . '" height="' . $thumbs_height . '" src="' . $thumb . '" /></a></li>';

	}

	$html[] = '</ul><!-- .hc-gallery -->';

	$html = implode("\n", $html);

	return $html;

}

?>