<?php

// PROTECTION
if ( ! defined('ABSPATH') ) { die(''); }

global $_der, $post; /* @var $_der DerThemeOptions */

$description = der_postmeta('post_description');

$blog_excerpt = $_der->equal('blog_excerpt', 'Excerpt');

?>

<div id="page-header">
	<h1 class="page-title"><?php echo theme_long_title(); ?></h1>
</div><!-- page-header -->

<!-- + -->

<div id="wrapper" class="clearfix wrapper-full-width">

	<ul id="content" class="layout-list">

<?php

	// Blog Query

	$blog_query = der_blog_query();

	foreach ($blog_query->posts as $post):

		setup_postdata($post);

		$categories = der_the_category();

		$image_height = $_der->getval('blog_fw_posts_height', MOD_LAYOUT);

		$image_height = ($image_height == '-1') ? 0 : $image_height; // Auto image height

		$post_image = post_thumb('post_image', 890, $image_height);

		$post->content_width = 850;

?>
		<li <?php ($post_image) ?  post_class('post clearfix') : post_class('post clearfix no-image-post'); ?>>
<?php

	if ($post_image):

?>
		<li class="post clearfix">
			<div class="post-image">
				<div class="meta">
					<span class="date"><?php theme_date_links(); ?></span>
					<a class="post-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</div>
				<a class="image" href="<?php the_permalink(); ?>"><img alt="<?php the_title(); ?>" width="890" <?php if ($image_height): ?>height="<?php echo $image_height; ?>"<?php endif; ?> src="<?php echo $post_image; ?>" /></a>
			</div>
<?php

	else:

?>
			<div class="meta">
				<span class="date"><?php theme_date_links(); ?></span>
				<a class="post-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</div>
<?php

	endif;

	if ( $post->post_type != 'page' ):

?>
			<div class="category-comments clearfix">
				<span class="categories"><?php echo $categories; ?></span>
				<a class="comments" href="<?php the_permalink(); ?>#comments"><?php comments_number(t('No Comments'), t('One Comment'), t('% Comments') ); ?></a>
			</div><?php endif; ?>
			<div class="excerpt">
				<?php ( $blog_excerpt ) ? the_excerpt() : the_content(); ?>
			</div>
			<?php if ($blog_excerpt): ?><a class="more" href="<?php the_permalink(); ?>"><?php _t('&raquo; Continue Reading'); ?></a><?php else: ?><br/><br/><?php endif; ?>
		</li><!-- .post -->

<?php

	endforeach;

?>
	</ul><!-- content -->

	<span class="clear"></span>

</div><!-- wrapper -->

<!-- + -->

<div id="pagination-wrap" class="centeredlist">
<?php

	der_pagination();

?>
</div><!-- pagination-wrap -->

<!-- + -->

<?php get_footer(); ?>
