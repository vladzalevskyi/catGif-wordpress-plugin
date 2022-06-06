<?php

/**
 * 
 * The plugin bootstrap file: This file is read by WordPress to generate the plugin
 * information in the plugin admin area. This file also includes all of the dependencies
 * used by the plugin, registers the activation and deactivation functions, and defines 
 * a function that starts the plugin.
 *
 * @link              https://maiamaster.udg.edu/
 * @since             0.0.1
 * @package           CatGif
 *
 * @wordpress-plugin
 * Plugin Name:       CatGif Helper
 * Plugin URI:        https://maiamaster.udg.edu/
 * Description:       This is a simple plugin to send cute cat gifs.
 * Version:           0.0.1
 * Author:            team project
 * Author URI:        https://github.com/Vivikar/catGif-wordpress-plugin
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       catGif
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
   echo "What are you doing here?";
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

// The code that runs during plugin activation.
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-catGif-activator.php';
	catGif_Activator::activate();
}

// The code that runs during plugin deactivation.
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-catGif-deactivator.php';
	catGif_Deactivator::deactivate();
}

// Add activation and deactivation hooks
register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

// Inlude the core cat gif class file
require plugin_dir_path( __FILE__ ) . 'includes/class-catGif.php';

// Run the plugin every time it's activated
function run_plugin_name() {
	$cat_gif_plugin = new CatGifPlugin();
	$cat_gif_plugin->run();
}

run_plugin_name();
