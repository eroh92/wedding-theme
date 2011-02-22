(function($) {

	$(document).ready(function() {

		preload_images();

		init_prettyphoto();

		init_colorpicker();

		admin_init();

		click_events();

		init_tabs();

	});

	preload_images = function() {

		// Preload Images

		preload = new Image();images = new Array();

		images.push(template_directory + "/functions/admin/images/hover-tab-bg.png");
		images.push(template_directory + "/functions/admin/images/save-options-popup.png");

		var i = 0;for( i=0; i < images.length; i++ ) {preload.src = images[i];}

	}

	admin_init = function() {

		$('#der-admin-body #options-form h2.title').remove();

		$('#der-admin-body .root-section').removeClass('root-section');

		$('#der-admin-body #options-form .secondary').hide();

		$('#back-to-top').click(function(){
			var duration = 500;
			var easing = 'easeInOutQuad';
			if ( $.browser.msie ) {$('html').animate({scrollTop:0}, duration);return false;}
			if ( !$.browser.opera ) {$('html,body').animate({scrollTop:0}, {duration: duration, easing: easing});}
			else {$('html').animate({scrollTop:0}, {duration: duration,	easing: easing});}
			return false;
		});

		$('#der-admin-body .radio-option .left p, #der-post-metabox .radio-option p').click(function() {

			$(this).children('input:radio').select();

		});



	}

	init_prettyphoto = function() {

		// Automatically update the links's hyperlink reference from the text input

		$("a[rel^='lightbox']").click(function() {

			var href = $(this).parents('.option').find('input.mime-image').val();

			$(this).attr('href', href);

		});

		var lightbox = $("a[rel^='lightbox']");

		if ( lightbox.length ) {

			$("a[rel^='lightbox']").prettyPhoto({
				animationSpeed: 'normal', /* fast/slow/normal */
				opacity: 0, /* Value between 0 and 1 */
				showTitle: true, /* true/false */
				allowresize: true, /* true/false */
				default_width: 500,
				default_height: 344,
				counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
				theme: 'dark_rounded', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
				hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
				wmode: 'opaque', /* Set the flash wmode attribute */
				autoplay: true, /* Automatically start videos: True/False */
				modal: false, /* If set to true, only the close button will close the window */
				changepicturecallback: function(){}, /* Called everytime an item is shown/changed */
				callback: function(){} /* Called when prettyPhoto is closed */
			});

		}

	}

	init_tabs = function() {

		// Exit on non-admin pages

		if ( $('#der-admin-head ul.sections').length == 0 ) {return false;}

		// Switch tab based on cookie

		current_options_page = "" + document.location;

		current_options_page = current_options_page.split('page=')[1];

		$('#der-admin-head ul.sections li a').click(function() {

			// Activate menu item

			if ( $(this).hasClass('active') ) {

				return false;

			} else {

				var current_section = '#' + $(this).parents('.sections').find('.active').attr('rel');

				$(this).parents('.sections').find('.active').removeClass('active');

				$(this).addClass('active');
			}

			var section = '#' + $(this).attr('rel');

			$(current_section).hide();

			$(section).stop().css('opacity','0').show().animate({'opacity':'1'},500);

			// Remember current page with cookies

			$.cookie(current_options_page, $(this).attr('rel'), {path: '/', expires: 1} );

			return false;

		});

		// Load the current page, or the requested one

		var current_page = $.cookie(current_options_page);

		var requested = String(document.location).match(/#(.+)$/);

		if ( requested ) {

			requested = requested[1];

			$('#der-admin-head ul.sections li a[rel=' + requested + ']').click();

			$('html').scrollTop(0);

		} else if ( current_page ) {

			$('#der-admin-head ul.sections li a[rel=' + current_page + ']').click();

		}

	}

	init_colorpicker = function() {

		if ( $('.colorpicker-icon').length == 0 ) {return false;}

		$('#der-admin-body #options-form .option .colorpicker-icon').click(function() {

			colorpicker_context = $(this).attr('rel');

			colorpicker_context_input = $('#der-admin-body #options-form .option input[name=' + colorpicker_context + ']');

		});

		$('#der-admin-body #options-form .option .colorpicker-icon').ColorPicker({

			'onBeforeShow'	: function() {

				var current_color = colorpicker_context_input.val().replace('#','');

				if ( current_color ) {$(this).ColorPickerSetColor( current_color );}

			},

			'onSubmit'		: function(hsb, hex, rgb, el) {

				$('.colorpicker').hide();

				colorpicker_context_input.val('#' + hex.toUpperCase() );

			},

			'onHide'		: function() {

				//var hex = $('.colorpicker .colorpicker_hex input').val();

				//colorpicker_context_input.val('#' + hex.toUpperCase() );

			}

		});

	}

	click_events = function() {

		$('.option .new-page').click(function() {

			// Set options context if unset

			try {options_context;}
			catch(e) {options_context = null;}

			var page_title = prompt('Page Title:');

			if ( ! page_title ) {return false;}

			var object = this;
			var post_url = wp_home + '/wp-admin/admin-ajax.php';
			var optionID = $(this).parents('.option').find('select').attr('name');

			$.post(post_url, {
				action: 'der_ajax_upload',
				type: 'new_page',
				optionID: optionID,
				page_title: page_title,
				options_context: options_context
			}, function(page_id) {

				var select = $(object).parents('.option').find('.left select');

				select.find('option:selected').removeAttr('selected');

				select.append('<option selected="selected" value="' + page_id + '">' + page_title + '</option>' );

				$(object).hide();

			});

			return false;

		});

	}

	check_for_updates = function() { // Needs to be accessed outside of namespace

		var request_url = wp_home + '/wp-admin/admin-ajax.php';

		$.post(request_url, {
			action: "der_admin_actions",
			type: "check_for_updates"
		}, function(response) {
			// Store last version checked cookie
			$.cookie(cookie_prefix + 'last_version_checked', response, {path: '/', expires: 1});
		});
		return false;

	}

})(jQuery);