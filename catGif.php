<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
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
 * Author:            Your Name or Your Company
 * Author URI:        https://github.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       catGif
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-catGif-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-catGif-activator.php';
	Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-catGif-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-catGif-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-catGif.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

	$plugin = new Plugin_Name();
	$plugin->run();

}
run_plugin_name();

function wporg_more_comments( $post_id ) {
    echo '<p class="comment-form-more-comments"><label for="more-comments">' . __( 'More Comments', 'your-theme-text-domain' ) . '</label> <textarea id="more-comments" name="more-comments" cols="45" rows="8" aria-required="true"></textarea></p>';
}
 
add_action( 'comment_form', 'wporg_more_comments' );

add_filter( 'preprocess_comment' , 'preprocess_comment_remove_url' );

#https://developer.wordpress.org/reference/hooks/comment_text/

function preprocess_comment_remove_url( $commentdata ) {
   // Always remove the URL from the comment author's comment
   unset( $commentdata['comment_author_url'] );
 
   // If the user is speaking in all caps, lowercase the comment
   if( $commentdata['comment_content'] == strtoupper( $commentdata['comment_content'] ) ) {
      $commentdata['comment_content'] = ucwords( strtolower( $commentdata['comment_content'] ) );
   }
 
   return $commentdata;
}
