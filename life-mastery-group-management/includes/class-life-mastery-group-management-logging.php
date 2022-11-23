<?php
/**
 * Class for logging events and errors
 *
 * @package     EDD
 * @subpackage  Logging
 * @copyright   Copyright (c) 2015, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.3.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if( class_exists('LifeMystery_Logging') ) return;

/**
 * LifeMystery_Logging Class
 *
 * A general use class for logging events and errors.
 *
 * @since 1.3.1
 */

if( !class_exists('LifeMystery_Logging') ) {

	class LifeMystery_Logging {

		public $is_writable = true;
		public $filename   = '';
		public $file       = '';

		/**
		 * Set up the EDD Logging Class
		 *
		 * @since 1.3.1
		 */
		public function __construct() {

			add_action( 'plugins_loaded', array( $this, 'setup_log_file' ), 0 );

		}

		/**
		 * Sets up the log file if it is writable
		 *
		 * @since 2.8.7
		 * @return void
		 */
		public function setup_log_file() {

			$upload_dir       = wp_upload_dir();
			$this->filename   = wp_hash( home_url( '/' ) ) . '-lm-debug.log';
			$this->file       = trailingslashit( $upload_dir['basedir'] ) . $this->filename;

			if ( ! is_writeable( $upload_dir['basedir'] ) ) {
				$this->is_writable = false;
			}

		}

		/**
		 * Retrieve the log data
		 *
		 * @since 2.8.7
		 * @return string
		 */
		public function get_file_contents() {
			return $this->get_file();
		}

		/**
		 * Log message to file
		 *
		 * @since 2.8.7
		 * @return void
		 */
		public function log_to_file( $message = '' ) {
			$message = date( 'Y-n-d H:i:s' ) . ' - ' . $message . "\r\n";
			$this->write_to_log( $message );

		}

		/**
		 * Retrieve the file data is written to
		 *
		 * @since 2.8.7
		 * @return string
		 */
		public function get_file() {

			$file = '';

			if ( @file_exists( $this->file ) ) {

				if ( ! is_writeable( $this->file ) ) {
					$this->is_writable = false;
				}

				$file = @file_get_contents( $this->file );

			} else {

				@file_put_contents( $this->file, '' );
				@chmod( $this->file, 0664 );

			}

			return $file;
		}

		/**
		 * Write the log message
		 *
		 * @since 2.8.7
		 * @return void
		 */
		public function write_to_log( $message = '' ) {
			$file = $this->get_file();
			$file .= $message;
			@file_put_contents( $this->file, $file );
		}

		/**
		 * Delete the log file or removes all contents in the log file if we cannot delete it
		 *
		 * @since 2.8.7
		 * @return void
		 */
		public function clear_log_file() {
			@unlink( $this->file );

			if ( file_exists( $this->file ) ) {

				// it's still there, so maybe server doesn't have delete rights
				chmod( $this->file, 0664 ); // Try to give the server delete rights
				@unlink( $this->file );

				// See if it's still there
				if ( @file_exists( $this->file ) ) {

					/*
					 * Remove all contents of the log file if we cannot delete it
					 */
					if ( is_writeable( $this->file ) ) {

						file_put_contents( $this->file, '' );

					} else {

						return false;

					}

				}

			}

			$this->file = '';
			return true;

		}

		/**
		 * Return the location of the log file that LifeMystery_Logging will use.
		 *
		 * Note: Do not use this file to write to the logs, please use the `lm_debug_log` function to do so.
		 *
		 * @since 2.9.1
		 *
		 * @return string
		 */
		public function get_log_file_path() {
			return $this->file;
		}

	}
}

// Initiate the logging system
$GLOBALS['lm_logs'] = new LifeMystery_Logging();

/**
 * Logs a message to the debug log file
 *
 * @since 2.8.7
 * @since 2.9.4 Added the 'force' option.
 *
 * @param string $message
 * @global $lm_logs EDD Logs Object
 * @return void
 */

if( !function_exists('lm_debug_log') ) {
	function lm_debug_log( $message = '', $force = false ) {
		global $lm_logs;

		if( function_exists( 'mb_convert_encoding' ) ) {

			$message = mb_convert_encoding( $message, 'UTF-8' );

		}

		$lm_logs->log_to_file( $message );
	}
}




function lm_listner_callback() {

	global $lm_logs;
	
	if( !function_exists('lm_debug_log') ) {
		return;
	}

	if( !isset($_GET['lm-listner']) ) {
		return;
	}


	$arr_rh = json_decode( file_get_contents( 'php://input' ), TRUE );

	if ( ! isset( $arr_rh['event_key'] ) OR ! $arr_rh['event_key'] OR ! isset( $arr_rh['object_type'] ) OR ! $arr_rh['object_type'] OR ! isset( $arr_rh['object_keys'] ) OR ! $arr_rh['object_keys'] OR ! isset( $arr_rh['api_url'] ) ) :
		exit;
	endif;

	lm_debug_log( sprintf('LM TAGS: %s', maybe_serialize( $arr_rh ) )  );

}

//add_action( 'wp_loaded', 'lm_listner_callback');