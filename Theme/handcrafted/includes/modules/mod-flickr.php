<?php /* mod-flickr */

add_action( 'widgets_init', 'mod_flickr_widgets' );
add_action( 'der_script', 'mod_flickr_add_js');

function mod_flickr_widgets() {

	register_widget( 'Der_Flickr_Widget' );
	
}

function der_flickr_widget($instance) {

	if ( empty($instance['username'])  ) { return false; }

	echo
'	<div class="der-flickr clearfix">
		<a class="query" style="display:none" href="http://www.flickr.com/badge_code_v2.gne?count=' . $instance['count'] . '&amp;display=' . $instance['display'] . '&amp;size=s&amp;layout=x&amp;source=' . $instance['source'] . '&amp;' .$instance['source'] . '=' . $instance['username'] . '"></a>
		<br class="clear"/>
	</div>
';

}

class Der_Flickr_Widget extends WP_Widget {

	function Der_Flickr_Widget() {

		/* Widget settings. */
		$widget_ops = array( 'classname' => 'der_flickr_widget', 'description' => 'Super Awesome Flickr Gallery' );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'der-flickr-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'der-flickr-widget', '&bull; ' . der_theme_data('Name') .  ' Flickr Gallery', $widget_ops, $control_ops );
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

		der_flickr_widget($instance);

		/* Widget Code */

		/* After widget (defined by themes). */
		echo $after_widget;
	}


	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['username'] = strip_tags( $new_instance['username'] );
		$instance['display'] = strip_tags( $new_instance['display'] );
		$instance['count'] = strip_tags( $new_instance['count'] );
		$instance['source'] = strip_tags( $new_instance['source'] );
		$instance['profile_url'] = strip_tags( $new_instance['profile_url'] );

		return $instance;
	}


	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Flickr Gallery', 'count' => 6, 'profile_url' => '#flickr-profile' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width: 217px;"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>">Flickr ID: <small> &nbsp;<a target="_blank" href="http://idgettr.com/">What's my ID?</a></small></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" style="width: 217px;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'source' ); ?>">Get Photos from:</label><br/>
			<select class="widefat" id="<?php echo $this->get_field_id( 'source' ); ?>" name="<?php echo $this->get_field_name( 'source' ); ?>">
				<option value="user" <?php echo ( $instance['source'] == 'user') ? 'selected="selected"' : null; ?>>Flickr User</option>
				<option value="group" <?php echo ( $instance['source'] == 'group') ? 'selected="selected"' : null; ?>>Flickr Group</option>
				<option value="user_set" <?php echo ( $instance['source'] == 'user_set') ? 'selected="selected"' : null; ?>>Flickr Set</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>">Photos Display:</label><br/>
			<select class="widefat" id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>">
				<option value="latest" <?php echo ( $instance['display'] == 'latest') ? 'selected="selected"' : null; ?>>Latest</option>
				<option value="random" <?php echo ( $instance['display'] == 'random') ? 'selected="selected"' : null; ?>>Random</option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>">Number of Pictures:</label><br/>
			<select class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>">
<?php  foreach ( array(3,6,9,12,15,18,21,24,27,30) as $number ): ?>
				<option value="<?php echo $number; ?>" <?php echo ( $number == $instance['count'] ) ? 'selected="selected"': null; ?>><?php echo $number; ?></option>
<?php  endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'profile_url' ); ?>">Profile Url:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'profile_url' ); ?>" name="<?php echo $this->get_field_name( 'profile_url' ); ?>" value="<?php echo $instance['profile_url']; ?>" style="width: 217px;" />
		</p>

	<?php
	}
}

function mod_flickr_add_js() {

}

?>