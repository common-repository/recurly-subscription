<?php

/**
 *
 * @link              http://codepaladins.com
 * @since             1.0.0
 * @package           Recurly_Subscription
 *
 * @wordpress-plugin
 * Plugin Name:       Recurly Subscription
 * Plugin URI:        https://wordpress.org/plugins/recurly-subscription
 * Description:       The Recurly Subscription plugin allows you to accept one time subscription or recurring subscription payment from your WordPress site.
 * Version:           1.0.0
 * Author:            CodePaladins
 * Author URI:        http://codepaladins.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       recurly-subscription
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'RS_PLUGIN_FILE', __FILE__ );
define( 'RS_ABSPATH', dirname( __FILE__ ) . '/' );
define( 'RS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'RS_VERSION', '1.0.0' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-recurly-subscription-activator.php
 */
function activate_recurly_subscription() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-recurly-subscription-activator.php';
	Recurly_Subscription_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-recurly-subscription-deactivator.php
 */
function deactivate_recurly_subscription() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-recurly-subscription-deactivator.php';
	Recurly_Subscription_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_recurly_subscription' );
register_deactivation_hook( __FILE__, 'deactivate_recurly_subscription' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-recurly-subscription.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_recurly_subscription() {

	$plugin = new Recurly_Subscription();
	$plugin->run();

}
run_recurly_subscription();
