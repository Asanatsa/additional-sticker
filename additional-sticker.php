<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.asanatsa.cc
 * @since             1.0.0
 * @package           Additional_Sticker
 *
 * @wordpress-plugin
 * Plugin Name:       自定义表情
 * Plugin URI:        http://www.asanatsa.cc/additional-sticker/
 * Description:       在评论里添加自定义的表情贴纸
 * Version:           alpha
 * Author:            Asanatsa
 * Author URI:        http://www.asanatsa.cc/
 * License:           GPL-3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       additional-sticker
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
define( 'ADDITIONAL_STICKER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-additional-sticker-activator.php
 */
function activate_additional_sticker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-additional-sticker-activator.php';
	Additional_Sticker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-additional-sticker-deactivator.php
 */
function deactivate_additional_sticker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-additional-sticker-deactivator.php';
	Additional_Sticker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_additional_sticker' );
register_deactivation_hook( __FILE__, 'deactivate_additional_sticker' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-additional-sticker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_additional_sticker() {

	$plugin = new Additional_Sticker();
	$plugin->run();

}
run_additional_sticker();
