(function($) {
	
	$(document).ready(function() {

		// Options Panel Initialization

		der_options_panel();

		// Ajax Upload Configuration

		der_ajax_upload();

	});

	/* Options Panel Initialization */

	der_options_panel = function() {

		// Add form action

		var form = $('#der-admin #options-form').get(0);

		if ( form ) {

			// There's already an element with name=action, which creates a conflict on IE (all versions)
			// Using setAttribute avoids this problem.

			form.setAttribute('action', template_directory + '/functions/admin/admin-ajax.php');
			
			// Add AJAX input to form

			$(form).append('<input type="hidden" type="text" name="ajax" value="1" />');

			// Theme Save AJAX

			der_theme_save_ajax();

			// Reset warning

			$('input.reset-button').click(function() {

				$(this).blur();

				return confirm('This will reset All the options from All Tabs to their defaults. Are you sure ?');

			});

		}

	}


	/* Theme Save Ajax */

	der_theme_save_ajax = function() {

		// Theme Save AJAX

		busy = false;

		$('#der-admin #options-form').bind('submit', function() {

			if ( busy ) {return false;}

			busy = true;

			$('#ajax-loader').show();

			$(this).ajaxSubmit({

				success: show_ajax_save_notice

			});

			return false;

		});

	}

	show_ajax_save_notice = function() {

		var popup_show = 600;

		$('#ajax-loader').hide();

		$('body').append('<div id="popup"></div>');

		var topCoord = Math.floor( (window.innerHeight - 120.0)/2.0 ) + 'px';

		$('#popup').css('top', topCoord).animate({'opacity': '1'},popup_show);

		setTimeout(function() {

			$('#popup').animate({'opacity': '0'},400);

			setTimeout(function() {

				$('#popup').remove();

				$('input[type=submit]').blur();

				admin_post_ajax();

				busy = false;

			}, 400);

		}, popup_show + 500 );

	}

	/* Admin Post Ajax instructions */

	admin_post_ajax = function() {

		/* Remove the image preview button, if the text entry is empty */

		$('#options-form .option').each(function() {

			var mime_image_input = $(this).find('.mime-image');

			if ( mime_image_input.length ) {

				if ( mime_image_input.val() == '' ) {

					mime_image_input.removeClass('mime-image');

					$(this).find('.image-preview').hide();

				}

			}

		});

		/* Add the image preview button, if text entry got new contents */

		$('#options-form .mime-image-container').each(function() {

			var input = $(this).find('input[type=text]');

			var href = input.val();

			if ( ! $(this).find('.mime-image').length && href != '' ) {

				input.addClass('mime-image');

				$(this).find('.image-preview').show();

			}

		});

		/* Add 'Create New Page' Button when resetting page option */

		$('#options-form .page-category-option').each(function() {

			var selected = $(this).find('select option:selected').attr('value');

			if ( selected == 'novalue' ) {
				// Show 'Create Page' Button
				$(this).find('.right .new-page').show();
			} else {
				// Hide 'Create Page' Button
				$(this).find('.right .new-page').hide();
			}

		});

	}


	/* AJAX Upload */

	der_ajax_upload = function() {

		// Set options context if unset

		try {options_context;}
		catch(e) {options_context = null;}

		// Image Upload

		$('.buttons-container button.image_upload').each(function(i) {

			var button = $(this);
			var optionID = button.attr('name');
			var input = $('input[name=' + optionID + ']');
			var post_ID = ( der_upload_context == 'mod-postmeta' ) ? button.attr('id').replace('post_','') : '-1';
			var action_url = wp_home + '/wp-admin/admin-ajax.php';

			new AjaxUpload(button, {
				action: action_url,
				name: optionID,
				data: { // Additional data to send
					action: 'der_ajax_upload',
					type: 'upload',
					data: optionID,
					context: der_upload_context,
					post_ID: post_ID,
					options_context: options_context
				},
				responseType: false,
				onSubmit : function(file, ext){
					// change button text, when user selects file
					button.text('Uploading');

					// If you want to allow uploading only 1 file at time,
					// you can disable upload button
					this.disable();

					// Uploding -> Uploading. -> Uploading...
					interval = window.setInterval(function(){
						var text = button.text();
						if (text.length < 13){
							button.text(text + '.');
						} else {
							button.text('Uploading');
						}
					}, 200);
				},
				autoSubmit: true,
				onComplete: function(file, response){
					button.text('Upload Image');

					window.clearInterval(interval);

					// enable upload button
					this.enable();

					// add file to the input
					if ( response == '-1') {
						input.val("Permission Denied. Maybe you're not logged in?");
					} else {
						input.val(response);
					}

					// Show Preview Button
					input.addClass('mime-image');
					input.parents('.option').find('.image-preview').show();
					button.siblings('.image_remove').show();

				}
			});

		});

		// Image Remove

		$('button.image_remove').click(function() {

			var button = $(this);
			var optionID = button.attr('name');
			var input = $('input[name=' + optionID + ']');
			var post_ID = ( der_upload_context == 'mod-postmeta' ) ? button.attr('id').replace('post_','') : '-1';
			var post_url = wp_home + '/wp-admin/admin-ajax.php';

			$.post(post_url, {
				action: 'der_ajax_upload',
				type: 'image_remove',
				data: optionID,
				context: der_upload_context,
				post_ID: post_ID,
				options_context: options_context
			}, function(response) {
				button.siblings('input').val('');
				input.val('');
				input.removeClass('mime-image');
				input.parents('.option').find('.image-preview').hide();
				button.hide();
			});

			return false;

		});

	}
	
})(jQuery);

