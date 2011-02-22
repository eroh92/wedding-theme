
/*  JS Core Functions
    Copyright (c) Ernesto Mendez 2010.
    http://der-design.com  */

(function($) {

$(document).ready(function() {

	setup_theme_data();

	widget_enhancements();

	menu_enhancements();

	setup_portfolio();

	init_lightbox();

	js_effects();

	homepage_twitter_feed();

	init_flickr_widgets();

	form_enhancements();

});

function setup_theme_data() {

	// Opacity setting for Portfolio Overlays

	theme_data = {
		'default': {
			overlay_opacity: '0.9',
			topbar_icon_opacity: '0.65',
			topbar_submit_opacity: '0.5',
			lightbox_theme: 'light_square',
			lightbox_opacity: '0.85'},
		'dark': {
			overlay_opacity: '0.6',
			topbar_icon_opacity: '0.5',
			topbar_submit_opacity: '0.35',
			lightbox_theme: 'light_square',
			lightbox_opacity: '0.80'},
		'wood': {
			overlay_opacity: '0.5',
			topbar_icon_opacity: '0.4',
			topbar_submit_opacity: '0.35',
			lightbox_theme: 'light_square',
			lightbox_opacity: '0.65'},

		'topbar_search_value' : $('#topbar .search input[type=text]').attr('title'),
		'topbar_search_input' : $('#topbar .search input[type=text]').removeAttr('title'),
		'topbar_search' : $('#topbar .search'),
		'topbar_input_focused' : false,
		'topbar_timeoutID' : null,
		'currently_hovered_title' : null,

		'topbar_search_mouseover_callback': function(context) {
			try {clearTimeout(theme_data.topbar_timeoutID);} catch(e) { /* pass */ }
			var textInput = theme_data.topbar_search_input;
			if ( $.trim( textInput.val() ) == '' ) {textInput.val(theme_data.topbar_search_value);}
			$(context).find('input[type=submit]').stop().animate({opacity: topbar_submit_target_opacity}, 400);
			$(context).stop().animate({backgroundPosition: '0px 0px'}, {duration:300, easing: 'easeOutQuart', complete: function() {$(context).find('input[type=text]').stop().animate({opacity: 1}, 100)}} );
		},

		'topbar_search_mouseout_callback': function(context) {
			$(context).find('input[type=text]').stop().animate({opacity: 0},{duration:100, complete: function() {$(context).stop().animate({backgroundPosition: '300px 0px'}, 300, 'easeInQuad');}});
			$(context).find('input[type=submit]').stop().animate({opacity: topbar_submit_initial_opacity}, 400);
		}
	}

	overlay_opacity = theme_data[color_theme].overlay_opacity;

	topbar_icons_initial_opacity = $('#topbar ul.links li').css('opacity');

	topbar_icons_target_opacity = theme_data[color_theme].topbar_icon_opacity;

	topbar_submit_initial_opacity = $('#topbar .search input[type=submit]').css('opacity');

	topbar_submit_target_opacity = theme_data[color_theme].topbar_submit_opacity;

}

function widget_enhancements() {

	// Set links as buttons when applicable

	var link_re = /^(\s+)?\<a(.+)?\>(.+)?\<\/a\>(\s+)?$/i;

	var link_ul_re = /^(\s+)?\<a(.+)?\>(.+)?\<\/a\>(\s+)?\<ul(.+)?\>/i;

	var children, counter, i, html, padding_left, parents, parent;

	parents = [];

	$('.widget ul').each(function() {

			children = $(this).find('li');

			counter = 0;

			for (i=0; i < children.length; i++) {

				html = $(children[i]).html();

				if ( html.match(link_re) ) {counter++;}

				if ( html.match(link_ul_re) ) {

					counter++;parents.push(children[i]);

				}

			}

			if ( counter == children.length ) {

				if ( $(this).parents('#footer').length ) {

					// Footer Context

					children.addClass('block-link')

					border_color = children.css('borderBottomColor');

					padding_left = $(children[0]).find('a').css('paddingLeft');

					$(this).find('ul').css({marginLeft: padding_left});

					children.find('ul li:first-child').css({marginTop:'0'});

				} else {

					// Sidebar Context

					$(this).children('li:first-child').css({paddingTop: '0'});


				}

			}

	});

}

function menu_enhancements() {

	// Remove Title from links

	$('#navigation li a').removeAttr('title');

	// Menu parent class

	$('#navigation > li ul > li ul').each(function() {$(this).parent().addClass('menu_parent');});

	// Alternating Stripes

	$('#navigation > li ul').each(function() {$(this).children('li:even').each(function(i) {$(this).children('a').addClass('alt');});});

	// Hover effect

	if (  is_apple_safari() && $('#piecemaker-wrap').length  ) { // Prevent safari bug with flash objects and slideDown effect

		$('#navigation li').hover(function() {

			$(this).find('ul:first').css({visibility: 'visible',display: 'none'}).fadeIn(300,'easeOutSine');

		}, function() {

			$(this).find('ul:first').css({visibility: 'hidden'});

		});

	} else { // Other browsers behave just fine

		$('#navigation li').hover(function() {

			$(this).find('ul:first').css({visibility: 'visible',display: 'none'}).slideDown(400,'easeOutSine');

		}, function() {

			$(this).find('ul:first').css({visibility: 'hidden'});

		});

	}

}

function setup_portfolio() {

	// If JS Enabled, show the overlay

	$('#portfolio-2col .entry a.image span.overlay, #portfolio-3col .entry a.image span.overlay').css({visibility:'visible'});

	// Hover Effect for overlays

	$('#portfolio-2col .entry a.image, #portfolio-3col .entry a.image').hover(function() {

		$(this).find('span.overlay').stop().animate({opacity:overlay_opacity},450,'easeInOutQuad');

	}, function() {

		$(this).find('span.overlay').stop().animate({opacity:'0'},450,'easeInOutQuad');

	});

	if (NOT_IE) {
		
		$('#portfolio-1col .entry button.play').css({opacity: 0, visibility: 'visible'})

	}

	// Click Event for entry play button

	$('#portfolio-1col .entry button.play').data('button:data', {playing: false, intervalID: false, queuedInterval: false}).click(function() {

		var data = object_copy( $(this).data('button:data') ); // Work with an actual copy, not with a pointer

		if ( data.playing ) {

			$(this).css('backgroundPosition', 'center left');

			data.playing = false;

			if ( data.intervalID ) {clearInterval(data.intervalID);data.intervalID = false;}

			$(this).data('button:data', data);

		} else {

			$(this).css('backgroundPosition', 'center right');

			data.playing = true;

			var next = $(this).parents('.entry').find('button.next').click();

			data.intervalID = setInterval(function() {

				next.click();

			}, nivoOptions.extraOptions.autoplayTimeout);

			$(this).data('button:data', data);

		}

	});

	// Click Event for entry play button

	if (NOT_IE) {

		$('#portfolio-1col .entry').hover(function() {

			var timeoutID = $(this).data('timeoutID');

			if ( timeoutID ) {clearTimeout(timeoutID);}

			$(this).find('button.play').stop().animate({opacity: 1}, 500);

		}, function() {

			var timeoutID = setTimeout(function(context) {

				$(context).find('button.play').stop().animate({opacity: 0}, 300);

			}, 1000, this);

			$(this).data('timeoutID', timeoutID);

		});

	}

}

function add_lightbox() {

	$('.add-lightbox').each(function() {

		$(this).find('a > img').each(function() {

			if ( $(this).parents('.post-image').length || $(this).parents('.hc-gallery').length ) {return true;}

			var gallery_id = $(this).parents('.gallery').attr('id');

			( gallery_id ) ? $(this).parent().attr('rel', 'lightbox[' + gallery_id + ']') : $(this).parent().attr('rel', 'lightbox[post]');

		});

	});

}

function init_lightbox() {

	// Setup Gallery lightboxes prior to initialization

	add_lightbox();

	// TODO: Fix vimeo links

	// Lightbox init code

	var lightbox_theme = theme_data[color_theme].lightbox_theme;

	var lightbox_opacity = theme_data[color_theme].lightbox_opacity;

	$(document).ready(function(){
		$("a[rel^='lightbox']").each(function(i) {

			// Remove title attribute and store in element's data attributes

			var  title = $(this).attr('title');

			$(this).data('title', $(this).attr('title')).removeAttr('title').find('img').removeAttr('title');

			// Fix Vimeo video links

			try {

				var href = $(this).attr('href');

				if ( href.match(/http\:\/\/www.vimeo.com\//) ) {$(this).attr('href', href.replace('/www.vimeo.com/', '/vimeo.com/') );}

			} catch(e) { }

		}).prettyPhoto({
			animation_speed: 'normal', /* fast/slow/normal */
			slideshow: false, /* false OR interval time in ms */
			autoplay_slideshow: false, /* true/false */
			opacity: lightbox_opacity, /* Value between 0 and 1 */
			show_title: true, /* true/false */
			allow_resize: true, /* Resize the photos bigger than viewport. true/false */
			default_width: 500,
			default_height: 344,
			counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
			theme: lightbox_theme, /* light_rounded / dark_rounded / light_square / dark_square / facebook */
			hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
			wmode: 'opaque', /* Set the flash wmode attribute */
			autoplay: true, /* Automatically start videos: True/False */
			modal: false, /* If set to true, only the close button will close the window */
			overlay_gallery: true, /* If set to true, a gallery will overlay the fullscreen image on mouse over */
			keyboard_shortcuts: true, /* Set to false if you open forms inside prettyPhoto */
			changepicturecallback: function(){}, /* Called everytime an item is shown/changed */
			callback: function(){} /* Called when prettyPhoto is closed */

		});
	});

}

function js_effects() {

	if (NOT_IE) {

		// NON-IE BROWSERS --[START]--

		// Enable JavaScript dependant UI

		$('#topbar .search').css('backgroundPosition', '300px 0').find('input[type=text]').css('opacity', 0);

		$('ul#portfolio-1col .entry button.next, ul#portfolio-1col .entry button.prev').css('visibility','visible');


		// Show search input if value present on load

		if ( theme_data.topbar_search_input.attr('value') != theme_data.topbar_search_value ) {

			theme_data.topbar_search.css('backgroundPosition', '0 0').find('input[type=submit]').css('opacity', theme_data[color_theme].topbar_submit_opacity);

			theme_data.topbar_search_input.css('opacity','1');

		}

		// Topbar Links hover

		$('#topbar ul.links li').hover(function() {

			$(this).stop().animate({
				opacity: topbar_icons_target_opacity
			}, 300);

		}, function() {

			$(this).stop().animate({
				opacity: topbar_icons_initial_opacity
			}, 300);

		});

		// Topbar Search hover

		$('#topbar form.search').hover(function() {

			theme_data.topbar_search_mouseover_callback(this);

		}, function() {

			var currentValue = $.trim(theme_data.topbar_search_input.attr('value'));

			if ( theme_data.topbar_input_focused == false ) {

				if ( currentValue != theme_data.topbar_search_value ) {
					return;
				}

				theme_data.topbar_timeoutID = setTimeout(theme_data.topbar_search_mouseout_callback, 1000, this);

			}

		});

		// NON-IE BROWSERS --[END]--

	}

	// Search input Focus & Blur events

	$('#topbar form.search input[type=text]').focus(function() {

		theme_data.topbar_input_focused = true;

		var val = $(this).attr('value');

		if ( val == theme_data.topbar_search_value ) {
			$(this).attr('value', '');
		}

	}).blur(function() {

		theme_data.topbar_input_focused = false;

		var val = $(this).attr('value');

		if ( val == '' ) {
			$(this).attr('value', theme_data.topbar_search_value);
		}

		theme_data.topbar_timeoutID = setTimeout(theme_data.topbar_search_mouseout_callback, 250, $(this).parents('.search') );

	});

}

function homepage_twitter_feed() {

	try {twitter_username} catch(e) {return;};

	if (enable_caching) {var cached = $.cookie('homepage-twitter-feed');}

	if (cached && enable_caching) {

		var tweet = $.base64Decode(cached);
		$('#homepage-twitter-feed').html(tweet);
		console_log('Homepage Twitter Feed: Using cached data');

	} else {

		$('#homepage-twitter-feed').tweets({
			tweets: 1,
			username: twitter_username,
			callback: function(tweets, target) {
				var tweet = tweets[0];
				target.html(tweet);
				if (enable_caching) {
					var expireDate = new Date();
					expireDate.setTime(expireDate.getTime() + (CACHE_TIMEOUT * 60 * 1000)); // expire in 5 minutes
					$.cookie('homepage-twitter-feed', $.base64Encode(tweet), {path: '/', expires: expireDate});
					console_log('Homepage Twitter Feed: Caching new data');
				}
			}
		});

	}

}

function init_flickr_widgets() {
		var c = 1;
		$(".der-flickr")
		.each(function() {$(this).attr("id","flickr-widget-" + c);c+=1;})
		.each(function() {
			var id = $(this).attr('id');
			if (enable_caching) {var cached = $.cookie(id)};
			if ( cached && enable_caching ) {
				var code = $.base64Decode(cached)
				console_log("Flickr Widget: Using cached data for " + id);
				eval(code);
			} else {
				var query = $(this).find('.query').attr('href');
				query = $.base64Encode(query);
				query = encodeURIComponent(query);
				var url = append_slash(template_directory) + "includes/ajax/flickr_ajax.php?id=" + id + "&q=" + query;
				$.ajax({
				  url: url,
				  cache: false,
				  success: function(code){

					try {eval(code);}
					catch(e) {console_log('Flickr Widget: Unable to retrieve data. Restart Apache or enable fopen() on the server.');return false;}

					if (enable_caching) {
						 var expireDate = new Date();
						 expireDate.setTime(expireDate.getTime() + (CACHE_TIMEOUT * 60 * 1000)); //
						 $.cookie(id, $.base64Encode(code), {path: "/", expires: expireDate});
						 console_log("Flickr Widget: Caching new data for " + id);
					}
				  }
				});
			}
		});

}

function form_enhancements() {

	$('#comments form.alt input:submit').click(function() {

		$(this).parents('form').find('input:text').each(function() {

			var val = $(this).val();

			var alt = $(this).data('title');

			if ( $.trim(val) == alt ) {$(this).val('');}

		});

	});

	alt_form_focus_callback = function() {

		var title = $(this).data('title');

		var val = $(this).val();

		if ( title == val ) {$(this).val('');}

	}

	alt_form_blur_callback = function() {

		var title = $(this).data('title');

		var val = $(this).val();

		if ( $.trim(val) == '' ) {$(this).val(title);}

	}


	$('form.alt:not(#contact-form) input:text').focus(alt_form_focus_callback).blur(alt_form_blur_callback).each(function() {

		$(this).data('title', $(this).attr('title')).removeAttr('title');

	});

}

})(jQuery);