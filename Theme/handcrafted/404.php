<?php

// PROTECTION
if ( ! defined('ABSPATH') ) { die(''); }

get_header(); global $_der, $post; /* @var $_der DerThemeOptions */ ?>

<div id="page-header">
	<h1 class="page-title"><?php _t('Are you lost?') ?></h1>
	<span class="page-meta"><?php _t('Seems like you\'ve reached one of those HTTP/404 Error Pages...') ?></span>
</div><!-- page-header -->

<!-- + -->

<div id="wrapper" class="clearfix">
	<div id="content">
		<div <?php post_class( 'post page clearfix' ); ?>>

			<h5 style="margin-top: 20px;"><?php _t('HTTP/404 Error'); ?></h5>
			<p><?php _t('It seems the page you requested is not found. Try searching the site.') ?></p>

			<form class="search-form" action="<?php get_bloginfo('home'); ?>" method="get">
				<p>
					<input type="text" name="s" value="<?php echo ( is_search() ) ? attribute_escape( get_search_query() ) : t('Search...'); ?>" style="width: 250px;" />
					<input type="submit" value="Go" />
				</p>
			</form>

		</div><!-- .page -->
	</div><!-- content -->

	<div id="sidebar">
<?php

	der_sidebar('Global, Blog');

?>
	</div><!-- sidebar -->
	<span class="clear"></span>
</div><!-- wrapper -->

<!-- + -->

<?php get_footer(); ?>
