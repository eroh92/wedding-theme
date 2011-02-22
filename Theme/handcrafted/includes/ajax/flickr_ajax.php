<?php

	/* Flickr Ajax */

	if ( empty($_GET['q']) OR empty($_GET['id']) ) { die('<pre>Forbidden</pre>'); }

	$q = base64_decode( urldecode($_GET['q']) );

	$q = str_replace('&amp;', '&', $q);

	$id = $_GET['id'];

	$code = file_get_contents($q);

	$code = str_replace('document.write', 'jQuery("#' . $id . '").prepend', $code);

	echo $code;

?>