<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://codepaladins.com
 * @since      1.0.0
 *
 * @package    Recurly_Subscription
 * @subpackage Recurly_Subscription/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Recurly_Subscription
 * @subpackage Recurly_Subscription/admin
 * @author     CodePaladins <support@codepaladins.com>
 */
class Recurly_Subscription_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        /* Add Actions */
		add_action('admin_menu', array($this, 'rs_admin_menu_items'));
        add_action( 'admin_init', array($this, 'rs_setting_options'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Recurly_Subscription_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Recurly_Subscription_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/recurly-subscription-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Recurly_Subscription_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Recurly_Subscription_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/recurly-subscription-admin.js', array( 'jquery' ), $this->version, false );

	}
    /* Admin Settings Init */
    public function rs_setting_options() {
        register_setting('rs_settings_group', 'rs_subdomain', array($this, 'rs_settings_sanitize'));
        register_setting('rs_settings_group', 'rs_pub_key', array($this, 'rs_settings_sanitize'));
        register_setting('rs_settings_group', 'rs_pri_key', array($this, 'rs_settings_sanitize'));
        register_setting('rs_settings_group', 'rs_subs_amt', array($this, 'rs_settings_sanitize'));
        register_setting('rs_settings_group', 'rs_currency', array($this, 'rs_settings_sanitize'));
        register_setting('rs_settings_group', 'rs_recurring', array($this, 'rs_settings_sanitize'));
        register_setting('rs_settings_group', 'rs_recurring_label', array($this, 'rs_settings_sanitize'));
        register_setting('rs_settings_group', 'rs_plan', array($this, 'rs_settings_sanitize'));
        /* Create settings section */
        add_settings_section(
                'rs_recurly_section', // Section ID
                __('Connection Settings', 'recurly_payment_gateway'),// Section title
                '', //array( $this, 'rs_recurly_connection_description' ),//Section callback function
                'rs_settings_section'                          // Settings page slug
        );
        /* Create settings field */
        add_settings_field(
                'rs_subdomain_field', // Field ID
                __('Recurly Subdomain', 'recurly_payment_gateway'), // Field title
                array($this, 'rs_subdomain_callback'), // Field callback function
                'rs_settings_section', // Settings page slug
                'rs_recurly_section'               // Section ID
        );
        add_settings_field(
                'rs_pub_key_field', // Field ID
                __('Public API Key', 'recurly_payment_gateway'),// Field title
                array($this, 'rs_public_callback'), // Field callback function
                'rs_settings_section', // Settings page slug
                'rs_recurly_section'               // Section ID
        );
        add_settings_field(
                'rs_pri_key_field', // Field ID
                __('Private API Key', 'recurly_payment_gateway'), // Field title
                array($this, 'rs_private_callback'), // Field callback function
                'rs_settings_section', // Settings page slug
                'rs_recurly_section'               // Section ID
        );
        add_settings_field(
                'rs_subs_amt_field', // Field ID
                __('Subscription Amount', 'recurly_payment_gateway'), // Field title
                array($this, 'rs_subs_callback'), // Field callback function
                'rs_settings_section', // Settings page slug
                'rs_recurly_section'               // Section ID
        );
        add_settings_field(
                'rs_currency_field', // Field ID
                __('Recurly Currency', 'recurly_payment_gateway'),// Field title
                array($this, 'rs_currency_callback'), // Field callback function
                'rs_settings_section', // Settings page slug
                'rs_recurly_section'               // Section ID
        );
        add_settings_section(
                'rs_recurring_section', // Section ID
                __('Recurring Settings', 'recurly_payment_gateway'),// Section title
                '', //array( $this, 'rs_recurly_connection_description' ),//Section callback function
                'rs_settings_section'                          // Settings page slug
        );
        add_settings_field(
                'rs_recurring_field', // Field ID
                __('Enable Recurring', 'recurly_payment_gateway'), // Field title
                array($this, 'rs_recurring_callback'), // Field callback function
                'rs_settings_section', // Settings page slug
                'rs_recurring_section'               // Section ID
        );
        add_settings_field(
                'rs_recurring_label_field', // Field ID
                __('Recurring Label', 'recurly_payment_gateway'), // Field title
                array($this, 'rs_recurring_label_callback'), // Field callback function
                'rs_settings_section', // Settings page slug
                'rs_recurring_section'               // Section ID
        );
        add_settings_field(
                'rs_plan_field', // Field ID
                __('Recurly Plan', 'recurly_payment_gateway'), // Field title
                array($this, 'rs_plan_callback'), // Field callback function
                'rs_settings_section', // Settings page slug
                'rs_recurring_section'               // Section ID
        );
    }

    /* Settings Field Callback */

    function rs_subdomain_callback() {
        echo '<input name="rs_subdomain" id="rs_subdomain" type="text" value="' . get_option('rs_subdomain') . '" />';
    }

    function rs_public_callback() {
        echo '<input name="rs_pub_key" id="rs_pub_key" type="text" value="' . get_option('rs_pub_key') . '" />';
    }

    function rs_private_callback() {
        echo '<input name="rs_pri_key" id="rs_pri_key" type="text" value="' . get_option('rs_pri_key') . '" />';
    }
    function rs_subs_callback() {
        echo '<input name="rs_subs_amt" id="rs_subs_amt" type="text" value="' . get_option('rs_subs_amt') . '" />';
    }
    function rs_currency_callback() {
        ;
        $html = '';
        $html.='<select id="rs_currency" name="rs_currency">
    <option value="">Select Currency</option>
    <option value="AUD" ' . (get_option("rs_currency") == 'AUD' ? ' selected="selected"' : '') . '>Australian Dollars</option>
    <option value="BRL" ' . (get_option("rs_currency") == 'BRL' ? ' selected="selected"' : '') . '>Brazilian Real</option>
    <option value="GBP" ' . (get_option("rs_currency") == 'GBP' ? ' selected="selected"' : '') . '>British Pounds</option>
    <option value="CAD" ' . (get_option("rs_currency") == 'CAD' ? ' selected="selected"' : '') . '>Canadian Dollars</option>
    <option value="CZK" ' . (get_option("rs_currency") == 'CZK' ? ' selected="selected"' : '') . '>Czech Korunas</option>
    <option value="DKK" ' . (get_option("rs_currency") == 'DKK' ? ' selected="selected"' : '') . '>Danish Kroner</option>
    <option value="EUR" ' . (get_option("rs_currency") == 'EUR' ? ' selected="selected"' : '') . '>Euros</option>
    <option value="HKD" ' . (get_option("rs_currency") == 'HKD' ? ' selected="selected"' : '') . '>Hong Kong Dollars</option>
    <option value="HUF" ' . (get_option("rs_currency") == 'HUF' ? ' selected="selected"' : '') . '>Hungarian Forints</option>
    <option value="INR" ' . (get_option("rs_currency") == 'INR' ? ' selected="selected"' : '') . '>Indian Rupee</option>
    <option value="ILS" ' . (get_option("rs_currency") == 'ILS' ? ' selected="selected"' : '') . '>Israeli New Sheqel</option>
    <option value="JPY" ' . (get_option("rs_currency") == 'JPY' ? ' selected="selected"' : '') . '>Japanese Yen</option>
    <option value="MXN" ' . (get_option("rs_currency") == 'MXN' ? ' selected="selected"' : '') . '>Mexican Peso</option>
    <option value="NOK" ' . (get_option("rs_currency") == 'NOK' ? ' selected="selected"' : '') . '>Norwegian Krones</option>
    <option value="NZD" ' . (get_option("rs_currency") == 'NZD' ? ' selected="selected"' : '') . '>New Zealand Dollars</option>
    <option value="PLN" ' . (get_option("rs_currency") == 'PLN' ? ' selected="selected"' : '') . '>Polish Złoty</option>
    <option value="SGD" ' . (get_option("rs_currency") == 'SGD' ? ' selected="selected"' : '') . '>Singapore Dollars</option>
    <option value="SEK" ' . (get_option("rs_currency") == 'SEK' ? ' selected="selected"' : '') . '>Swedish Kronas</option>
    <option value="CHF" ' . (get_option("rs_currency") == 'CHF' ? ' selected="selected"' : '') . '>Swiss Francs</option>
    <option value="ZAR" ' . (get_option("rs_currency") == 'ZAR' ? ' selected="selected"' : '') . '>South African Rand</option>
    <option value="USD" ' . (get_option("rs_currency") == 'USD' ? ' selected="selected"' : '') . '>United States Dollars</option>
    </select>';
        echo $html;
    }

    function rs_recurring_callback() {

        $options = get_option('rs_recurring');
        $checked = ($options == 'true' ? ' checked="checked"' : '');
        echo "<input id='rs_recurring' name='rs_recurring' type='checkbox' value='true' {$checked} />";
    }
    
    function rs_recurring_label_callback() {
        if (get_option('rs_recurring_label') == '') {
            $disabled = "disabled";
        } else {
            $disabled = "";
        }
        echo '<input name="rs_recurring_label" id="rs_recurring_label" type="text" value="' . get_option('rs_recurring_label') . '" ' . $disabled . '/>';
        echo '<p>User friendly name for Recurring label on frontend subscription form</p>';
    }
    
    function rs_plan_callback() {
        if (get_option('rs_plan') == '') {
            $disabled = "disabled";
        } else {
            $disabled = "";
        }
        echo '<input name="rs_plan" id="rs_plan" type="text" value="' . get_option('rs_plan') . '" ' . $disabled . '/>';
    }

    /* Sanitize Callback Function */

    function rs_settings_sanitize($input) {
        return isset($input) ? $input : false;
    }

    public function rs_admin_menu_items() {
        // Add the top-level admin menu
        $page_title = 'Recurly Subscription';
        $menu_title = 'Recurly Subscription';
        $capability = 'manage_options';
        $menu_slug = 'rs_settings';
        $function = 'rs_settings_page';
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, array($this, $function));
        
        $sub_menu_title = 'Settings';
        add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, array($this, $function));
    }

    public function rs_settings_page() {
        include_once( RS_ABSPATH . 'admin/partials/recurly-subscription-admin-display.php' );
    }
}
