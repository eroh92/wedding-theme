<?php

$GLOBALS['d'] = new DerOptions();

/* @var $_der DerThemeOptions */

class DerOptions {

	var $options = array();

	/* Constructor */

	function DerOptions() { }

	function set_arrays($name) {

		$names = csv2array($name);

		foreach ($names as $name) {

			$this->$name = array();

		}

	}

}

class DerThemeOptions {

	var $extra_options = array();

	var $extra_default_options = array();

	var $slot = null; // Options context

	var $previous_slot = null; // Previous Options Context

	var $last_query = null; // Last Queried Object

	var $overrides = array(); // Request Overrides

	/* Constructor */

	function DerThemeOptions() {

		$options = ( $GLOBALS['der_theme_options'] ) ? $GLOBALS['der_theme_options'] : get_option(DER_OPTIONS_DB_ENTRY); // Use options cache, if available

		$options = ($options) ? $options : array();

		$default_options = der_get_default_options();

		foreach ($options as $key => $val) {

			$this->$key = $val;

		}

		foreach ($default_options as $key => $val) {

			if ( ! isset ( $this->$key ) ) {

				$this->$key = $val;

			}
			
		}

		// Process overrides

		if ( defined('LIVE_PREVIEW_KEY') ) {

			$this->overrides = der_decrypt(LIVE_PREVIEW_KEY);

		}

	}

	function set_options_context($context) {

		$this->previous_slot = $this->slot;

		$this->slot = $context;

	}

	function reset_options_context() {

		$this->slot = null;

	}

	function restore_options_context() {

		$this->slot = $this->previous_slot;

	}

	function return_override($option, $slot) {

		$slot = ($slot) ? str_replace(DER_THEME_PREFIX . '_', '', $slot) : 'default';

		return $this->overrides[$slot][$option];

	}

	function val($option, $slot=null, $altval=null) {

		$slot = ( $slot == null ) ? $this->slot : $slot; // Set context, if $slot is not set

		echo $this->getval($option, $slot, $altval);

	}

	function getval($option , $slot=null, $altval=null) {

		$slot = ( $slot == null ) ? $this->slot : $slot; // Set context, if $slot is not set

		if ( empty($option) ) { return null; }

		$override = $this->return_override($option, $slot);

		if ($override) { return $override; }

		if ( $slot ) {

			$out = ($this->extra_options[$slot][$option]) ?
					
					$this->extra_options[$slot][$option] :

					$this->extra_default_options[$slot][$option];

			$out = ( $out == 'novalue' ) ? null : $out;

			return ($out == null ) ? $altval : $out;

		} else {

			$out = $this->$option;

			$out = ( $out == 'novalue' ) ? null : $out;

			return ($out == null ) ? $altval : $out;

		}

	}

	function get_taxonomies($option, $slot=null) {

		$slot = ( $slot == null ) ? $this->slot : $slot; // Set context, if $slot is not set

		if ($slot == null) { $slot = DER_OPTIONS_DB_ENTRY; }

		$options = get_option($slot);

		if ( empty($options) ) { $options = array(); }

		$values = array();

		foreach ($options as $key => $val ) {

			if ( preg_match("/^${option}_(\d+)$/", $key ) AND $val == 'on' ) {

				$values[] = (int) str_replace("${option}_", '', $key);

			}
		}

		return $values;

	}

	function checked($id, $value=null, $slot=null) {

		$slot = ( $slot == null ) ? $this->slot : $slot; // Set context, if $slot is not set

		$value = ( $value ) ? $value : 'on';

		return ( $this->getval($id, $slot) == $value ) ? ' checked="checked" ' : null;

	}

	function selected($id, $value, $slot=null) {

		$slot = ( $slot == null ) ? $this->slot : $slot; // Set context, if $slot is not set

		return ( $this->getval($id, $slot ) == $value) ? ' selected="selected" ' : null;

	}

	function meta_selected($id, $value) {

		$m = der_postmeta($id);

		return ( $m == $value ) ? ' selected="selected" ' : null;

	}

	function meta_checked($id, $value=null) {

		$value = ( $value ) ? $value : 'on';

		$m = der_postmeta($id);

		return  ( $m == $value ) ? ' checked="checked" ' : null;

	}

