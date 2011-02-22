<?php /* portfolio-1col.php */ global $_der, $post;

$portfolio_query = der_portfolio_page_query();

$slider_effect = $_der->getval('slider_effect');

$anim_speed = $_der->getval('slider_speed');

$pause_time = $_der->getval('slider_timeout');

$slider_pieces = $_der->getval('slider_pieces');

$description = der_postmeta('post_description');

?>
<div id="page-header">
	<h1 class="page-title"><?php echo theme_long_title(); ?></h1>
	<?php if ($description): ?><span class="page-meta"><?php echo $description; ?></span><?php endif; ?>
</div><!-- page-header -->

<!-- + -->

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/extra/nivo/jquery.nivo.slider.js"></script>
<script type="text/javascript">
/* <![CDATA[ */

(function($) {

portfolio_images = [];
elementCache = [];

// Nivo Options
nivoOptions = {
	effect:'<?php $_der->val('slider_effect'); ?>',
	slices:<?php echo $slider_pieces; ?>,
	animSpeed: <?php echo $anim_speed; ?>, //Slide transition speed
	pauseTime: <?php echo $pause_time; ?>,
	directionNavHide: true,
	keyboardNav: false,
	pauseOnHover: true,
	manualAdvance: true,
	beforeChange: function(slideIndex, runtimeVars, options) {
		options.elementCache.thisEntry.find('ul.controls li[rel='+slideIndex+']').addClass('active').siblings('.active').removeClass('active');
	},
	afterChange: function(slideIndex, runtimeVars, options) {
		var playData = options.elementCache.playButton.data('button:data');
		if ( playData.queuedInterval ) {
			playData.intervalID = setInterval(function() {
				options.elementCache.nextNav.click();
				playData.queuedInterval = false;
				options.elementCache.playButton.data('button:data', playData);
			}, options.extraOptions.autoplayTimeout );
		}
	},
	extraOptions: {
		autoplayTimeout: <?php echo $pause_time; ?>
	}
}

$(document).ready(function() {

	for ( var i=0; i < portfolio_images.length; i++) {

		if ( portfolio_images[i].length == 0 ) { continue; }
		var allImages = ''; for ( var j=0; j < portfolio_images[i].length; j++ ) { allImages += portfolio_images[i][j]; }
		var thisEntry = $('ul#portfolio-1col > li.gallery').eq(i);
		var thisOptions = object_copy(nivoOptions);
		thisEntry.find('.nivoSlider').append(allImages).nivoSlider(thisOptions);
		elementCache[i] = {
			thisEntry: thisEntry,
			prevNav: thisEntry.find('.nivoSlider a.nivo-prevNav'),
			nextNav: thisEntry.find('.nivoSlider a.nivo-nextNav'),
			controlNav: thisEntry.find('.nivoSlider .nivo-controlNav'),
			playButton: thisEntry.find('button.play') }
		thisOptions.elementCache = elementCache[i];

		thisEntry.find('button.next, button.prev').data('index', i).click(function() {
			var i = $(this).data('index'); // i is outside of context on event
			( $(this).hasClass('next') ) ? elementCache[i].nextNav.click() : elementCache[i].prevNav.click();
		});

		var controls = '';
		for (var k=0; k < portfolio_images[i].length; k++ ) { controls += '<li rel="'+(k+1)+'"></li>'; }
		thisEntry.find('ul.controls').append('<li rel="0" class="active"></li>'+controls).find('li').data('index', i).click(function() {
			var i = $(this).data('index'); // i is outside of context on event
			var runtimeData = elementCache[i].thisEntry.find('.nivoSlider').data('nivo:vars');
			if ( runtimeData.running ) { return false; }
			var playData = elementCache[i].playButton.data('button:data');
			if ( playData.playing ) { clearInterval(playData.intervalID); playData.queuedInterval = true; }
			var rel = $(this).attr('rel');
			$(this).addClass('active').siblings('.active').removeClass('active');
			var thisControl = elementCache[i].controlNav.find('a[rel='+rel+']');
			thisControl.click();

		});

	}

});

})(jQuery);

/* ]]> */
</script>


<ul id="portfolio-1col">
<?php

	$i = 0;

	$remove_post_permalinks = der_postmeta('remove_post_permalinks');

	foreach ($portfolio_query->posts as $post):

		setup_postdata($post);

		$post_video = der_postmeta('post_video');

		$gallery_height = der_postmeta('gallery_height');

		$gallery_height = ($gallery_height) ? $gallery_height : 400; // Default to 400, just in case

		$categories = der_the_category();

		if ($remove_post_permalinks) { $categories = preg_replace('/href="(.*?)"/', '', $categories); }

