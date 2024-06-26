<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://www.asanatsa.cc/project/additional-sticker
 * @since      1.1.1
 *
 * @package    Additional_Sticker
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

function rm_all_dir($dir){
	if (!is_dir($dir)){
		return false;
	}

	foreach (scandir($dir) as $s) {
		if ($s === "." || $s === ".."){
			continue;
		} elseif (is_dir($dir . "/" . $s)) {
			rm_all_dir($dir . "/" . $s);
		} else{
			unlink($dir . "/" . $s);
		}
	}
	rmdir($dir);
}


global $wpdb;
$sql = "DROP TABLE `{$wpdb->prefix}sticker_group`;";
$wpdb->query($sql);

$sql = "DROP TABLE `{$wpdb->prefix}stickers`;";
$wpdb->query($sql);

rm_all_dir(WP_CONTENT_DIR . "/" . "stickers");
