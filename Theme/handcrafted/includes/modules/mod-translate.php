<?php /* mod-translate */ global $d, $_der;

/* @var $_der DerThemeOptions */

// Dynamic Internationalization Module
// ===================================

// Create Module Instance
$translate_module = new DerModule('translate', 'Translate Theme');

$default_options = mod_translate_gen_localization();

$translate_module->set_default_options( $default_options );

$translate_module->set_interface_callback('mod_translate_interface');



// Function Definitions

function mod_translate_gen_localization() {

	// Enable caching

	if ( $GLOBALS['DER_TRANSLATE_STRINGS'] ) { return $GLOBALS['DER_TRANSLATE_STRINGS']; }

	$include_dirs = array( TEMPLATEPATH );

	$extra_include_dirs = array('/functions', '/includes', '/includes/modules');

	foreach ($extra_include_dirs as $dir ) { $include_dirs[] = TEMPLATEPATH . $dir; }

	$ignore_files = array('/includes/modules/mod-translate.php');

	for ($i=0; $i<count($ignore_files); $i++) { $ignore_files[$i] = TEMPLATEPATH . $ignore_files[$i]; }

	$theme_files = array();

	foreach ( $include_dirs as $dir ) {

		$files = der_compat_scandir($dir);

		foreach ( $files as $file ) {

			$fpath = $dir . '/' . $file;

			if ( in_array($fpath, $ignore_files) ) { continue; }

			$pathinfo = pathinfo($fpath);

			if ( $pathinfo['extension'] == 'php' ) {

				$theme_files[] = $dir . '/' . $file;

			}

		}

	}

	$translations = array();

	foreach ( $theme_files as $file ) {

		$buf = file_get_contents($file);

		$buf = preg_replace('/\<\?php|\?\>/', '', $buf);

		$buf = preg_replace('/( |\n|\s+|\(|\[)(t|_t)\(/', "\n[[", $buf);

		$buf = preg_replace('/\)(\;)?/', "]]\n", $buf);

		preg_match_all('/\[\[.+\\]\]/', $buf, $matches);

		$matches = $matches[0];

		for ( $i=0; $i < count($matches); $i++) {

			$matches[$i] = trim(preg_replace('/\[\[(\'|\")|(\'|\")\]\]/', '', $matches[$i]));

		}

		$translations = array_merge($translations, $matches);

	}

	$translations = array_unique($translations);

	$base64_translations = array();

	foreach ( $translations as $translation) {

		$k = base64_encode($translation);

		$base64_translations[ $k ] = $translation;

	}

	$GLOBALS['DER_TRANSLATE_STRINGS'] = $base64_translations;

	return $base64_translations;

}

function mod_translate_interface($instance) { global $_der;

	/* Render the HTML for each translation  */

	$translations = mod_translate_gen_localization();

	$translations = array_flip($translations);

	ksort($translations);

	foreach ( $translations as $trans => $id ) {

		if ( preg_match('/\[\[|\]\]/', $trans) ) { continue; }

		echo '
	<div class="option clearfix">

		<h3 class="reset">' . $trans . '</h3>

		<input type="text" name="' . $id . '" value="' . $_der->getval($id) . '" />

	</div><!-- option -->
';

	}

	if ( empty($translations) ) {

		echo '<div class="option"></div>';
	}

}

function mod_translate_translate($string, $before=null, $after=null) { global $_der;

	return $before . $_der->getval( base64_encode(trim($string)), DER_TRANSLATE_DB_ENTRY ) . $after;

}

?>