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
 * Author:            team project
 * Author URI:        https://github.com/Vivikar/catGif-wordpress-plugin
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

require plugin_dir_path( __FILE__ ) . 'includes/class-catGif.php';


// connect the public style
function public_css(){
   // enqueue css script
   wp_register_style( 'public-style', plugins_url('/public/css/catGif-public.css',__FILE__ ), false, '1.0.0', 'all' );
   wp_enqueue_style( 'public-style' );
}

add_action('wp_enqueue_scripts', 'public_css');

// Add send cat gif button 
function add_send_cat_gif_button( $args ) {
   echo '<div class="form-submit">';
   echo '<input name="catgif" class="btn fourth" type="submit" id="catgif" value="Send Cat Gif">';
	echo '</div>';
}

// Use add action to add the button when the page is laoded
add_action( 'comment_form', 'add_send_cat_gif_button' );

/**  Using a Giphy API token create an API instance and do the 
 *   search for the gif with the desired message
 */
function search_for_cat_gif( $comment_content ){
   // path to the cloned repo 
	require_once plugin_dir_path( __FILE__ ) . 'giphy-php-client/vendor/autoload.php';

   // creating api instance
   $api_instance = new GPH\Api\DefaultApi();

   // api token
   $API_KEY = 'AsTZs872SKSwgyevUPQWgadXgxJwYKWJ';

   try {    
      // the maximum number of records to return.
      $limit = 25;
      // optional results offset. Defaults to 0.
      $offset = 0; 
      // filters results by specified rating.
      $rating = "g";
      // Specify default country for regional content; use a 2-letter ISO 639-1 country code. 
      // See list of supported languages <a href = \"../language-support\">here</a>.
      $lang = "en";
      // indicate the expected response format. Default is Json.
      $fmt = "json";

      // cat to the search get with from giphy api client
      $result = $api_instance->gifsSearchGet($API_KEY, 'cat ' . $comment_content, $limit, $offset, $rating, $lang, $fmt);
      // decode the result 
      $json_result = json_decode($result);
      
      //returning the embed url
      return $json_result->data[0]->images->original->url;

   } catch (Exception $e) {
         echo 'Exception when calling DefaultApi->gifsSearchGet: ', $e->getMessage(), PHP_EOL;
   }
}

// Send a cat gif if the post request was submit by the cat gif button
function send_gif_as_comment( $commentdata ) {
   global $GIF;

   // if the post request is from the cat gif button
   if (isset($_POST['catgif']))
   {
      // request the embed url to giphy api with the comment content info
      $img_src = search_for_cat_gif($commentdata['comment_content']);

      // get the url of the gif and use it in an image element
      $GIF = "<image src=\"$img_src\" width=\"50%\" height=\"50%\" frameBorder=\"0\" class=\"giphy-embed\">";
      
      // modify the content of the message and post
      $commentdata['comment_content'] = $GIF;
   }
   return $commentdata;
}

add_filter( 'preprocess_comment' , 'send_gif_as_comment' );

// disable wordpress flood filter to post comments more frequently
add_filter('comment_flood_filter', '__return_false');
// post same comments posible
add_filter('duplicate_comment_id', '__return_false');