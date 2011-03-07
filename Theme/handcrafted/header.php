<?php

// PROTECTION
if ( ! defined('ABSPATH') ) { die(''); }

global $_der;

define('COLOR_THEME', strtolower($_der->getval('color_theme')));

$color_stylesheet = ( COLOR_THEME != 'default' ) ? 'core/styles/' . COLOR_THEME . '.css,' : null;

?><!DOCTYPE html>
<html dir="<?php bloginfo('text_direction'); ?>" lang="<?php bloginfo('language'); ?>">
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="theme" content="<?php echo COLOR_THEME; ?>" />
<title><?php wp_title('&#150;',true, 'right'); bloginfo('name'); ?></title>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php

	der_add_favicon('favicon');
	
	der_load_stylesheets("style.css, $color_stylesheet core/style.php");

	mod_typography_add_stylesheets();

	der_do_robots();

	wp_head();

	ie_conditionals();

?>

</head>

<body<?php body_id(); ?>>

<div id="topbar-wrap">
&nbsp;
</div><!-- topbar-wrap -->

<!-- + -->

<div id="content-bg-overlay"></div>
<div id="content-wrap-full"><div id="content-wrap-bg"><div class="content-wrap">

<div id="header">

	<div class="logo"><a href="<?php bloginfo('home'); ?>"><img src="<?php theme_logo(); ?>" /></a></div>

	<div class="navigation centeredlist">
		<span class="separator"></span>
<?php

	theme_navigation();

?>
		<span class="separator" style="margin-top: -24px;"></span>
	</div><!-- .navigation -->

</div><!-- header -->

<!-- + -->

<?php  der_load_template(); ?>
