<?php

	// PROTECTION
	if ( ! defined('ABSPATH') ) { die(''); }

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _t('This post is password protected. Enter the password to view comments') ?>.</p>
	<?php
		return;
	}

if ( comments_open() ) : ?>

<h3 id="comments-heading"><?php comments_number( t('No Responses'), t('One Response'), '% ' . t('Responses') ); echo ' ' . t('to');  ?> &#8220;<?php the_title(); ?>&#8221;</h3>

	<div class="comments-navigation">
		<?php

		$previous_comments_link = get_previous_comments_link();

		$next_comments_link = get_next_comments_link();

		echo $previous_comments_link;

		if ($next_comments_link) { echo "<span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</span>"; }

		echo $next_comments_link;

		?>
		<div class="clear"></div>
	</div>

	<ol class="commentlist">
	<?php wp_list_comments('type=comment&avatar_size=48'); ?>
	</ol>

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/core/js/jquery.autoresize.min.js"></script>
<script type="text/javascript">

	(function($) {

		$(document).ready(function() {
			
			$('#comments form.alt textarea').autoResize({
				animate : function() { },
				onResize : function() { },
				animateCallback : function() { },
				animateDuration : 300,
				extraSpace : 20,
				minHeight: 150
			}).change();
			
		});

	})(jQuery);

</script>

<div id="respond">

<div class="cancel-comment-reply">
	<small><?php cancel_comment_reply_link(); ?></small>
</div>

<?php
	if ( get_option('comment_registration') && !is_user_logged_in() ) :
?>
<p><?php _t('You must be '); ?><a href="<?php echo wp_login_url( get_permalink() ); ?>"><?php _t('logged in'); ?></a> <?php _t('to post a comment'); ?>.</p>
<?php
	else :
?>

<form class="alt hc-form" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

	<h3><?php comment_form_title( t('Leave a Comment'), t('Leave a Reply') . ' %s' ); ?></h3>

<?php if ( is_user_logged_in() ) : ?>

	<p class="logged-in-as" style="margin-bottom: 5px;"><?php _t('Logged in as '); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _t('Log out of this account'); ?>"><?php _t('Log out'); ?> &raquo;</a></p>

<?php else : ?>

	<p>
		<input type="text" name="author" id="author" title="<?php _t('Name'); ?>" value="<?php _t('Name'); ?>" size="22" tabindex="1" aria-required='true' class="required name" />
		<label for="author"><?php _t('Required'); ?></label>
	</p>

	<p>
		<input type="text" name="email" id="email" title="<?php _t('Email'); ?>" value="<?php _t('Email'); ?>" size="22" tabindex="2" aria-required='true' class="required email" />
		<label for="email"><?php _t('Required, Will not be published'); ?></label>
	</p>

	<p>
		<input type="text" name="url" id="url" title="<?php _t('Website'); ?>" value="<?php _t('Website'); ?>" size="22" tabindex="3" class="url" />
	</p>

<?php endif; ?>

	<p>
		<textarea name="comment" id="comment" cols="58" rows="10" tabindex="4" class="required"></textarea>
	</p>

	<p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _t('Submit Comment'); ?>" /></p>

<?php

	comment_id_fields();

	do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>