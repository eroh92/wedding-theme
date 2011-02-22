<?php /* Contact Form Processing */ global $_der;

	/* Prevent Unauthorized Access */
	if (count($_POST) == 0) { echo '<pre>Forbidden</pre>'; die(); }

	/* CWD to Wordpress Root */
	$cwd = explode('wp-content', getcwd()); $wp_root = $cwd[0]; chdir($wp_root);

	/* Load Wordpress Environment */
	require_once 'wp-load.php';

	/* Make options available */
	global $_der;

	
	$recipient_email = $_der->getval('recipient_email');

	define( 'DISABLE_PHP_MAILER', $_der->checked('disable_phpmailer') );

	der_sendmsg( $recipient_email, DISABLE_PHP_MAILER );

	exit();

?>
