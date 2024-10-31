<?php

/**
 * Fired during plugin activation
 *
 * @link       http://codepaladins.com
 * @since      1.0.0
 *
 * @package    Recurly_Subscription
 * @subpackage Recurly_Subscription/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Recurly_Subscription
 * @subpackage Recurly_Subscription/includes
 * @author     CodePaladins <support@codepaladins.com>
 */
class Recurly_Subscription_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
     add_role('recurly_user', __(
    'Recurly User'),
    array(
        'read'              => true, // Allows a user to read
        'create_posts'      => true, // Allows user to create new posts
        'edit_posts'        => true, // Allows user to edit their own posts
        'edit_others_posts' => false, // Allows user to edit others posts too
        'publish_posts'     => true, // Allows the user to publish posts
        'manage_categories' => true, // Allows user to manage post categories
        )
      );
	}

}
