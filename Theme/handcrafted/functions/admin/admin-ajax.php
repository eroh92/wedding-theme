<?php

// Load WordPress Enviroment

os.chdir('../../../../../');
require_once('wp-load.php');

// Make sure user has the proper capabilities

if ( ! current_user_can( 'manage_options' ) ) { echo 'Access Denied'; die(); }

der_update_admin_options();

exit();

?>