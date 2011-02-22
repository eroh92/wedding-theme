<?php /* piecemaker */ global $_der;

	// Include top separation, a positive value that gets added to the margin top of piecemaker

	// include a bottom location for the shadow, in pixels

	$height = (int) $_der->getval('slider_height', MOD_PIECEMAKER);

	$height = ($height < 200) ? 200 : $height;

?>

</div><!-- .content-wrap -->

<script type="text/javascript" src="<?php bloginfo('template_directory') ?>/core/js/jquery.swfobject.min.js"></script>
<script type="text/javascript">
(function($) {

	$(document).ready(function() {

		jQuery('#piecemaker-container').css({backgroundPosition:'bottom center',height:'auto'}).flash({
			swf: template_directory + "/extra/piecemaker/piecemaker.swf",
			width: '100%',
			height: <?php echo $height + 150; ?>,
			wmode:"transparent",
			hasVersion:10,
			menu:false,
			AllowScriptAccess:'always',
			expressInstaller: template_directory + "/extra/piecemaker/expressInstall.swf",
			flashvars: {
				xmlSource: template_directory + "/extra/piecemaker-xml.php",
				cssSource: template_directory + "/extra/piecemaker/piecemakerCSS.css",
				imageSource: '<?php echo strstr(get_bloginfo('template_directory'), 'wp-content') . '/functions/scripts'; ?>'
			}
		});

	});

})(jQuery);
</script>

<div id="piecemaker-wrap" style="width: 100%; height: <?php echo $height + 150; ?>px;">
	<div id="piecemaker-container">
		<div class="require" style="top: <?php echo floor(($height+150)/2) ?>px"><?php _t('Please'); ?> <a href="http://www.adobe.com/products/flashplayer/"><?php _t('Install or Upgrade'); ?></a> <?php _t('Flash Player 10'); ?>.</div>
	</div><!-- piecemaker-wrap -->
</div>

<div class="content-wrap">
