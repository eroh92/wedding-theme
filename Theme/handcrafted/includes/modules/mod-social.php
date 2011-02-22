<?php /* mod-social */ global $d;

/* @var $_der DerThemeOptions */

// Social Networks Module
// ======================

// Module Defines
define('MOD_SOCIAL_ICONS_DIR', '/core/images/social');
define('MOD_SOCIAL_ICONS_WIDTH', 32);
define('MOD_SOCIAL_ICONS_HEIGHT', 32);
define('MOD_SOCIAL_ENABLE_WIDGET', false);
define('MOD_SOCIAL_ID', 'social');


// Create Module Instance
$social_module = new DerModule('social', 'Social Networks');

$social_module->set_interface_callback('mod_social_interface');


// Module Actions
add_action('admin_head', 'mod_social_styles');
if (MOD_SOCIAL_ENABLE_WIDGET) { add_action( 'widgets_init', 'mod_social_widget' ); }


/* Module Options */

$d->social['Configuration'] = array();

$d->social['Configuration'][] = array(
	'name'			=>	'Number of Social Links to Display',
	'id'			=>	'social_links_count',
	'type'			=>	'text',
	'default'		=>	'6',
	'description'	=>	'The amount of social links to display on the Social Networks Widget.<br/><br/>
<a class="button" href="">Refresh to Update</a>
'
);

$d->social['Configuration'][] = array(
	'name'			=>	'Configuration Options',
	'type'			=>	'checkbox',
	'fields'		=>	array(
						'open_in_same_window'		=>	'Open Links in the same browser window.',
						'hide_link_tooltips'		=>	'Hide Link Tooltips'
	)
);


/* Module Functions */

