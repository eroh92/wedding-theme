<?php require_once('../../../../wp-load.php'); header('Content-Type: text/css'); global $d, $_der;

if ( defined('DER_STYLE_HEADER') ) { echo DER_STYLE_HEADER . "\n\n"; } else { echo "/* Dynamic CSS Code */\n\n"; }

der_style_process_css($d->options);

der_do_action('der_style');

$_der->val('custom_css');

?>