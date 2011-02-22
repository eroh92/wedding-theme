<?php /* slider-nivo.php */ global $_der, $post;

	$slideshow_height = $_der->getval('slider_height', MOD_NIVO);

	$slideshow_height = ($slideshow_height < 200) ? 200 : $slideshow_height;

	$slideshow_permalink = $_der->getval('slideshow_permalink', MOD_NIVO);

	$slideshow_query = der_slider_query(); // optimize performance, only get first post

?>

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/extra/nivo/jquery.nivo.slider.js"></script>
<?php

	// Include Nivo's Dynamic JavaScript

	include(TEMPLATEPATH . '/extra/nivo-js.php');

	// Prepare first post

	$post = $slideshow_query[0];

	setup_postdata($post);

	$post_image = post_thumb('post_image', 926, $slideshow_height);

?>

<div id="slideshow" style="height: <?php echo $slideshow_height; ?>px;">
	<a class="container" style="height: <?php echo $slideshow_height; ?>px">
		<?php if ($post_image): ?><img width="926" height="<?php echo $slideshow_height; ?>" src="<?php echo $post_image; ?>" /><?php endif; ?>

	</a><!-- .container -->
	<div class="title"><span><a class="text"<?php echo ($slideshow_permalink) ? ' href="' . get_permalink() . '"' : null; ?>><?php the_title(); ?></a></span></div>
</div><!-- slideshow -->

<!-- + -->

<div id="slideshow-controls" class="centeredlist" style="display: none;">
	<span class="previous"></span>
	<ul></ul>
	<span class="next"></span>
</div><!-- slideshow-controls -->
