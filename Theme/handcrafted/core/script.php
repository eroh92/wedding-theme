<?php require_once('../../../wp-load.php'); header('Content-Type: text/javascript'); global $d, $_der;

/* @var $_der DerThemeOptions */

if ( defined('DER_SCRIPT_HEADER') ) { echo DER_SCRIPT_HEADER . "\n\n"; } else { echo "/* Dynamic JavaScript Code */\n\n"; }

echo 'template_directory = "' . get_bloginfo('template_directory') . '";' . "\n";
echo 'wp_home = "' . get_bloginfo('home') . '";' . "\n";

foreach ( $d->options as $section => $null ) {

	foreach ( $d->options[$section] as $option ) {

		switch ( $option['type'] ) {

			case 'text':
			case 'select':

				$mime = $option['mime'];

				$val = $_der->getval($option['id']);

				if ( $mime != 'javascript' ) { break; }

				elseif ( empty($val) AND isset($option['default']) AND isset($option['variable']) ) {

					echo $option['variable'] . " = null;\n"; continue;
					
				} elseif ( empty($val) ) { 
					
					break;

				}

				$val = str_replace('"', '\"', $val);


				// Process boolean values

				if ( empty($val) ) { $val = ( $option['default'] ) ? $option['default'] : 'null'; }

				$val = ($val == 'enabled' OR $val == 'on') ? 'true' : $val;
				
				$val = ($val == 'disabled' OR $val == 'off') ? 'false' : $val;

				$val = ( $val == 'true' OR $val == 'false' OR is_numeric($val) ) ? $val : '"' . $val . '"';

				if ( $mime == 'javascript' && $option['variable'] ) {

					echo $option['variable'] . ' = ' . $val . ';' . "\n";

				}

				break;

			case 'checkbox':

				$mime = $option['mime'];

				if ( $mime != 'javascript' ) { break; }

				foreach ($option['fields'] as $key => $val) {

					echo $key . ' = '; print_bool($_der->checked($key)); echo ";\n";
					
				}

				break;
		}

	}

}

der_do_action('der_script');

$custom_js = $_der->getval('custom_javascript');

if ($custom_js) {

	echo '(function($) { $(document).ready(function(){
' . $custom_js . '
}); })(jQuery);
';

}

?>