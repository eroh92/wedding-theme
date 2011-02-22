<?php

// PROTECTION
if ( ! defined('ABSPATH') ) { die(''); }

define('THE_SEARCH_QUERY', wp_specialchars($s, 1) );

include(TEMPLATEPATH . '/archive.php');

?>