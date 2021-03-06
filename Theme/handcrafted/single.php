<?php

// PROTECTION
if ( ! defined('ABSPATH') ) { die(''); }

get_header(); global $_der, $post; /* @var $_der DerThemeOptions */

// Full Width Layout Check

if ( der_postmeta('use_fullwidth') ) { include(TEMPLATEPATH . '/single_fullwidth.php'); exit(); }

$categories = der_the_category();

$post_image = post_thumb('post_image', 600, 0);

$add_lightbox = der_postmeta('add_lightbox');

$class = ( $add_lightbox OR is_attachment() ) ? 'post add-lightbox' : 'post';

$post_description = der_postmeta('post_description');

?>

<div id="page-header">
	<h1 class="page-title"><?php echo theme_long_title(); ?></h1>
</div><!-- page-header -->

<!-- + -->

<div id="wrapper" class="clearfix">
	<div id="content">

		<div <?php post_class( $class ); ?>>
<?php if ($post_image): ?>
			<div class="post-image">
				<a class="image"><img alt="<?php the_title(); ?>" width="600" src="<?php echo $post_image; ?>" /></a>
			</div>
<?php endif; ?>
			<div class="excerpt clearfix">
<?php

			$post->content_width = 560;

			the_content();

			edit_post_link('&rarr; Edit this Post');

?>
			</div>

<?php if ( comments_open() ): ?>
<div id="comments">
<?php

	comments_template();

?>
</div><!-- comments -->
<?php endif; ?>

		</div><!-- .post -->

	</div><!-- content -->

	<div id="sidebar">
<?php

	der_sidebar('Global, Blog');

?>
	</div><!-- sidebar -->
	<span class="clear"></span>
</div><!-- wrapper -->

<!-- + -->

<?php get_footer(); ?>
