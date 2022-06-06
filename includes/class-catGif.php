<?php

/**
 * The file that defines the core plugin class
 *
 * @link       https://github.com/Vivikar/catGif-wordpress-plugin
 * @since      1.0.0
 *
 * @package    CatGif-Wordpress-Plugin
 * @subpackage CatGif-Wordpress-Plugin/includes
 */

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    CatGif-Wordpress-Plugin
 * @subpackage CatGif-Wordpress-Plugin/includes
 * @author     Your Name <email@example.com>
 */
class CatGifPlugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The catGifPluging adds a button in the comments section that allows the users
	 * to send cat gifs related to the comment they have written.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, and set the hooks the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

	public function __construct(){
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'catGif';

		$this->load_dependencies();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - catGif_Loader. Orchestrates the hooks of the plugin.
	 * - catGif_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-catGif-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-catGif-public.php';

		$this->loader = new catGif_Loader();

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new catGif_Public( $this->get_plugin_name(), $this->get_version() );
		
		// Add our style to the button
		$this->loader->add_action( "wp_enqueue_scripts", $plugin_public, 'enqueue_styles');
		
		// Create cat gif button
		$this->loader->add_action( "comment_form", $plugin_public, "add_send_cat_gif_button");
		
		// Search for the gif with the desired message using giphy API
		$this->loader->add_filter( "preprocess_comment", $plugin_public, "send_gif_as_comment");
		
		// Disable wordpress flood filter to post comments more frequently
		$this->loader->add_filter( "comment_flood_filter", $plugin_public, "return_false");
		
		// Allow posting of same comments
		$this->loader->add_filter( "duplicate_comment_id", $plugin_public, "return_false");
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    catGif_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
 }
