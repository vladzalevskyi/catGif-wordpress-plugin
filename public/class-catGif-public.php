<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/Vivikar/catGif-wordpress-plugin
 * @since      1.0.0
 *
 * @package    CatGif-Wordpress-Plugin
 * @subpackage CatGif-Wordpress-Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    CatGif-Wordpress-Plugin
 * @subpackage CatGif-Wordpress-Plugin/public
 * @author     team project
 */
class catGif_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// enqueue all our scripts: css, js
		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__) . 'css/catGif-public.css',
			false,
			$this->version,
			'all'
		);

	}
	
	/**
	 * Simple return false to avoid some filterings
	 *
	 * @since    1.0.0
	 */
	public function return_false(){
		return false;
	}

	/**
	 * Add the "Send Cat Gif button in the comment section
	 *
	 * @since    1.0.0
	 */
	public function add_send_cat_gif_button(){
		/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
		echo '<div class="form-submit">';
		echo '<input name="catgif" class="btn fourth" type="submit" id="catgif" value="Send Cat Gif">';
		echo '</div>';
	}
	
	/**
	 * Check if the comment was done with the send cat gif button and if so search for the propper gif.
	 * Return the gif as an image element.
	 *
	 * @since    1.0.0
	 * @param 	 $commentdata   Content of the comment post, array
	 */
	public function send_gif_as_comment( $commentdata ) {
		// Send a cat gif if the post request was submitted by the cat gif button

		global $GIF;
		
		// If the post request is from the cat gif button
		if (isset($_POST['catgif']))   // Check that the post from catgif exists and is not null
		{	
			// Request a gif url to giphy api based on the comment content info
			$img_src = $this->search_for_cat_gif($commentdata['comment_content']);

			// Get the url of the gif and use it in an image element
			$GIF = "<image src=\"$img_src\" width=\"50%\" height=\"50%\" frameBorder=\"0\" class=\"giphy-embed\">";
			
			// modify the content of the message and post
			$commentdata['comment_content'] = $GIF;
		}
		return $commentdata;
	}

	/**
	 * Add cat tag and do the search in giphy using their API
	 *
	 * @since    1.0.0
	 * @param 	 $comment_content   Content of the posted comment, string
	 */
	private function search_for_cat_gif( $comment_content ){
		require_once plugin_dir_path( __FILE__ ) . '../giphy-php-client/vendor/autoload.php';
		
		// Creating api instance 
		$api_instance = new GPH\Api\DefaultApi();
		
		// Giphy API token (user) needed to use the API
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
		
			// Add cat keyword and do a search get with from giphy api client
			$result = $api_instance->gifsSearchGet(
				$API_KEY, 'cat ' . $comment_content, $limit, $offset, $rating, $lang, $fmt);
			
			// Decode the response
			$json_result = json_decode($result);
			
			// Return the gif url
			return $json_result->data[0]->images->original->url;
		
		} catch (Exception $e) {
					echo 'Exception when calling DefaultApi->gifsSearchGet: ', $e->getMessage(), PHP_EOL;
		}
	}

}