?>

	<li class="entry clearfix<?php echo (empty($post_video)) ? ' gallery' : ''; ?>">
<?php

		if ( empty($post_video) ): // Post Gallery

			$post_images = get_post_images();

			$image = der_postmeta('post_image');

			if ($image) { array_unshift($post_images, $image); } // Set Post Image to be the first image

			der_clean_array($post_images);

?>
<script type="text/javascript">
/* <![CDATA[ */

	var i = <?php echo $i; ?>;
	portfolio_images[i] = [];
<?php

			$first_image = null;

			foreach ($post_images as $image) {

				$thumb = thumb_src($image, 630, $gallery_height);

				$thumb = str_replace('&amp;','&', $thumb);

				if ($first_image==null) { $first_image = $thumb; }

				else { echo "\t" . 'portfolio_images[i].push(\'<img width="630" height="' . $gallery_height . '" src="' . $thumb . '" />\');' . "\n"; }

			}

			$display_controls = count($post_images) > 1;

			$first_image = str_replace('&', '&amp;', $first_image);

?>

/* ]]> */
</script>
		<div class="slider" style="height: <?php echo $gallery_height; ?>px;">
			<div class="container nivoSlider"><img width="630" height="<?php echo $gallery_height; ?>" src="<?php echo $first_image; ?>" /></div>
			<ul class="controls"></ul>
			<?php if ( $display_controls ): ?><button class="play"></button><?php endif; ?>
		</div>
<?php

			$i++;

		elseif ( preg_match('/^http\:\/\/(www\.)?youtube.com\//i', $post_video) ): // Youtube Video

			$post_video = preg_replace('/\/v\//', '?v=', $post_video);

			$vars = parse_queryurl($post_video);

			$video_id = $vars['v'];

?>
		<div class="slider video youtube">
			<div class="container">
				<object width="630" height="<?php echo $gallery_height; ?>"><param name="movie" value="http://www.youtube.com/v/<?php echo $video_id; ?>?fs=1&amp;hl=en_US&amp;hd=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/<?php echo $video_id; ?>?fs=1&amp;hl=en_US&amp;hd=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="630" height="<?php echo $gallery_height; ?>"></embed></object>
			</div><!-- .container -->
		</div>

<?php

		elseif ( preg_match('/^http\:\/\/(www\.)?vimeo.com\//i', $post_video) ): // Vimeo Video

				preg_match('/vimeo\.com\/(\d+)/', $post_video, $matches);

				$video_id = $matches[1];

?>
		<div class="slider video youtube">
			<div class="container">
				<iframe class="frame" src="http://player.vimeo.com/video/<?php echo $video_id ?>" width="630" height="<?php echo $gallery_height; ?>" frameborder="0"></iframe>
			</div><!-- .container -->
		</div>
<?php

		elseif ( preg_match('/\.mov(\?|&)?/i', $post_video) ): // Quicktime Video

			$data = parse_url($post_video);

			$post_video = "${data['scheme']}://${data['host']}${data['path']}"; // clean url

?>
		<div class="slider video youtube">
			<div class="container">
				<object classid="CLSID:02bf25d5-8c17-4b23-bc80-d3488abddc6b" width="630"height="<?php echo $gallery_height; ?>"
				codebase="http://www.apple.com/qtactivex/qtplugin.cab">
				<param NAME="src" value="<?php echo $post_video; ?>">
				<param NAME="autoplay" value="false">
				<param NAME="controller" value="true">
				<embed src="<?php echo $post_video; ?>" width="630" height="<?php echo $gallery_height; ?>" autoplay="false" controller="true" pluginspage="http://www.apple.com/quicktime/download/"></embed>
				</object>
			</div><!-- .container -->
		</div>
<?php

		endif;

?>
		<div class="post-meta">
			<a class="meta"><?php the_date('m.d.Y'); ?></a><br/>
			<a class="title" <?php if (! $remove_post_permalinks): ?>href="<?php the_permalink(); ?>"<?php endif; ?>><?php the_title(); ?></a>
			<span class="categories"><?php echo $categories; ?></span>
			<?php if ($display_controls): ?><button class="prev"></button><button class="next"></button><?php endif; ?>
		</div>
		<span class="clear"></span>
	</li><!-- .entry -->

	<!-- + -->
<?php

	endforeach;

?>

</ul><!-- portfolio-1col -->

<!-- + -->

<div id="pagination-wrap" class="portfolio-pagination centeredlist">
<?php

	der_pagination();

?>

</div><!-- pagination-wrap -->

<!-- + -->

