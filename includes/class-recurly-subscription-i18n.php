<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://codepaladins.com
 * @since      1.0.0
 *
 * @package    Recurly_Subscription
 * @subpackage Recurly_Subscription/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Recurly_Subscription
 * @subpackage Recurly_Subscription/includes
 * @author     CodePaladins <support@codepaladins.com>
 */
class Recurly_Subscription_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'recurly-subscription',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
