<?php /* mod-settings */ global $d;

// Import/Export Settings Module
// =============================

// Create Module Instance
$settings_module = new DerModule('settings', 'Import/Export');

$settings_module->set_interface_callback('mod_settings_interface');

$settings_module->skip_backup();

add_action('admin_head', 'mod_settings_styles');
add_action('wp_ajax_mod_settings', 'mod_settings_ajax');

// Module Functions

function mod_settings_styles() {

	if ($_GET['page'] == MOD_SETTINGS_ID) {

		echo '<style type="text/css">
#der-admin-body .option .pre { border: solid 1px #ccc; padding: 10px; margin: 0px 0 0 0; background: #f6f6f6; font-size: 12px; width: 720px; font-family: Arial,sans-serif;
 font-family: Arial,verdana,sans-serif; color: #666; font-style: italic; top: 0; display: none; }
#der-admin-body .settings-option div.description { margin: 0; }
#der-admin-body .settings-option .left { width: 100%; }
#der-admin-body .settings-option div.description p { font-size: 11px; color: #8B8B8B; }
.form-buttons, .form-reset, .form-bottom, #reset-options-form, #back-to-top { display: none !important; }
#der-admin-body { padding-bottom: 0 !important; }
</style>

<script type="text/javascript">
(function($) {
$(document).ready(function() {
	$("#import-settings-button").click(function() {
		var textarea = $("textarea#import-code");
		var backup_code = $.trim(textarea.val());
		if ( backup_code.length == 0 ) { return false; } // Exit if no backup code available
		var post_url = wp_home + "/wp-admin/admin-ajax.php";
		$.post(post_url, {
			action: "mod_settings",
			type: "restore_settings",
			backup_code: backup_code
		}, function(response) {
			if ( response == "error" ) {
				alert("There was an error importing your Data.");
			} else {
				show_ajax_save_notice();
				setTimeout(function() { textarea.val("") },1600);
			}
		});
		return false;
	});

	$("#backup-code-button").click(function() {
		var textarea = $("textarea#backup-code");
		var backup_code = $.trim(textarea.val());
		var post_url = wp_home + "/wp-admin/admin-ajax.php";
		var object = this;
		$.post(post_url, {
			action: "mod_settings",
			type: "backup_settings"
		}, function(response) {
			textarea.val(response).show();
			$(object).hide();
		});
		return false;
	});

	$("#reset-settings-button").click(function() {
		var post_url = wp_home + "/wp-admin/admin-ajax.php";
		var button = $(this);
		if ( confirm("Reset ALL settings to their defaults?") ) {
			$.post(post_url, {
				action: "mod_settings",
				type: "reset_settings"
			}, function(response) {
				$("#settings-reset-notice").fadeIn(300);
				button.remove();
			});
		}
		return false;
	});

	$("#clear-import-code").click(function() {
		var textarea = $("textarea#import-code");
		textarea.val("");
		return false;
	});

});
})(jQuery)
</script>

';

	}

}

function mod_settings_interface() {

	echo '
		<div class="option settings-option clearfix">

			<h3 class="reset">Backup Code</h3>

			<div class="left">
				<div class="description">
					<p>The following code will allow you to Restore your settings on a later stage.</p>
				</div>

				<textarea rows="8" id="backup-code" class="pre" onclick="this.select();"></textarea>

				<a class="button" id="backup-code-button" href="#" style="display: inline-block; margin: 3px 0 0 0; padding-left: 30px; padding-right: 30px; text-align: center;">Get Backup Code</a>

			</div><!-- left -->

		</div><!-- option -->


		<div class="option settings-option clearfix">

			<h3 class="reset">Reset All Options</h3>

			<div class="left">
				<div class="description">
					<p>Reset all the option pages to their defaults.</p>
				</div>

				<a class="button" id="reset-settings-button" href="#" style="display: inline-block; margin: 3px 0 0 0; padding-left: 30px; padding-right: 30px; text-align: center;">Global Reset</a>

				<p class="updated" id="settings-reset-notice" style="padding: 10px; font-size: 11px; margin: 15px 0 0 0; display: none;">All settings have been reset to their defaults.</p>

			</div><!-- left -->

		</div><!-- option -->

		<div class="option settings-option clearfix" style="border-bottom: none;">

			<h3 class="reset">Import Settings</h3>

			<div class="left">
				<div class="description">
					<p>Paste the <u>Backup Code</u> on the field below. <br/>
					To Restore your settings, click on the "<u>Import Settings</u>" button.</p>
				</div>

				<textarea rows="8" style="top: 0; width: 720px; margin-bottom: 12px;" id="import-code"></textarea>

				<a class="button" id="import-settings-button" href="#" style="float: right; padding-left: 30px; padding-right: 30px; ">Import Settings</a>

				<a class="button" id="clear-import-code" style="float: right; padding-left: 30px; padding-right: 30px; margin-right: 10px;">Clear</a>

			</div><!-- left -->

		</div><!-- option -->

';

}

function mod_settings_backup() { global $registered_modules;

	$options = array();

	$options[DER_OPTIONS_DB_ENTRY] = serialize( get_option(DER_OPTIONS_DB_ENTRY) );

	foreach ($registered_modules as $module) {

		if ($module->skip_backup) { continue; }

		$options[$module->default_db_entry] = serialize( get_option($module->default_db_entry) );

	}

	$serialized = serialize($options);

	echo base64_encode($serialized);
	
}

function mod_settings_reset() { global $registered_modules;

	update_option( DER_OPTIONS_DB_ENTRY, array() );

	foreach ($registered_modules as $module) {

		if ($module->skip_backup) { continue; }

		update_option($module->default_db_entry, array() );

	}

}

function mod_settings_ajax() {

	switch ($_POST['type']) {

		case 'restore_settings':

			$backup_code = $_POST['backup_code'];

			$serialized = base64_decode($backup_code);

			$options = unserialize($serialized);

			if ( ! $options ) { echo 'error'; break; }

			foreach ($options as $key => $val) {

				$val = unserialize($val);

				update_option($key, $val);

			}

			echo 'success';

			break;


		case 'backup_settings':

			mod_settings_backup();

			break;

		case 'reset_settings':

			mod_settings_reset();

			echo 'success';

			break;

	}

	exit();
}

?>
