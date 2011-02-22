<?php /* portfolio-3col.php */ global $_der, $post;

$portfolio_query = der_portfolio_page_query();

$description = der_postmeta('post_description');

$chunks = array_chunk($portfolio_query->posts, 3);

?>
<div id="page-header">
	<h1 class="page-title"><?php echo theme_long_title(); ?></h1>
	<?php if ($description): ?><span class="page-meta"><?php echo $description; ?></span><?php endif; ?>
</div><!-- page-header -->

<!-- + -->

<ul id="portfolio-3col" class="portfolio-container">

<?php

	$thumbs_height = $_der->getval('three_columns_image_height', MOD_LAYOUT);

	$remove_post_permalinks = der_postmeta('remove_post_permalinks');

	$consider_as_new = der_postmeta('consider_as_new');

	$consider_as_new = ($consider_as_new) ? $consider_as_new : 2;

	$consider_as_new = ($consider_as_new == -1) ? 0 : $consider_as_new;

	$i = $portfolio_query->query_vars['showposts'] * ($paged - 1);

	foreach ($chunks as $chunk):

		$post = $chunk[0];

		setup_postdata($post);

		$new = ( $i < $consider_as_new ) ? t('NEW') :  sprintf("%02d", $i-($consider_as_new-1) );

		$post_video = sanitize_video_url( der_postmeta('post_video') );

		$class = ($post_video) ? ' video' : '';

		$post_image = post_thumb('post_image', 280, $thumbs_height);

		$categories = der_the_category();

		if ($remove_post_permalinks) { $categories = preg_replace('/href="(.*?)"/', '', $categories); }

		$media = ($post_video) ? $post_video : der_postmeta('post_image');

?>
	<li class="entries clearfix">

		<div class="entry left">
			<a rel="lightbox[gallery]" title="<?php the_title(); ?>" class="image" href="<?php echo $media;?>"><span class="overlay<?php echo $class; ?>"></span><?php if ($post_image): ?><img alt="" width="280" height="<?php echo $thumbs_height; ?>" src="<?php echo $post_image; ?>" /><?php else: echo '<span class="placeholder" style="height: ' . $thumbs_height . 'px;"></span>'; endif;?></a>
			<div class="post-meta">
				<a class="meta"><?php echo $new; ?>.</a><br/>
				<a class="title" <?php if (! $remove_post_permalinks): ?>href="<?php the_permalink(); ?><?php endif; ?>"><?php the_title(); ?></a>
				<span class="categories"><?php echo $categories; ?></span>
			</div>
		</div>

<?php

		$i++;

		if ( count($chunk) > 1 ):

			$post = $chunk[1];

			setup_postdata($post);

			$new = ( $i < $consider_as_new ) ? t('NEW') :  sprintf("%02d", $i-($consider_as_new-1) );

			$post_video = sanitize_video_url( der_postmeta('post_video') );

			$class = ($post_video) ? ' video' : '';

			$post_image = post_thumb('post_image', 280, $thumbs_height);

			$categories = der_the_category();

			if ($remove_post_permalinks) { $categories = preg_replace('/href="(.*?)"/', '', $categories); }

			$media = ($post_video) ? $post_video : der_postmeta('post_image');

?>
		<div class="entry center">
			<a rel="lightbox[gallery]" title="<?php the_title(); ?>" class="image" href="<?php echo $media;?>"><span class="overlay<?php echo $class; ?>"></span><?php if ($post_image): ?><img alt="" width="280" height="<?php echo $thumbs_height; ?>" src="<?php echo $post_image; ?>" /><?php else: echo '<span class="placeholder" style="height: ' . $thumbs_height . 'px;"></span>'; endif;?></a>
			<div class="post-meta">
				<a class="meta"><?php echo $new; ?>.</a><br/>
				<a class="title" <?php if (! $remove_post_permalinks): ?>href="<?php the_permalink(); ?><?php endif; ?>"><?php the_title(); ?></a>
				<span class="categories"><?php echo $categories; ?></span>
			</div>
		</div>

<?php

			$i++;

		endif;

		if ( count($chunk) > 2 ):

			$post = $chunk[2];

			setup_postdata($post);

			$new = ( $i < $consider_as_new ) ? t('NEW') :  sprintf("%02d", $i-($consider_as_new-1) );

			$post_video = sanitize_video_url( der_postmeta('post_video') );

			$class = ($post_video) ? ' video' : '';

			$post_image = post_thumb('post_image', 280, $thumbs_height);

			$categories = der_the_category();

			if ($remove_post_permalinks) { $categories = preg_replace('/href="(.*?)"/', '', $categories); }

			$media = ($post_video) ? $post_video : der_postmeta('post_image');

?>
		<div class="entry right">
			<a rel="lightbox[gallery]" title="<?php the_title(); ?>" class="image" href="<?php echo $media;?>"><span class="overlay<?php echo $class; ?>"></span><?php if ($post_image): ?><img alt="" width="280" height="<?php echo $thumbs_height; ?>" src="<?php echo $post_image; ?>" /><?php else: echo '<span class="placeholder" style="height: ' . $thumbs_height . 'px;"></span>'; endif;?></a>
			<div class="post-meta">
				<a class="meta"><?php echo $new; ?>.</a><br/>
				<a class="title" <?php if (! $remove_post_permalinks): ?>href="<?php the_permalink(); ?><?php endif; ?>"><?php the_title(); ?></a>
				<span class="categories"><?php echo $categories; ?></span>
			</div>
		</div>

<?php

			$i++;

		endif;

?>
	</li><!-- .entries -->

	<li class="clear"></li>

<?php

	endforeach;

?>
</ul><!-- portfolio-2col -->

<!-- + -->

<div id="pagination-wrap" class="portfolio-pagination centeredlist">
<?php

	der_pagination();

?>

</div><!-- pagination-wrap -->

<!-- + -->

