<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    reliable_repair_device
 * @subpackage reliable_repair_device/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    reliable_repair_device
 * @subpackage reliable_repair_device/admin
 * @author     Your Name <email@example.com>
 */
class Reliable_Repair_Device_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $reliable_repair_device    The ID of this plugin.
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
	 * @param      string    $reliable_repair_device       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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
		 * defined in reliable_repair_device_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The reliable_repair_device_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/reliable-repair-device-admin.css', array(), $this->version, 'all' );

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
		 * defined in reliable_repair_device_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The reliable_repair_device_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/reliable-repair-device-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function initialize_acf_options_page()
	{
		// Check function exists.
		if( function_exists('acf_add_options_page') ) {

			// Register options page.
			$parent = acf_add_options_page(array(
				'page_title'    => __('Repair Device'),
				'menu_title'    => __('Repair Device'),
				'menu_slug'     => 'add_device',
				'capability'    => 'manage_options',
				'redirect'      => false,
				'update_button' => __('Save Settings', 'acf'),
				'updated_message' => __("Settings Updated", 'acf'),
				'icon_url' 		=> 'dashicons-admin-settings',
			));
		}
	}


}
