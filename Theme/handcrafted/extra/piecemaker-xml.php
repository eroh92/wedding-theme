<?php /* piecemaker-xml.php */

	/* @var $_der DerThemeOptions */

	// Load WordPress environment
	$cwd = explode('wp-content', getcwd()); $wp_root = $cwd[0]; chdir($wp_root); require_once 'wp-load.php';

	header("Content-Type: text/xml");

	global $_der, $post;

	$_der->set_options_context(MOD_PIECEMAKER);

	$height = $_der->getval('slider_height');

	$height = ($height < 200) ? 200 : $height;

	$segments = $_der->getval('segments');

	$autoplay = $_der->getval('autoplay');

	$tween_time = $_der->getval('tween_time');

	$tween_delay = $_der->getval('tween_delay');

	$z_distance = $_der->getval('z_distance');

	$tween_type = $_der->getval('tween_type');

	$expand = $_der->getval('expand');

	$_der->reset_options_context();

	switch ( strtolower( $_der->getval('color_theme') ) ) {

		case 'default': $inner_color = '#747474'; break;

		case 'dark': $inner_color = '#2c2c2c'; break;

		case 'wood': $inner_color = '#21201d'; break;

	}

	$text_background = '#2c2c2c';

	echo '<?xml version="1.0" encoding="utf-8"?>';

?>
<Piecemaker>
	
  <Settings>
    <imageWidth>924</imageWidth>
    <imageHeight><?php echo $height - 1; ?></imageHeight>
    <segments><?php echo $segments; ?></segments>
    <tweenTime><?php echo $tween_time; ?></tweenTime>
    <tweenDelay><?php echo $tween_delay; ?></tweenDelay>
    <tweenType><?php echo $tween_type; ?></tweenType>
    <zDistance><?php echo $z_distance; ?></zDistance>
    <expand><?php echo $expand; ?></expand>
    <innerColor><?php echo str_replace('#', '0x', $inner_color); ?></innerColor>
    <textBackground><?php echo str_replace('#', '0x', $text_background); ?></textBackground>
    <shadowDarkness>100</shadowDarkness>
    <textDistance>25</textDistance>
    <autoplay><?php echo $autoplay; ?></autoplay>
  </Settings>

<?php

	$slider_query = der_slider_query();

	foreach ($slider_query as $post):

		setup_postdata($post);

		$post_image = post_thumb('post_image', 926, $height);

		if ( ! $post_image ) { continue; }

		$post_image = strstr($post_image, 'timthumb.php?src=');

?>
<Image Filename="<?php echo $post_image; ?>"></Image>
<?php

	endforeach;

?>
</Piecemaker>