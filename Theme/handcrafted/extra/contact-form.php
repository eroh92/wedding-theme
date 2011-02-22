
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/core/js/jquery.validate.pack.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/core/js/jquery.autoresize.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/core/js/jquery.form.js"></script>
<script type="text/javascript">

	(function($) {

		$(document).ready(function() {

			// Auto Resize Textarea

			$('#contact-form textarea').autoResize({
				animate : function() { },
				onResize : function() { },
				animateCallback : function() { },
				animateDuration : 300,
				extraSpace : 20,
				minHeight: 150
			}).change();


			// Form Validation

			contact_form = $('#contact-form');

			contact_form.find('input:text').bind('focus', alt_form_focus_callback).bind('blur', alt_form_blur_callback);

			contact_form.find('input:text').each(function() { $(this).data('title', $(this).attr('title') ).removeAttr('title'); });

			contact_form.find('a.close').click(function() { $(this).parents('.success').fadeOut(500); return false; });

			contact_form.append('<input type="hidden" name="ajax" value="true" />');

			name_input = contact_form.find('input[name=name]');

			email_input = contact_form.find('input[name=email]');

			subject_input = contact_form.find('input[name=subject]');

			sent_message = false;

			sending = false;

			// [-- Validation Options --

			contact_form.validate({
				errorPlacement: function() {},
				highlight: function(element, errorClass) {
					$(element).addClass('invalid');
				},

				unhighlight: function(element, errorClass) {
					$(element).removeClass('invalid');
				},

				submitHandler: function(form) {

					if (sending) { return false; } // Prevent crazy clickers

					if ( sent_message ) { alert('<?php _t('Your message is on the way, Thanks!'); ?>'); return false; }

					var validated = true;

					if ( $.trim(name_input.val()) == name_input.data('title') ) { name_input.addClass('invalid'); validated = false; } else { name_input.removeClass('invalid'); }

					if ( $.trim(email_input.val()) == email_input.data('title') ) { email_input.addClass('invalid'); validated = false; } else { email_input.removeClass('invalid'); }

					if ( $.trim(subject_input.val()) == subject_input.data('title') ) { subject_input.addClass('invalid'); validated = false; } else { subject_input.removeClass('invalid'); }

					if (validated) {

						sending = true;

						$(form).ajaxSubmit({
							success: function(response) {
								sending = false;
								if (response == 'success') {
									sent_message = true;
									$('#contact-form .success').fadeIn(500);
								} else {
									$('#contact-form .success span').html('Unable to send the message due to a server error.');
									$('#contact-form .success').fadeIn(500);
								}

							}
						});

					}

					return false;

				}

			}); // -- validation-options --]

		});

	})(jQuery);

</script>

<form id="contact-form" class="alt" action="<?php bloginfo('template_directory'); ?>/includes/postdata/sendmsg.php" method="post">

	<p>
		<input type="text" name="name" id="name" class="name required" title="<?php _t('Name'); ?>" value="<?php _t('Name'); ?>" />

		<label for="name">required</label>
	</p>

	<p>
		<input type="text" name="email" id="email" class="email required" title="<?php _t('Email'); ?>" value="<?php _t('Email'); ?>" />
		<label for="email">required</label>
	</p>

	<p>

		<input type="text" name="subject" id="subject" class="required" title="<?php _t('Subject'); ?>" value="<?php _t('Subject'); ?>" />
		<label for="subject">required</label>
	</p>

	<label for="message"></label>
	<p>
		<textarea rows="10" cols="20" id="message" name="message" class="required"></textarea>
	</p>

	<div class="success"><span><?php _t('Your message has been sent!'); ?></span> <a href="#" class="close"><?php _t('Close'); ?></a></div>

	<p>
		<input type="submit" value="<?php _t('Send Message'); ?>" />
	</p>

</form><!-- contact-form -->
