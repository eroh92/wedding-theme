<?php /* mod-typography */ global $d;

// Typography Module
// =================

/*

caption, wp-caption p #comments ol.commentlist li.comment .comment-author cite.fn, h1,h2,h3,h4,h5,h6, a.post-edit-link, a.button,
form input[type=text], form input[type=password], form textarea, form input[type=submit], form input[type=reset], #header .navigation > ul > li,
#piecemaker-container .require, #slideshow .title .text, #slideshow-controls, #homepage-center p, #page-header h1.page-title, #page-header span.page-meta, #page-header span.page-meta a,
ul#pagination, #content .post > a.more, #contact-form div.success, div.light_square, #navigation > li ul, .widget h2.title

*/


// Create Module Instance
$typography_module = new DerModule('typography', 'Typography');

add_action('der_style', 'mod_typography_css');


// Module Options

$d->typography['Typography'][] = array(
	'name'			=>	'Navigation Menu &amp; Widget Titles Font',
	'id'			=>	'navigation_menu_font',
	'type'			=>	'text',
	'scope'			=>	'font',
	'default'		=>	"<link href='http://fonts.googleapis.com/css?family=OFL+Sorts+Mill+Goudy+TT' rel='stylesheet' type='text/css'>",
	'selector'		=>	'#header .navigation > ul > li > a, #homepage-center p span.dropcap, .widget h2.title',
	'description'	=>	'<strong>Embed Code</strong> for the Navigation Menu Font.',
	'intro_message' => array(
		'title'		=>	'Adding Fonts from the Google Fonts Directory',
		'content'	=>	'
' . der_theme_data('Name') . ' allows you to use any font listed on the <a target="_blank" href="http://code.google.com/webfonts/">Google Fonts Directory</a> to your site.<br/>
This allows more flexibility when it comes to the message you want to portray using Typography.<br/><br/>

<strong>Embedding a Font</strong>:<br/><br/>
1) <a class="button" target="_blank" href="http://code.google.com/webfonts">Browse Fonts</a><br/><br/>
2) Click on the Font you want to use.<br/><br/>
3) Click on <strong>"Use this font"</strong>.<br/><br/>
4) Select the <strong>Font Variants</strong> you want to embed.<br/><br/>
5) Copy the <strong>Embed Code</strong>, and paste it on the embed fields on this page.
'
	)
);

$d->typography['Typography'][] = array(
	'name'			=>	'Navigation Font Size <small>(pixels)</small>',
	'id'			=>	'navigation_font_size',
	'type'			=>	'text',
	'default'		=>	'20',
	'selector'		=>	'#header .navigation > ul > li > a',
	'property'		=>	'font-size',
	'description'	=>	'Font Size for the Navigation Menu.<br/><br/> Value expressed as a Percentage with respect to the <a href="#" onclick="$(\'input[name=base_font_size]\').focus(); return false;">Base Font Size</a>.'
);
$d->typography['Typography'][] = array(
	'name'			=>	'Navigation Menu Items Separation  <small>(pixels)</small>',
	'id'			=>	'menu_items_separation',
	'type'			=>	'text',
	'default'		=>	'60',
	'selector'		=>	'#header .navigation > ul > li',
	'property'		=>	'margin-right',
	'description'	=>	'Horizontal separation for the Navigation Menu Items.'
);

$d->typography['Typography'][] = array(
	'name'			=>	'Dropdown Menu Font Size <small>(pixels)</small>',
	'id'			=>	'dropdown_menu_font_size',
	'type'			=>	'text',
	'default'		=>	'14',
	'selector'		=>	'#navigation > li ul ',
	'property'		=>	'font-size',
	'description'	=>	'Font Size for the Body Type.'
);

$d->typography['Typography'][] = array(
	'name'			=>	'Secondary Font',
	'id'			=>	'secondary_font',
	'type'			=>	'text',
	'scope'			=>	'font',
	'default'		=>	"",
	'selector'		=>	'caption, wp-caption p #comments ol.commentlist li.comment .comment-author cite.fn, h1,h2,h3,h4,h5,h6, a.post-edit-link, a.button,
form input[type=text], form input[type=password], form textarea, form input[type=submit], form input[type=reset], #header .navigation > ul > li,
#piecemaker-container .require, #slideshow .title .text, #slideshow-controls, #homepage-center p, #page-header h1.page-title, #page-header span.page-meta, #page-header span.page-meta a,
ul#pagination, #content .post > a.more, #contact-form div.success, div.light_square, #navigation > li ul',
	'description'	=>	'<strong>Embed Code</strong> for the Alternate (Serif) Font.'
);

$d->typography['Typography'][] = array(
	'name'			=>	'Body Font',
	'id'			=>	'body_font',
	'type'			=>	'text',
	'scope'			=>	'font',
	'default'		=>	"",
	'selector'		=>	'body',
	'description'	=>	'<strong>Embed Code</strong> for the Body Type Font.'
);

