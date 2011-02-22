<?php

// PROTECTION
if ( ! defined('ABSPATH') ) { die(''); }

get_header(); global $_der, $post; /* @var $_der DerThemeOptions */

// Check for Full Width Layout

if ( der_postmeta('use_fullwidth') ) { include(TEMPLATEPATH . '/page_fullwidth.php'); exit(); }

$description = der_postmeta('post_description');

$add_lightbox = der_postmeta('add_lightbox');

$class = ( $add_lightbox ) ? 'post page clearfix add-lightbox' : 'post page clearfix';

$contact_page = $_der->equal('contact_page', $post->ID);

?>

<div id="page-header">
	<h1 class="page-title"><?php echo ( is_archive() OR is_search() ) ? $title : theme_long_title(); ?></h1>
	<?php if ($description): ?><span class="page-meta"><?php echo $description; ?></span><?php endif; ?>
</div><!-- page-header -->

<!-- + -->

<div id="wrapper" class="clearfix">
	<div id="content">
		<div <?php post_class( $class ); ?>>
<?php

		the_content();

		if ( $contact_page ) { include(TEMPLATEPATH . '/extra/contact-form.php'); }

		edit_post_link('&rarr; Edit this Page');

?>
		</div><!-- .page -->
	</div><!-- content -->

	<div id="sidebar">
<?php

	( $contact_page ) ? der_sidebar('Global, Contact Page') :  der_sidebar('Global, Pages');

?>
	</div><!-- sidebar -->
	<span class="clear"></span>
</div><!-- wrapper -->

<!-- + -->

<?php get_footer(); ?>
