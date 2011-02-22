
			<form class="alt search-form" action="<?php bloginfo('home'); ?>" method="get">
				<p>
					<input type="text" name="s" title="<?php _t('Search...'); ?>" value="<?php echo ( is_search() ) ? attribute_escape( get_search_query() ) : t('Search...'); ?>" />
					<input type="submit" value="<?php _t('Go'); ?>" />
				</p>
			</form>