function mod_social_interface($instance) { global $_der;

	$_der->set_options_context($instance->default_db_entry);

	$social_links_count = $_der->getval('social_links_count');

	$social_links_count = ( $social_links_count > 100 ) ? 100 : $social_links_count; // Prevent massive memory consumption

	echo '
<div class="custom">
	<h3 class="reset">Manage Social Networks</h3>

	<p>
		To select the amount of Social Networks to be displayed, switch to the <u>Configuration</u> Tab, and set the <u>Number of Social Links to Display</u>.<br/>

		<!-- All the fields must be specified, in order for an item to appear in the <a href="' . admin_url('widgets.php') . '">Social Networks Widget</a>.<br/> --->

		To Add new Social Icons (' . MOD_SOCIAL_ICONS_WIDTH . ' x ' . MOD_SOCIAL_ICONS_HEIGHT . ' px), upload them via FTP to <u>' . MOD_SOCIAL_ICONS_DIR . '</u>. (Relative to the theme\'s directory).

	</p>

</div>
';


	for ($i=0; $i < $social_links_count; $i++) {

		$name = $_der->getval("social_item_${i}_name");

		$icon = $_der->getval("social_item_${i}_icon");

		$url = $_der->getval("social_item_${i}_url");

		echo '
	<div class="option social-option clearfix">

		<h3 class="reset">Social Item #' . ($i + 1) . '</h3>

		<p class="clearfix">
			<label for="social_item_' . $i . '_name">Name</label>
			<input tabindex="2" type="text" autocomplete="off" name="social_item_' . $i . '_name" id="social_item_' . $i . '_name" value="' . $name . '" />
		</p>

		<p class="clearfix">
			<label for="social_item_' . $i . '_icon">Icon</label>
			<select tabindex="2" name="social_item_' . $i . '_icon" id="social_item_' . $i . '_icon">' . mod_social_icons_select_html($icon) . '</select>
		</p>
		
		<p class="clearfix">
			<label for="social_item_' . $i . '_url">URL</label>
			<input tabindex="2" type="text" autocomplete="off" name="social_item_' . $i . '_url" id="social_item_' . $i . '_url" value="' . $url . '" />
		</p>

	</div><!-- option -->
';
		
	}

}

function mod_social_styles() {

	if ($_GET['page'] == MOD_SOCIAL_ID) {

		echo '<style type="text/css">
#der-admin-body .social-option { padding: 10px 0 30px; }
#der-admin-body .social-option label { float: left; top: 0; left: 0; height: 30px; line-height: 30px; padding: 0; width: 60px; text-align: right; color: #aaa; }
#der-admin-body .social-option input[type="text"], #der-admin-body .social-option select { float: left; top: 0; left: 7px; width: 655px; }
#der-admin-body .social-option p { margin-bottom: 1px; }
#der-admin-body .social-option p:last-child { margin-bottom: 0; }
</style>
';

	}

}

function mod_social_get_icons() {

	if ( $GLOBALS['mod_social_icons_cache'] ) { return $GLOBALS['mod_social_icons_cache']; }

	$path = TEMPLATEPATH . MOD_SOCIAL_ICONS_DIR;

	$files = der_compat_scandir($path);

	$files = array_slice($files, 2);

	array_unshift($files, '');

	sort($files);

	$GLOBALS['mod_social_icons_cache'] = $files; // Enable caching

	return $files;

}

function mod_social_icons_select_html($current=null) {

	$icons = mod_social_get_icons();

	$options = '';

	$first = true;

	foreach ($icons as $icon) {

		if ( $current ) { $selected = ( $icon == $current ) ? ' selected="selected"' : null; }

		else { $selected = ( $first ) ? ' selected="selected"' : null; }

		if ($first) { $first = false; }

		$name = ucwords(preg_replace('/.(jpg|png)$/i', '', $icon));

		$options .= "\n\t" . '<option value="' . $icon . '"' . $selected . '>' . $name . '</option>';

	}

	return $options;

}

function mod_social_get_data() { global $_der;

	$_der->set_options_context(DER_SOCIAL_DB_ENTRY);

	$social_links_count = $_der->getval('social_links_count');

	$prefix = 'social_item_';

	$data = array();

	for ($i=0; $i < $social_links_count; $i++) {

		$current = array(
			'name' => $_der->getval($prefix . $i . '_name'),
			'icon' => get_bloginfo('template_directory') . MOD_SOCIAL_ICONS_DIR . '/' . $_der->getval($prefix . $i . '_icon'),
			'url' => $_der->getval($prefix . $i . '_url'),
		);

		if ( $current['name'] AND $current['icon'] AND $current['url'] ) { $data[] = $current; }

	}

	$_der->restore_options_context();

	return $data;

}

function mod_social_widget() {

	register_widget( 'Der_Social_Widget' );

}

function Der_Social_Widget($instance) { global $_der;

	$_der->set_options_context(MOD_SOCIAL_ID);

	$module_data = mod_social_get_data();

	echo '<ul class="social layout-list clearfix">';

	foreach ($module_data as $data) {

		$target = ( ! $_der->checked('open_in_same_window') ) ? 'target="_blank" ' : null;

		$title = ( ! $_der->checked('hide_link_tooltips') ) ? 'title="' . $data['name'] . '" ' : null;

		echo "\n<li><a " . $target . $title. 'href="'. $data['url'] . '"><img width="' . MOD_SOCIAL_ICONS_WIDTH . '" height="' . MOD_SOCIAL_ICONS_HEIGHT . '" src="' . $data['icon'] . '" /></a></li>';

	}

	echo "\n</ul><!-- .social -->";

	$_der->reset_options_context();

}

class Der_Social_Widget extends WP_Widget {

	function Der_Social_Widget() {

		/* Widget settings. */
		$widget_ops = array( 'classname' => 'breathe_social_widget', 'description' => der_theme_data('Name') . ' Social Networks Widget' );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'der-social-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'der-social-widget', '&bull; Social Networks', $widget_ops, $control_ops );
	}


	function widget( $args, $instance ) {
		global $_der;
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		/* Widget Code */

		Der_Social_Widget($instance);

		/* Widget Code */

		/* After widget (defined by themes). */
		echo $after_widget;
	}


	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}


	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Social Networks', 'count' => 6 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width: 217px;"/>
		</p>

		<p>
			<a class="button" href="<?php echo admin_url('admin.php?page=' . MOD_SOCIAL_ID); ?>">Manage Social Networks</a>
		</p>

	<?php

	}

}

?>
