<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://unaibamir.com
 * @since             1.0.0
 * @package           Life_Mastery_Group_Management
 *
 * @wordpress-plugin
 * Plugin Name:       Life Mastery Group Management
 * Plugin URI:        https://unaibamir.com
 * Description:       This is a custom plugin which creates the option for LearnDash group leaders to manage their groups for drip lessons and other info. 
 * Version:           1.0.0
 * Author:            Unaib Amir
 * Author URI:        https://unaibamir.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       life-mastery-group-management
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if (!function_exists("dd")) {
    function dd($data, $exit_data = true)
    {
        echo '<pre>' . print_r($data, true) . '</pre>';
        if ($exit_data == false) {
            echo '';
        } else {
            exit;
        }
    }
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LIFE_MASTERY_GROUP_MANAGEMENT_VERSION', '1.0.0' );


/**
 * Plugin Text Domain
 */
define( 'WPNP_LM_TEXT_DOMAIN', 'life-mastery-group-management' );

/**
 * Plugin Directory
 */
define( 'WPNP_LM_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPNP_LM_DIR_FILE', WPNP_LM_DIR . basename( __FILE__ ) );
define( 'WPNP_LM_INCLUDES_DIR', trailingslashit( WPNP_LM_DIR . 'includes' ) );
define( 'WPNP_LM_TEMPLATES_DIR', trailingslashit( WPNP_LM_DIR . 'templates' ) );
define( 'WPNP_LM_BASE_DIR', plugin_basename( __FILE__ ) );

/**
 * Plugin URLS
 */
define( 'WPNP_LM_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'WPNP_LM_ASSETS_URL', trailingslashit( WPNP_LM_URL . 'assets' ) );

define( 'WPNP_ACF_PATH', WPNP_LM_DIR . 'includes/plugins/acf-pro/' );
define( 'WPNP_ACF_URL', WPNP_LM_URL . 'includes/plugins/acf-pro/' );

// Include the ACF plugin.
include_once( WPNP_ACF_PATH . 'acf.php' );
include_once( WPNP_LM_DIR . 'includes/plugins/acf-fields.php' );

// Customize the url setting to fix incorrect asset URLs.
add_filter('acf/settings/url', 'wpnp_lm_acf_settings_url');
function wpnp_lm_acf_settings_url( $url ) {
    return WPNP_ACF_URL;
}

// (Optional) Hide the ACF admin menu item.
add_filter('acf/settings/show_admin', 'wpnp_lm_acf_settings_show_admin', 99999);
function wpnp_lm_acf_settings_show_admin( $show_admin ) {
    return false;
}

$zoom_plugin_file = plugin_dir_path(dirname(__FILE__)).'wp-zoom-addon/wp-zoom-addon.php';
if( file_exists($zoom_plugin_file) ) {
    require_once plugin_dir_path(dirname(__FILE__)).'/wp-zoom-addon/wp-zoom-addon.php';
    Video_Conferencing_With_Zoom::instance();
}

/**
 * 
 The code that runs during plugin activation.
 * This action is documented in includes/class-life-mastery-group-management-activator.php
 */
function activate_life_mastery_group_management() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-life-mastery-group-management-activator.php';
	Life_Mastery_Group_Management_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-life-mastery-group-management-deactivator.php
 */
function deactivate_life_mastery_group_management() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-life-mastery-group-management-deactivator.php';
	Life_Mastery_Group_Management_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_life_mastery_group_management' );
register_deactivation_hook( __FILE__, 'deactivate_life_mastery_group_management' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-life-mastery-group-management.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_life_mastery_group_management() {

	$plugin = new Life_Mastery_Group_Management();
	$plugin->run();

}
run_life_mastery_group_management();
