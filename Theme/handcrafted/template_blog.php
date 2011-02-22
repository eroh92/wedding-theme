<?php

// PROTECTION
if ( ! defined('ABSPATH') ) { die(''); }

global $_der, $post, $wp_query; /* @var $_der DerThemeOptions */

if ( is_archive() OR is_search() ) {

	$blog_excerpt = true; // Prevent full contents from being displayed on archive pages

} else {

	// Blog Page

	if ( $_der->equal('blog_layout', 'Full Width') ) { include(TEMPLATEPATH . '/template_blog_fullwidth.php'); exit(); }

	$description = der_postmeta('post_description');

	$blog_excerpt = $_der->equal('blog_excerpt', 'Excerpt');

}

?>

<div id="page-header">
	<h1 class="page-title"><?php echo ( is_archive() OR is_search() ) ? $title : theme_long_title(); ?></h1>
	<?php if ($description): ?><span class="page-meta"><?php echo $description; ?></span><?php endif; ?>
</div><!-- page-header -->

<!-- + -->

<div id="wrapper" class="clearfix">

	<ul id="content" class="layout-list">

<?php

	// Blog Query

	$blog_query = ( is_archive() OR is_search() ) ? $wp_query : der_blog_query();

	foreach ($blog_query->posts as $post):

		setup_postdata($post);

		$categories = der_the_category();

		$image_height = $_der->getval('blog_posts_height', MOD_LAYOUT);

		$image_height = ($image_height == '-1') ? 0 : $image_height; // Auto image height

		$post_image = post_thumb('post_image', 600, $image_height);

		$post->content_width = 560;

?>
		<li <?php ($post_image) ?  post_class('post clearfix') : post_class('post clearfix no-image-post'); ?>>
<?php

	if ($post_image):

?>
			<div class="post-image">
				<div class="meta">
					<span class="date"><?php theme_date_links(); ?></span>
					<a class="post-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</div>
				<a class="image" href="<?php the_permalink(); ?>"><img alt="<?php the_title(); ?>" width="600" <?php if ($image_height): ?>height="<?php echo $image_height; ?>"<?php endif; ?> src="<?php echo $post_image; ?>" /></a>
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
				<?php if ( comments_open() ): ?><a class="comments" href="<?php the_permalink(); ?>#comments"><?php comments_number(t('No Comments'), t('One Comment'), t('% Comments') ); ?></a><?php endif; ?>
			</div><?php endif; ?>
			<div class="excerpt">
				<?php ( $blog_excerpt ) ? the_excerpt() : the_content(); ?>
			</div>
			<?php if ( $blog_excerpt ): ?><a class="more" href="<?php the_permalink(); ?>"><?php _t('&raquo; Continue Reading'); ?></a><?php else: ?><br/><br/><?php endif; ?>
		</li><!-- .post -->

<?php

	endforeach;

	if ( $blog_query->found_posts == 0 ):

?>
		<li>
			<h5 style="margin-top: 20px;"><?php _t('Nothing Found...'); ?></h5>
			<p><?php _t('Sorry, but nothing matched your criteria. Please try again with some different keywords.'); ?></p>

			<form class="search-form" action="<?php get_bloginfo('home'); ?>" method="get">
				<p>
					<input type="text" name="s" value="<?php echo ( is_search() ) ? attribute_escape( get_search_query() ) : t('Search...'); ?>" style="width: 250px;" />
					<input type="submit" value="Go" />
				</p>
			</form>

		</li>
<?php

	endif;

?>
	</ul><!-- content -->

	<div id="sidebar">

<?php

	der_sidebar('Global, Blog');

?>

	</div><!-- sidebar -->
	<span class="clear"></span>
</div><!-- wrapper -->

<!-- + -->

<div id="pagination-wrap" class="centeredlist">
<?php

	( is_archive() OR is_search() ) ? der_pagination($wp_query) : der_pagination();
	
?>
</div><!-- pagination-wrap -->

<!-- + -->

<?php get_footer(); ?>