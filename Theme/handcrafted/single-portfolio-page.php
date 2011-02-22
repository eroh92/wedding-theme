<?php

// PROTECTION
if ( ! defined('ABSPATH') ) { die(''); }

get_header(); global $_der, $post; /* @var $_der DerThemeOptions */

$layout = der_postmeta('portfolio_layout');

switch ($layout) {

	case 'Single Column':

		include(TEMPLATEPATH . '/extra/portfolio-1col.php');

		break;

	case 'Two Columns':

		include(TEMPLATEPATH . '/extra/portfolio-2col.php');

		break;

	case 'Three Columns':

		include(TEMPLATEPATH . '/extra/portfolio-3col.php');

		break;

}

get_footer(); ?>