<?php

// PROTECTION
if ( ! defined('ABSPATH') ) { die(''); }

get_header(); global $_der, $post; /* @var $_der DerThemeOptions */

// Slider

switch( $_der->getval('slider_manager') ) {

	case 'Nivo Slider':
		$slideshow_manager = 'nivo';
		break;

	case 'Piecemaker':
		$slideshow_manager = 'piecemaker';
		break;

}

include(TEMPLATEPATH . "/extra/$slideshow_manager.php");


// Homepage Teaser

$homepage_teaser = $_der->getval('homepage_teaser');

$homepage_icon = $_der->getval('homepage_icon_destination');

$twitter_username = $_der->getval('twitter_username');

switch ($homepage_teaser) {

	case 'Quote':

		$quote = $_der->getval('homepage_quote');

		$first_letter = '<span class="dropcap">' . strtoupper($quote[0]) . '</span>';

		$quote = $first_letter . substr($quote, 1);

		$quote = wpautop($quote);

		$paragraph = $quote;

		break;

	case 'Twitter Feed':

		if ($twitter_username ) {

			$paragraph = '
<script type="text/javascript">twitter_username = "' . $twitter_username . '";</script>
<p id="homepage-twitter-feed"></p>
';

		} else { $paragraph = null; }

		break;

}

switch ($homepage_icon) {
	
	case 'Contact Page':

		$contact_page = $_der->getval('contact_page');

		$permalink = ($contact_page) ? get_permalink($contact_page) : '#contact-page';

		$link = '<a class="round-button contact" title="' . t('Contact Us') . '" href="' . $permalink . '">' . t('Contact') . "</a>\n";

		break;

	case 'Twitter Profile':

		$link = '<a class="round-button twitter" title="' . t('Follow Us @' . $twitter_username) . '" href="http://twitter.com/' . $twitter_username . '">' . t('Twitter Profile') . "</a>\n";

		break;
	
}

if ($homepage_teaser != 'Don\'t Show'):

?>

<!-- + -->

<div id="homepage-center" class="clearfix">
<?php

	echo $paragraph;

	echo $link;

?>
<span class="clear"></span>
</div><!-- homepage-center -->
<?php

	endif;




	// Homepage Posts

	if ( ! $_der->getval('disable_homepage_posts') ):

?>

<!-- + -->

<div class="big-separator" style="margin-top: 20px;"></div>

<ul id="homepage-posts" class="posts clearfix">

<?php

	$homepage_query = der_homepage_query();

	$consider_as_new = $_der->getval('homepage_consider_as_new');

	$image_height = $_der->getval('homepage_posts_height', MOD_LAYOUT);

	$image_height = ($image_height == '-1') ? 0 : $image_height; // Auto image height

	$i = 0;

	foreach ($homepage_query->posts as $post):

		$new = ( $i < $consider_as_new ) ? t('NEW') :  sprintf("%02d", $i-($consider_as_new-1) );

		setup_postdata($post);

		$post_image = post_thumb('post_image', 290, $image_height);

		$post->content_width = 290;

?>
	<li <?php post_class('post clearfix'); ?>>
		<div class="post-meta">
			<a class="meta"><?php echo $new; ?>.</a><br/>
			<a class="title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			<span class="categories"><?php the_category(', '); ?></span>
			<?php if ($post_image): ?><a class="post-image" href="<?php the_permalink(); ?>"><img alt="" width="290" <?php if ($image_height): ?>height="<?php echo $image_height; ?>"<?php endif; ?> src="<?php echo $post_image; ?>" /></a><?php endif; ?>
		</div>
		<div class="excerpt"><?php the_excerpt(); ?></div>
		<a class="read-more" href="<?php the_permalink(); ?>"><?php _t('Continue Reading'); ?></a>
	</li><!-- .post -->

<?php if ( ($i+1) % 3 == 0 ): ?>
	<li class="clear"></li>

<?php endif;  $i++; 

	endforeach;

?>
</ul><!-- homepage-posts -->

<?php

	else:

?>
	<div style="height: 30px;"></div>

<?php

	endif;

?>

<!-- + -->

<?php get_footer(); ?>