<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://codepaladins.com
 * @since      1.0.0
 *
 * @package    Recurly_Subscription
 * @subpackage Recurly_Subscription/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
<h1>Recurly Settings</h1>
<p id="rs_error"></p>
<p id="rs_msg"></p>
<form method="post" action="options.php" id="rs_form" name="rs_form">
		<?php
			settings_fields( 'rs_settings_group' );
			do_settings_sections( 'rs_settings_section' );// page slug of add_settings_section
			submit_button();
		?>
		</form>

</div>