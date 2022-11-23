<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://unaibamir.com
 * @since      1.0.0
 *
 * @package    Life_Mastery_Group_Management
 * @subpackage Life_Mastery_Group_Management/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Life_Mastery_Group_Management
 * @subpackage Life_Mastery_Group_Management/includes
 * @author     Unaib Amir <unaibamiraziz@gmail.com>
 */
class Life_Mastery_Group_Management_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'life-mastery-group-management',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
