<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    reliable_repair_device
 * @subpackage reliable_repair_device/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    reliable_repair_device
 * @subpackage reliable_repair_device/includes
 * @author     Your Name <email@example.com>
 */
class Reliable_Repair_Device {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      reliable_repair_device_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $reliable_repair_device    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'RELIABLE_REPAIR_DEVICE_VERSION' ) ) {
			$this->version = RELIABLE_REPAIR_DEVICE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'reliable-repair-device';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - reliable_repair_device_Loader. Orchestrates the hooks of the plugin.
	 * - reliable_repair_device_i18n. Defines internationalization functionality.
	 * - reliable_repair_device_Admin. Defines all hooks for the admin area.
	 * - reliable_repair_device_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/reliable-repair-device-dbs.php';
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-reliable-repair-device-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-reliable-repair-device-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-reliable-repair-device-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-reliable-repair-device-public.php';

		$this->loader = new Reliable_Repair_Device_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the reliable_repair_device_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Reliable_Repair_Device_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Reliable_Repair_Device_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_custom_option_page');
		$this->loader->add_action( 'acf/init', $plugin_admin, 'initialize_acf_options_page', 10, 3 );
		// $this->loader->add_filter( 'acf/prepare_field/name=add_sub_device', $plugin_admin, 'populate_add_sub_device_acf_select', 10, 3 );

		// $this->loader->add_action( 'plugins_loaded', $plugin_admin,'acf_options_setup' );
		// $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_site_options' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Reliable_Repair_Device_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		// $this->loader->add_action( 'init', $plugin_public, 'init' );
		add_shortcode( 'reliable_repair_device', array( $plugin_public, 'reliable_repair_device_shortcode_callback' ) );
		
	    $this->loader->add_action( 'wp_ajax_nopriv_rrd_get_sub_device_details', $plugin_public, 'load_add_device_details' );
		$this->loader->add_action( 'wp_ajax_rrd_get_sub_device_details', $plugin_public, 'load_add_device_details' );

		$this->loader->add_action( 'wp_ajax_rrd_get_grand_sub_device_details', $plugin_public, 'load_add_grand_device_details' );
		$this->loader->add_action( 'wp_ajax_nopriv_rrd_get_grand_sub_device_details', $plugin_public, 'load_add_grand_device_details' );
		
		$this->loader->add_action( 'wp_ajax_rrd_get_serice_details', $plugin_public, 'load_service_details' );
		$this->loader->add_action( 'wp_ajax_nopriv_rrd_get_serice_details', $plugin_public, 'load_service_details' );	
			
		$this->loader->add_action( 'wp_ajax_send_form', $plugin_public, 'send_form' );
		$this->loader->add_action( 'wp_ajax_nopriv_send_form', $plugin_public, 'send_form' );

		// $this->loader->add_filter( 'wpcf7_form_tag', $plugin_public, 'cf7_time_select_dropdown', 10, 2); 
		// $this->loader->add_filter( 'wpcf7_form_tag', $plugin_public, 'cf7_subdevice_select_dropdown', 10, 2); 
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    reliable_repair_device_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
