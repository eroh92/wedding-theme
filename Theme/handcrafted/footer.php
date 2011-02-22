<?php

// PROTECTION
if ( ! defined('ABSPATH') ) { die(''); }

global $_der; wp_footer();

$simple_footer = $_der->checked('disable_footer');

?>
<span class="margin-clear"></span>

</div></div></div><!-- content-wrap-full | content-wrap-bg | content-wrap -->

<div id="footer-wrap">
	<span class="shadow"></span>
	<span class="margin-clear"></span>
	<ul id="footer" class="clearfix<?php echo ($simple_footer) ? ' simple-footer' : null; ?>">
<?php

	if ( ! $simple_footer ):

?>
		<li class="column column-1">
<?php

	der_sidebar('Footer Left');

?>
		</li><!-- .column-1 -->

		<li class="column column-2">
<?php

	der_sidebar('Footer Center');

?>
		</li><!-- .column-2 -->

		<li class="column column-3">
<?php

	der_sidebar('Footer Right');

?>
		</li><!-- .column-3 -->

		<li class="clear"></li>

<?php

	endif;

?>
		<li class="copyright"><?php $_der->val('copyright_info'); ?></li>

	</ul>
</div><!-- footer-wrap -->

<!-- analytics -->
<?php $_der->val('analytics_code'); ?>


</body>
</html>
