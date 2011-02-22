<?php

// PROTECTION
if ( ! defined('ABSPATH') ) { die(''); }

global $_der, $post; /* @var $_der DerThemeOptions */

$categories = der_the_category();

$image_width = $_der->getval('fw_post_image_width', MOD_LAYOUT);

$image_width = ( $image_width > 830 ) ? 830 : $image_width; // Don't accept values above 830

$post_image = post_thumb('post_image', $image_width, 0);

$add_lightbox = der_postmeta('add_lightbox');

$class = ( $add_lightbox ) ? 'post add-lightbox' : 'post';

$post_description = der_postmeta('post_description');

?>

<div id="page-header">
	<h1 class="page-title"><?php the_title(); ?></h1>
	<span class="page-meta"><?php if (!$post_description): ?>Published by <?php echo der_link( get_the_author(), der_author_link() ); ?> on <?php echo der_month_link('F'); ?> <?php echo der_day_link('j') ?>, <?php echo der_year_link('Y'); ?>. <?php if ($categories): ?>Filed under <?php echo $categories; ?><?php endif; else: echo $post_description; endif; ?></span>
</div><!-- page-header -->

<!-- + -->

<div id="wrapper" class="clearfix  wrapper-full-width">
	<div id="content">

		<div <?php post_class( $class ); ?>>
<?php if ($post_image): ?>
			<div class="post-image">
				<a class="image"><img alt="<?php the_title(); ?>" width="<?php echo $image_width; ?>" src="<?php echo $post_image; ?>" /></a>
			</div>
<?php endif; ?>

			<div class="excerpt clearfix">

<?php

			$post->content_width = 850;

			the_content();

			edit_post_link('&rarr; Edit this Post');

?>

			</div>

		</div><!-- .post -->

<?php if ( comments_open() ): ?>
<div id="comments">
<?php

	comments_template();

?>
</div><!-- comments -->
<?php endif; ?>

	</div><!-- content -->

	<span class="clear"></span>
</div><!-- wrapper -->

<!-- + -->

<?php get_footer(); ?>