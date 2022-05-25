<?php

/**
 * 
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
define( 'PLUGIN_NAME_VERSION', '0.0.1' );

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

 // Allowing users to embed iframe objects in wordpress
global $allowedtags;
$allowedtags["iframe"] = array(
   "src" => array(),
   "height" => array(),
   "width" => array(),
   "frameBorder" => array(),
   "class" => array(),
   
  );

// DISPLAY SEND GIF BUTTON

// TODO: Change func's logic to display "Send CatGif" button and link it to the gif
// class that makes requests to giphy and retrieves gif ids and then passes it to another 
// function below to display them

function add_send_cat_gif_button( $args ) {
   echo '<div class="form-submit">';
   echo '<input name="catgif" type="submit" id="catgif" class="button button-primary" value="Send Cat Gif">';
	echo '</div>';
}

add_action( 'comment_form', 'add_send_cat_gif_button' );


function search_for_cat_gif( $comment_content ){
   global $GIF_IFRAME_PATTERN;

   # path to the clone repo 
   require_once('/opt/lampp/htdocs/wordpress/wp-content/plugins/catGif-wordpress-plugin/giphy-php-client/autoload.php');

   $api_instance = new GPH\Api\DefaultApi();
   $API_KEY = 'AsTZs872SKSwgyevUPQWgadXgxJwYKWJ';

   # TODO add gify API
   try {    
      $limit = 25; // int | The maximum number of records to return.
      $offset = 0; // int | An optional results offset. Defaults to 0.
      $rating = "g"; // string | Filters results by specified rating.
      $lang = "en"; // string | Specify default country for regional content; use a 2-letter ISO 639-1 country code. See list of supported languages <a href = \"../language-support\">here</a>.
      $fmt = "json"; // string | Used to indicate the expected response format. Default is Json.

      $result = $api_instance->gifsSearchGet($API_KEY, $comment_content, $limit, $offset, $rating, $lang, $fmt);
      $json_result = json_decode($result);
      $GIF_IFRAME_PATTERN = '<img src="' . $json_result->data[0]->images->downsized->url . '" width=\"480\" height=\"359\" frameBorder=\"0\" frameBorder=\"0\">';

   } catch (Exception $e) {
         echo 'Exception when calling DefaultApi->gifsSearchGet: ', $e->getMessage(), PHP_EOL;
   }
   //$GIF_IFRAME_PATTERN = "<iframe src=\"https://giphy.com/embed/$GIF_ID\" width=\"480\" height=\"359\" frameBorder=\"0\" class=\"giphy-embed\" allowFullScreen></iframe>";
}

// // SEND GIF INSTEAD OF THE COMMENT
// TODO: Transmit gif ids from the classes/functions that do requests to this function
function send_gif_as_comment( $commentdata ) {
   // NEEDS TO ADRESS A GLOBAL VARIABLE TO SEE IT
   global $GIF_IFRAME_PATTERN;

   // TODO: Change if condition to button 'Send CatGif' pressed or smth
   if (str_contains($commentdata['comment_content'], "cat") & (isset($_POST['catgif'])))
   {
      search_for_cat_gif($commentdata['comment_content']);
      $commentdata['comment_content'] = $GIF_IFRAME_PATTERN;
   }
   return $commentdata;
}

add_filter( 'preprocess_comment' , 'send_gif_as_comment' );

// DISABLE WORDPRESS FLOOD FILTER TO BE ABLE TO POST MORE COMMENTS MORE FREQUENTLY
// AND POST SAME COMMENTS
add_filter('comment_flood_filter', '__return_false');
add_filter('duplicate_comment_id', '__return_false');
