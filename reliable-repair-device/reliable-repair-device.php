<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           reliable_repair_device
 *
 * @wordpress-plugin
 * Plugin Name:       Reliable Repair Device
 * Plugin URI:        http://example.com/reliable-repair-device-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       reliable-repair-device
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPNP_LM_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPNP_LM_DIR_FILE', WPNP_LM_DIR . basename( __FILE__ ) );
define( 'WPNP_LM_INCLUDES_DIR', trailingslashit( WPNP_LM_DIR . 'includes' ) );
define( 'WPNP_LM_BASE_DIR', plugin_basename( __FILE__ ) ); 
define( 'WPNP_LM_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'RELIABLE_REPAIR_DEVICE_VERSION', '1.0.0' );
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
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-reliable-repair-device-activator.php
 */
function activate_reliable_repair_device() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-reliable-repair-device-activator.php';
	Reliable_Repair_Device_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-reliable-repair-device-deactivator.php
 */
function deactivate_reliable_repair_device() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-reliable-repair-device-deactivator.php';
	Reliable_Repair_Device_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_reliable_repair_device' );
register_deactivation_hook( __FILE__, 'deactivate_reliable_repair_device' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-reliable-repair-device.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_reliable_repair_device() {

	$plugin = new Reliable_Repair_Device();
	$plugin->run();

}
run_reliable_repair_device();
