<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://unaibamir.com
 * @since      1.0.0
 *
 * @package    Life_Mastery_Group_Management
 * @subpackage Life_Mastery_Group_Management/includes
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
 * @package    Life_Mastery_Group_Management
 * @subpackage Life_Mastery_Group_Management/includes
 * @author     Unaib Amir <unaibamiraziz@gmail.com>
 */
class Life_Mastery_Group_Management {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Life_Mastery_Group_Management_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
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
		if ( defined( 'LIFE_MASTERY_GROUP_MANAGEMENT_VERSION' ) ) {
			$this->version = LIFE_MASTERY_GROUP_MANAGEMENT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'life-mastery-group-management';

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
	 * - Life_Mastery_Group_Management_Loader. Orchestrates the hooks of the plugin.
	 * - Life_Mastery_Group_Management_i18n. Defines internationalization functionality.
	 * - Life_Mastery_Group_Management_Admin. Defines all hooks for the admin area.
	 * - Life_Mastery_Group_Management_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/life-mastery-dbs.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-life-mastery-group-management-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-life-mastery-group-management-i18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-life-mastery-group-management-helpers.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-life-mastery-group-management-logging.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-life-mastery-group-management-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-life-mastery-group-management-public.php';

		$this->loader = new Life_Mastery_Group_Management_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Life_Mastery_Group_Management_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Life_Mastery_Group_Management_i18n();

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

		$plugin_admin = new Life_Mastery_Group_Management_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts', 999 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'ld_group_save_post', 10, 3 );
		$this->loader->add_action( 'acf/init', $plugin_admin, 'initialize_acf_options_page', 10, 3 );
		$this->loader->add_filter( 'acf/prepare_field/name=student_form', $plugin_admin, 'populate_student_form_options', 10, 3 );
		$this->loader->add_filter( 'mce_css', $plugin_admin, 'tuts_mcekit_editor_style', 10, 1);
		// $this->loader->add_filter( 'acf/prepare_field/name=enable_manage_classes', $plugin_admin, 'populate_ld_groups_acf_select', 10, 3 );
		$this->loader->add_filter( 'acf/prepare_field/name=enable_manage_courses', $plugin_admin, 'populate_ld_courses_acf_select', 10, 3 );
		$this->loader->add_filter( 'acf/prepare_field/name=select_course_configuration', $plugin_admin, 'populate_ld_courses_acf', 10, 3 );
		$this->loader->add_filter( 'acf/prepare_field/name=facilitator_select_course', $plugin_admin, 'populate_facilitator_select_course_options', 10, 3 );
		$this->loader->add_filter( 'acf/prepare_field/name=promise_select_course', $plugin_admin, 'populate_promise_select_course_options', 10, 3 );

		//$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_zoom_meta_boxes' );
		//$this->loader->add_action( 'save_post', $plugin_admin, 'ld_group_save_zoom', 999, 3 );

		$this->loader->add_action( 'init', $plugin_admin, 'remove_zoom_plugin_cron_job', 99 );

		$this->loader->add_action( 'init', $plugin_admin, 'modify_zoom_vimeo_hooks' );

		$this->loader->add_filter( 'option_zoom_recording_via', $plugin_admin, 'modify_zoom_recording_via_option', 9, 2 );

		$this->loader->add_filter( 'cron_schedules', $plugin_admin, 'lm_create_custom_cron_schedules' );
		   $this->loader-> add_action( 'admin_menu', $plugin_admin, 'my_acf_menu');

		$this->loader->add_action( 'wp_ajax_show_hide_que_feci', $plugin_admin, 'show_hide_que_feci' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Life_Mastery_Group_Management_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_public, 'init' );

		add_shortcode( 'lm_group_management', array( $plugin_public, 'lm_group_management_shortcode_callback' ) );
		// add_shortcode( 'lm_course_management', array( $plugin_public, 'lm_course_management_shortcode_callback' ) );

		$this->loader->add_action( 'wp_ajax_lm_group_schedule_callback', $plugin_public, 'lm_group_schedule_save_callback' );

		$this->loader->add_action( 'wp_ajax_lm_group_zoom_callback', $plugin_public, 'lm_group_zoom_save_callback' );

		$this->loader->add_action( 'wp_ajax_lm_group_attendance_callback', $plugin_public, 'lm_group_attendance_save_callback' );

		$this->loader->add_action( 'wp_ajax_lm_load_group_data', $plugin_public, 'lm_group_details_ajax_callback' );
		// $this->loader->add_action( 'wp_ajax_lm_load_course_data', $plugin_public, 'lm_course_details_ajax_callback' );
		//  change select value callback funcation
		
		$this->loader->add_action( 'wp_loaded', $plugin_public, 'lm_infusionsoft_listner_callback' );

		$this->loader->add_action( 'wp_ajax_lm_load_student_form_details', $plugin_public, 'load_student_form_details' );

		$this->loader->add_action( 'wp_ajax_lm_load_week_facilitator_instructions', $plugin_public, 'load_week_facilitator_instructions' );

		add_shortcode( 'lm_group_meeting', array( $plugin_public, 'lm_group_meeting_shortcode_callback' ) );

		add_shortcode( 'lm_meeting_recordings', array( $plugin_public, 'lm_meeting_recordings_shortcode_callback' ) );

		$this->loader->add_action( 'bp_after_profile_field_content', $plugin_public, 'add_member_settings_field' );

		$this->loader->add_action( 'xprofile_updated_profile', $plugin_public, 'save_member_settings_field' );

		add_shortcode( 'lm_zoom_api_link', array( $plugin_public, 'lm_zoom_api_link_shortcode_callback' ) );
		
		//$this->loader->add_action( 'lm_zoom_wp_main_cron_sync_data_hook', $plugin_public, 'lm_zoom_main_cron_sync_user_meeting_data' );

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
	 * @return    Life_Mastery_Group_Management_Loader    Orchestrates the hooks of the plugin.
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
