<?php

global $wpdb;

$charset_collate = $wpdb->get_charset_collate();

$table_name = $wpdb->prefix . 'rrd_sub_device';
$table_name1 = $wpdb->prefix . 'rrd_sub_grand_device';


if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
	$sql = "CREATE TABLE `{$table_name}` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`device_name` int(11) NOT NULL,
	`sub_device_name` int(11) NOT NULL,
	`created_at` datetime DEFAULT CURRENT_TIMESTAMP,
	`modified_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL,
	PRIMARY KEY (`id`)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
}

if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name1'" ) != $table_name1 ) {
	$sql = "CREATE TABLE `{$table_name1}` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`device_name` int(11) NOT NULL,
	`sub_device_name` int(11) NOT NULL,
	`sub_grand_device_name` int(11) NOT NULL,
	`created_at` datetime DEFAULT CURRENT_TIMESTAMP,
	`modified_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL,
	PRIMARY KEY (`id`)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );
}