	function equal($id, $value, $slot=null) {

		$slot = ( $slot == null ) ? $this->slot : $slot; // Set context, if $slot is not set

		return ( $this->getval($id, $slot) == $value );

	}

	function add_extra_options($slot, $array, $defaults=null) {

		$slot = ( $slot == null ) ? $this->slot : $slot; // Set context, if $slot is not set

		$this->extra_options[$slot] = $array;

		if ($defaults) {

			$this->extra_default_options[$slot] = $defaults;

		}

	}

	function page($id, $slot=null) {

		$slot = ( $slot == null ) ? $this->slot : $slot; // Set context, if $slot is not set

		$val = $this->getval($id, $slot);

		return ( $val ) ? get_page($val) : null;

	}

}

class DerModule {

	var $module_id, $menu_title;

	var $db_entry_constant, $default_db_entry;

	var $der_init_cb, $module_css;

	var $default_options = array();

	var $skip_backup = false;

	var $interface_cb;

	var $admin_menu_destination;

	function DerModule($module_id, $menu_title) { global $_der; // __constructor

		list($this->module_id, $this->menu_title) = array($module_id, $menu_title);

		$this->db_entry_constant = 'DER_' . strtoupper($this->module_id) . '_DB_ENTRY';

		$this->default_db_entry = DER_THEME_PREFIX . '_' . $this->module_id;

		define('MOD_' . strtoupper($module_id), $this->default_db_entry); // MOD_{NAME}

		define('MOD_' . strtoupper($module_id) . '_ID', $module_id);

		$this->register_module();

	}

	function set_action_callback($action, $callback) {

		$action .= '_cb';

		$this->$action = $callback;

	}

	function set_css($code) {

		$this->module_css = $code;

	}

	function get_default_options() { global $d;

		if ( $this->default_options ) { return $this->default_options; }

		$module_id = $this->module_id;

		$options_array = ( $d->$module_id ) ? $d->$module_id : array();

		$defaults_array = array();

		foreach ($options_array as $section) {

			foreach ($section as $option) {

				if ($option['default'] != null) { $defaults_array[$option['id']] = $option['default']; continue; }

				if ($option['type'] == 'select') {

					$default = csv2array($option['fields']);

					$defaults_array[$option['id']] = $default[0]; continue;

				}

			}

		}

		$this->default_options = $defaults_array;

		return $defaults_array;

	}

	function set_default_options($options) {

		$this->default_options = $options;

	}

	function skip_backup() {

		$this->skip_backup = true;

	}

	function run_action($action, $data=null) {

		switch ($action) {

			case 'der_admin_menu':

				if ($this->admin_menu_cb) { $cb = $this->admin_menu_cb; $cb($this); }

				else { $this->admin_menu(); }

				break;

			case 'der_admin_styles':

				if ($this->admin_styles_cb) { $cb = $this->admin_styles_cb; $cb($this); }

				else { $this->admin_styles(); }

				break;

			case 'der_admin_scripts':

				if ($this->admin_scripts_cb) { $cb = $this->admin_scripts_cb; $cb($this); }

				else { $this->admin_scripts(); }

				break;

			case 'der_init':

				if ($this->init_cb) { $cb = $this->init_cb; $cb($this); }

				else { $this->init(); }

				break;

			case 'der_admin_init':

				if ($this->admin_init_cb) { $cb = $this->admin_init_cb; $cb($this); }

				else { $this->init(); }

				break;

			case 'der_theme_save':

				$this->save_settings($data);

				break;

			case 'der_theme_reset':

				$this->reset_settings();

				break;

		}

	}

	function register_module() { global $registered_modules;

		$registered_modules[] = $this;

	}

	function admin_menu() {

		$callback = ( $this->admin_menu_destination ) ? null : array($this, 'options_page');

		$destination = ( $this->admin_menu_destination ) ? $this->admin_menu_destination : $this->module_id;

		add_submenu_page('theme_options', $this->menu_title, $this->menu_title, 8, $destination, $callback );

	}

	function set_admin_menu_destination($destination) {

		$this->admin_menu_destination = $destination;

	}

