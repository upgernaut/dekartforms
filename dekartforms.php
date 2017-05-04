<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://aramkhachikyan.com
 * @since             1.0.0
 * @package           Dekartforms
 *
 * @wordpress-plugin
 * Plugin Name:       Dekart Forms
 * Plugin URI:        https://github.com/uptimex/dekartforms
 * Description:       Create forms and put it into your posts/pages.
 * Version:           1.0.0
 * Author:            Aram Khachikyan
 * Author URI:        http://aramkhachikyan.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dekartforms
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dekartforms-activator.php
 */
function activate_dekartforms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dekartforms-activator.php';
	Dekartforms_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dekartforms-deactivator.php
 */
function deactivate_dekartforms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dekartforms-deactivator.php';
	Dekartforms_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dekartforms' );
register_deactivation_hook( __FILE__, 'deactivate_dekartforms' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dekartforms.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dekartforms() {

	$plugin = new Dekartforms();
	$plugin->run();

}
run_dekartforms();