$d->typography['Typography'][] = array(
	'name'			=>	'Base Font Size <small>(pixels)</small>',
	'id'			=>	'base_font_size',
	'type'			=>	'text',
	'default'		=>	'13',
	'selector'		=>	'body',
	'property'		=>	'font-size',
	'description'	=>	'Font Size for the Body Type.'
);

$d->typography['Typography'][] = array(
	'name'			=>	'Theme Configuration',
	'type'			=>	'checkbox',
	'fields'		=>	array(
						'use_italic_for_widgets'		=>		'Use a Italic Style for the Widgets Type',
						'remove_quote_italic'			=>		'Remove Italic Style from the Homepage Quote/Twitter section',
						'remove_dropdown_italic'		=>		'Remove Italic Style from the Dropdown Menu',
						'remove_page_title_italic'		=>		'Remove Italic Style from the Page Headers',
						),
);

/* Advanced */

$d->typography['Advanced'][] = array(
	'name'			=>	'Load Extra Stylesheets from the Google Fonts API',
	'id'			=>	'extra_stylesheets',
	'type'			=>	'code',
	'rows'			=>	'10',
	'description'	=>	'Use this field if you want to load more stylesheets from the <a href="http://code.google.com/webfonts" target="_blank">Google Fonts Directory</a>. These will be added to the theme\'s <strong>&lt;head&gt;</strong>. <br/>

You can paste the <strong>&lt;link /&gt;</strong> tags to embed the stylesheets. For Example: <br/><br/>

' . "<code>&lt;link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'&gt;<code>"
);

$d->typography['Advanced'][] = array(
	'name'			=>	'Custom CSS Code',
	'id'			=>	'custom_css',
	'type'			=>	'code',
	'rows'			=>	'10',
	'description'	=>	'Add Extra CSS Code to apply your typographic modifications. <br/>Useful if you want to set Font Styles. Add <code>!important</code> to force. For Example:<br/><br/>
		
' . "<code>body { font-family: 'Droid Sans', arial, serif !important; }<code>"
);



// Module Functions

function mod_typography_add_stylesheets() { global $_der, $d;

	foreach( $d->typography['Typography'] as $option) {

		if ($option['scope'] == 'font') {

			$embed = $_der->getval($option['id'], MOD_TYPOGRAPHY);

			if ($embed) { echo $embed . "\n"; }

		}
		
	}

	// Load Extra Stylesheets

	$_der->val('extra_stylesheets', MOD_TYPOGRAPHY);

	echo "\n";

}

function mod_typography_css() { global $_der, $d;

	foreach( $d->typography['Typography'] as $option) {

		$embed_code = $_der->getval($option['id'], MOD_TYPOGRAPHY);

		if ($embed_code) {

			$styles = mod_typography_get_styles($embed_code);

			if ( empty($styles) ) { continue; }

			$this_css = $option['selector'] . ' ' . $styles . "\n";

			$css .= $this_css;

		}

	}

	echo $css;


	// Process Adjustments

	der_style_process_css($d->typography, MOD_TYPOGRAPHY);


	// Serif Typeface check

	if ($_der->getval('use_serif_typeface', MOD_TYPOGRAPHY)) {

		echo "body { font-family: Georgia,serif; }\n";

	}

	// Remove Italic from Page Titles

	if ($_der->getval('remove_page_title_italic', MOD_TYPOGRAPHY ) ) {

		echo "#page-header * { font-style: normal !important; }\n";

	}

	// Remove Italic from Homepage Quote/Twitter

	if ($_der->getval('remove_quote_italic', MOD_TYPOGRAPHY ) ) {

		echo "#homepage-center p { font-style: normal !important; }\n";

	}

	// Remove Italic from Navigation Menu

	if ($_der->getval('remove_dropdown_italic', MOD_TYPOGRAPHY ) ) {

		echo "#header ul#navigation li a { font-style: normal !important; }\n";

	}

	// Serif Typeface for Widgets

	if ( $_der->getval('use_italic_for_widgets', MOD_TYPOGRAPHY) ) {

		echo ".widget > h2.title + * { font-style: italic; !important; }\n";

	}

	// Print Custom CSS

	$_der->val('custom_css', MOD_TYPOGRAPHY);

}

function mod_typography_get_styles($embed) {

	$href = preg_match("/href=('|\")(.*?)('|\")/", $embed, $matches);

	$href = $matches[2];
	
	$urldata = parse_url($href);

	$query = array();

	parse_str($urldata['query'], $query);

	$family = $query['family'];

	if ( empty($family) ) { return null; }

	preg_match('/(.+):(.+)/', $family, $matches);

	$family = ($matches) ? $matches[1] : $family;

	$variants = ($matches) ? explode(',', $matches[2]) : array();

	if ( in_array('bold', $variants) OR in_array('bolditalic', $variants) ) { $font_weight = ' font-weight: bold !important;'; }

	if ( in_array('italic', $variants) OR in_array('bolditalic', $variants) ) { $font_style = ' font-style: italic !important;'; }

	$css = '{ font-family: "' . $family . '" !important;' . $font_weight . $font_style . ' }';

	return $css;

}

?>