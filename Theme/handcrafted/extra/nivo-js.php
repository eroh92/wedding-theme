<?php /* slider-nivo-js.php */ global $_der, $post;

	/* @var $_der DerThemeOptions */

	$_der->set_options_context(MOD_NIVO);

	$enable_direction_nav = $_der->getval('enable_direction_nav');

	$direction_nav_autohide = $_der->getval('direction_nav_autohide');

	$pause_on_hover = $_der->getval('pause_on_hover');

	$manual_advance = $_der->getval('manual_advance');

	$anim_speed = $_der->getval('slider_speed');

	$pause_time = $_der->getval('slider_timeout');

	$slider_effect = $_der->getval('slider_effect');

	$slider_pieces = $_der->getval('slider_pieces');

	$_der->restore_options_context();

?>
<script type="text/javascript">

(function($) {

// Configuration Options
sliderOptions = {
	usePermalink: <?php echo ($slideshow_permalink) ? 'true' : 'false'; ?>,
	directionNavEnable: <?php print_bool($enable_direction_nav); ?>,
	directionNavHide: <?php print_bool($direction_nav_autohide); ?>,
	pauseOnHover: <?php print_bool($pause_on_hover); ?>,
	manualAdvance: <?php print_bool($manual_advance); ?>,
	animSpeed: <?php echo $anim_speed; ?>,
	pauseTime: <?php echo $pause_time; ?>
}

$(document).ready(function() {

	// Nivo Options
	nivoOptions = {
		effect:'<?php echo $slider_effect; ?>',
		slices:<?php echo $slider_pieces; ?>,
		animSpeed: sliderOptions.animSpeed, //Slide transition speed
		pauseTime: sliderOptions.pauseTime,
		directionNavHide: sliderOptions.directionNavHide,
		keyboardNav: true,
		pauseOnHover: sliderOptions.pauseOnHover,
		manualAdvance: sliderOptions.manualAdvance,
		beforeChange: function(slideIndex, runtimeVars, options) {

			// jQuery recognizes the siblings after chaining.
			options.slideshowControls.find('ul li[rel='+slideIndex+']').addClass('current').siblings('li.current').removeClass('current');
			options.metaElements.title.stop().animate({opacity: 0, width: get_title_width(slideIndex)}, options.animSpeed*0.60);
			setTimeout(function() {
				options.metaElements.title.html(options.slideshowData[slideIndex].title).stop().animate({opacity: 1}, options.animSpeed*0.40);
				if (sliderOptions.usePermalink) {
					options.metaElements.title.add(options.slideshowContainer).attr('href', options.slideshowData[slideIndex].permalink);
				}
			}, options.animSpeed*0.60);

			options.previousSlide = slideIndex;

		}
	};

	// Bind beforeChange to afterLoad
	nivoOptions.afterLoad = function(runtimeVars, options) {
		options.controlNavElement = $('#slideshow .nivo-controlNav'); // Only available after the Nivo has loaded
		options.previousSlide = 0;
		if (!sliderOptions.directionNavEnable) { nivoOptions.slideshowContainer.find('a.nivo-nextNav, a.nivo-prevNav').css('visibility', 'hidden'); }
		nivoOptions.nextNav = nivoOptions.slideshowContainer.find('a.nivo-nextNav');
		nivoOptions.prevNav = nivoOptions.slideshowContainer.find('a.nivo-prevNav');
		$('#slideshow-controls span.previous').click(function() { nivoOptions.prevNav.click(); return false; });
		$('#slideshow-controls span.next').click(function() { nivoOptions.nextNav.click(); return false; });
	}

	// Gather Slideshow Data
	nivoOptions.slideshowData = [];
<?php

	foreach ($slideshow_query as $post):

		setup_postdata($post);

?>

	nivoOptions.slideshowData.push({
		title: '<?php the_title(); ?>',
		permalink: '<?php the_permalink(); ?>',
		image: '<img width="926" height="<?php echo $slideshow_height; ?>" src="<?php _post_thumb('post_image', 926, $slideshow_height); ?>" />'});
<?php

	endforeach;

?>

	// Clone title container
	title_container_clone();

	// Add Meta Elements
	nivoOptions.metaElements = {
		title: $('#slideshow .title a.text')
	}

	// Create pager items
	setup_nivo_pager();

	// Add Images
	var allImages = '';
	for ( var i=1; i < nivoOptions.slideshowData.length; i++ ) { allImages += nivoOptions.slideshowData[i].image; }

	// Configure Slideshow
	nivoOptions.slideshowContainer = $('#slideshow:not(.clone) a.container'); // Chaining can't be done in this context
	nivoOptions.slideshowContainer.append(allImages).nivoSlider(nivoOptions);

});

function setup_nivo_pager() {
	var slideshow_controls = nivoOptions.slideshowControls = $('#slideshow-controls');
	slideshow_controls.show();
	var allItems = '';

	for (var i=0; i < nivoOptions.slideshowData.length; i++) { var theClass = (i==0) ? ' class="current"' : ''; allItems += '<li rel="' + i + '"'  + theClass + '>' + (i+1) + '</li>'; }

	slideshow_controls.find('ul').append(allItems);
	
	slideshow_controls.find('ul li').click(function() {
		var rel = $(this).html();
		nivoOptions.controlNavElement.find('a[rel=' + (rel-1) + ']').click();
		return false;
	});
}

function title_container_clone() {
	$('body').prepend('<div id="cache" style="position: absolute; top: 0; left: 0; z-index: -9999; visibility: hidden;"></div>');
	var cache = $('#cache');
	cache.append( $('#slideshow').clone().addClass('clone') );
	slideshow_title = cache.find('.title a.text');
	slideshow_title_widths = [];
	
	for ( var i=0; i < nivoOptions.slideshowData.length; i++) {
		var title = nivoOptions.slideshowData[i].title;
		var w = slideshow_title.html(title).width();
		slideshow_title_widths.push(w + 'px');
	}
	
	cache.remove(); // free memory

}

function get_title_width(slideIndex) {
	// Return cached title width data (improved performance)
	return slideshow_title_widths[slideIndex];
}

})(jQuery);

</script>
