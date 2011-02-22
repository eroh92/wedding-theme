<?php

// PROTECTION
if ( ! defined('ABSPATH') ) { die(''); }

global $_der, $post; /* @var $_der DerThemeOptions */

$description = der_postmeta('post_description');

$add_lightbox = der_postmeta('add_lightbox');

$class = ( $add_lightbox ) ? 'post page clearfix add-lightbox' : 'post page clearfix';

?>

<div id="page-header">
	<h1 class="page-title"><?php echo ( is_archive() OR is_search() ) ? $title : theme_long_title(); ?></h1>
	<?php if ($description): ?><span class="page-meta"><?php echo $description; ?></span><?php endif; ?>
</div><!-- page-header -->

<!-- + -->

<div id="wrapper" class="clearfix wrapper-full-width">
	<div id="content">

		<div <?php post_class( $class ); ?>>
<?php

		the_content();

		if ( $_der->equal('contact_page', $post->ID) ) { include(TEMPLATEPATH . '/extra/contact-form.php'); }

		edit_post_link('&rarr; Edit this Page');

?>
		</div><!-- .page -->

	</div><!-- content -->

	<span class="clear"></span>
</div><!-- wrapper -->

<!-- + -->

<?php get_footer(); ?>