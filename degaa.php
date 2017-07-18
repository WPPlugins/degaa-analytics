<?php

	/*
	Plugin Name:  Degaa Analytics
	Plugin URI:   http://wordpress.org/extend/plugins/degaa/
	Version:      1.4
	Description:  This plugin adds support for Degaa Analytics tracking to WordPress installations.
	Author:       Degaa Analytics
	Author URI:   http://www.degaa.net/
	*/

	add_action('wp_footer',  'dg_footer');
	add_action('admin_menu', 'dg_admin_actions');

	if (get_option('track-admin'))
		add_action( 'admin_footer',	'dg_footer');

	function dg_footer() {
		$tracking_id = get_option("tracking-id", "invalid");

		if (dg_is_user_admin() &&
			get_option("ignore-administrator", true))
			return;

		if ($tracking_id != "invalid" &&
		    get_option("tracking-enabled", true))
		{
			?>
			
			<!-- DEGAA ANALYTICS TRACKING CODE - DO NOT CHANGE -->
			<script language="javascript" type="text/javascript">
			var daJsHost = (("https:" == document.location.protocol) ? "https://" : "http://");
			document.write(unescape("%3Cscript src='" + daJsHost + "live.degaa.net/da_v2' type='text/javascript'%3E%3C/script%3E"));
			</script> 
			<script language="javascript" type="text/javascript">
			try
			{
				var tracker = new DATracker();
				tracker.init("<?php echo $tracking_id; ?>");
				tracker.trackPageView();
			}
			catch (err) { }
			</script> 
			<!-- DEGAA ANALYTICS TRACKING CODE - DO NOT CHANGE -->

			<?php
		}
	}

	function dg_admin_actions() {
		add_options_page("Degaa Analytics", "Degaa Analytics", 1, "degaa", "dg_admin");
	}

	function dg_isChecked($field, $default)	{

		if(get_option($field, $default))
		{
			echo 'checked="checked"';
		}
	}

	function dg_is_user_admin() {
		$user = wp_get_current_user();

		// is this user an administrator
		if (is_user_logged_in() &&
		    $user->user_level == 10)
			 return true;

		return false;
	}

	function dg_get_visitor_name() {
			$user = wp_get_current_user();

			// make sure the user is logged in
			if (is_user_logged_in()) {
				 return $user->display_name;
			}

			return "";
	}

	function dg_admin() {
  		?>

  		<div class="wrap">
		<h2>Degaa Analytics</h2>

		<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>

		<table class="form-table">

		<tr valign="top">
			<th scope="row"></th>
			<td>
				<strong style='color:red'>
				This plugin requires an active account with Degaa Analytics. Create your site profile free at <a href='http://www.degaa.net/signup'>http://www.degaa.net/signup</a> to receive your
				tracking key and to access your online stats.
				</strong>
			</td>
		</tr>

		<tr valign="top">
		<th scope="row">Tracking Key</th>
		<td>
			<input type="text" style="width:350px; padding:5px; padding-top:8px; padding-bottom:8px; font-weight:bold;" name="tracking-id" id="tracking-id" value="<?php echo get_option('tracking-id'); ?>" />
			<br />
			<small>You can find the tracking key on your welcome email or by viewing the website settings on your <a target="_blank" href="http://www.degaa.net/my-sites">website list</a>.</small>
		</td>
		</tr>

		<tr valign="top">
			<th scope="row">Enable Tracking</th>
			<td>
				<input type="checkbox" name="tracking-enabled" id="tracking-enabled" value="true" <?php dg_isChecked('tracking-enabled', true) ?> />
				<label for="tracking-enabled"><?php _e("Enable Degaa Analytics Tracking for this site"); ?></label>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row">Ignore Administrator</th>
			<td>
				<input type="checkbox" name="ignore-administrator" id="ignore-administrator" value="true" <?php dg_isChecked('ignore-administrator', true) ?> />
				<label for="ignore-administrator"><?php _e("Ignore Visits from Administrators"); ?></label>
				<br />
				<small>Exclude all visits from users who are logged in as administrators</small>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row">Track Admin Pages</th>
			<td>
				<input type="checkbox" name="track-admin" id="track-admin" <?php dg_isChecked('track-admin', false) ?> />
				<label for="track-admin"><?php _e("Track Visits to Admin Pages"); ?></label>
				<br />
				<small>Include all visits to admin pages listed under /wp-admin/</small>
			</td>
		</tr>

		</table>

		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="tracking-id,tracking-enabled,ignore-administrator,track-admin" />

		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>

		</form>
		</div>

  		<?php
	}
?>