	function options_page() { global $options_array_name, $options_context, $module_instance;

		$options_array_name = $this->module_id;

		$options_context = $this->default_db_entry;

		$module_instance = $this;

		include(TEMPLATEPATH . '/functions/admin/admin-interface.php');

	}

	function admin_styles() { global $module_opts;

		if ( $_GET['page'] == $this->module_id ) {

			der_admin_basic_css();

			if ($this->module_css) { echo '<style type="text/css">' . $this->module_css . "</style>\n"; }

		}

	}

	function admin_scripts() {

		if ( $_GET['page'] == $this->module_id ) { der_admin_basic_js(); }

	}

	function init() { global $_der, $d;

		if ( ! defined($this->db_entry_constant) ) {

			define($this->db_entry_constant, $this->default_db_entry);

		}

		$module_options = get_option($this->default_db_entry);

		$module_options = ($module_options == null) ? array() : $module_options;

		$default_options = $this->get_default_options();

		$_der->add_extra_options($this->default_db_entry, $module_options, $default_options );

	}

	function save_settings($DATA) {

		$page = preg_replace('/&.+$/', '', $_GET['page']);

		if ( $page == $this->module_id ) {

			$default_options = array(); // Set default options

			$updated_options = array();

			foreach ( $DATA as $key => $val ) {

				if ( $key == 'page' OR $key == 'action' OR $key == 'ajax' ) { continue; }

				if ( empty($DATA[$key]) ) {

					$updated_options[$key] = stripslashes( $default_options[$key] );

				} else {

					$updated_options[$key] = stripslashes( $DATA[$key] );

				}

			}

			update_option($this->default_db_entry, $updated_options);

			define('DER_THEME_SAVE_BREAK', true);

		}

	}

	function reset_settings() {

		if ( $_GET['page'] == $this->module_id ) {

			$updated_options = array();

			update_option($this->default_db_entry, $updated_options);

			define('DER_THEME_RESET_BREAK', true);

		}

	}

	function set_interface_callback($callback) { global $d;

		$this->interface_cb = $callback;

		$module_id = $this->module_id;

		$array = array($this->menu_title => array(array('type' => $module_id . '_module')));

		$d->$module_id = $array;

	}

	function render_interface() {

		if ($this->interface_cb) { $cb = $this->interface_cb; $cb($this); }

		else { return; }

	}

}

class DerCustomQuery {

	/* WP_Query Compatible object */

	var $query_vars;

	var $request;

	var $found_posts;

	var $max_num_pages;

	var $posts;

	function DerCustomQuery($args) { global $wpdb, $paged;

		$defaults = array(
			'post_type' => null,
			'taxonomy' => null,
			'terms' => array(),
			'showposts' => '-1'
		);

		map_defaults($args, $defaults);

		$this->query_vars = $args;

		extract($args);

		$terms_in = array();

		foreach ($terms as $term) { $terms_in[] = "'$term'"; }

		$terms_in = implode(', ', $terms_in);

		// LIMIT {$showposts * ($paged-1), $showposts}

		$limits = absint($showposts) * ( $paged - 1 ) . ', ' . $showposts;

		$this->request = "
			SELECT SQL_CALC_FOUND_ROWS  $wpdb->posts.* FROM $wpdb->posts
			INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
			INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
			WHERE 1=1
			AND $wpdb->term_taxonomy.taxonomy = '$taxonomy'
			AND $wpdb->term_taxonomy.term_id IN ($terms_in)
			AND $wpdb->posts.post_type = '$post_type'
			AND ($wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'private')
			GROUP BY $wpdb->posts.ID
			ORDER BY $wpdb->posts.post_date DESC
			LIMIT $limits
			";

		$this->posts = $wpdb->get_results($this->request, OBJECT);

		$found_posts_query = apply_filters_ref_array( 'found_posts_query', array( 'SELECT FOUND_ROWS()', &$this ) );
		$this->found_posts = $wpdb->get_var( $found_posts_query );
		$this->found_posts = apply_filters_ref_array( 'found_posts', array( $this->found_posts, &$this ) );
		$this->max_num_pages = ceil($this->found_posts / $showposts);

	}

}

?>