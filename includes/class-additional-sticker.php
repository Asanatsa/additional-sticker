<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.asanatsa.cc/project/additional-sticker
 * @since      1.1.1
 *
 * @package    Additional_Sticker
 * @subpackage Additional_Sticker/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.1.1
 * @package    Additional_Sticker
 * @subpackage Additional_Sticker/includes
 * @author     Asanatsa <me@asanatsa.cc>
 */
class Additional_Sticker {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.1.1
	 * @access   protected
	 * @var      Additional_Sticker_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.1.1
	 * @access   protected
	 * @var      string    $additional_sticker    The string used to uniquely identify this plugin.
	 */
	protected $additional_sticker;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.1.1
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.1.1
	 */
	public function __construct() {
		if ( defined( 'ADDITIONAL_STICKER_VERSION' ) ) {
			$this->version = ADDITIONAL_STICKER_VERSION;
		} else {
			$this->version = '1.1.1';
		}
		$this->additional_sticker = 'additional-sticker';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Additional_Sticker_Loader. Orchestrates the hooks of the plugin.
	 * - Additional_Sticker_i18n. Defines internationalization functionality.
	 * - Additional_Sticker_Admin. Defines all hooks for the admin area.
	 * - Additional_Sticker_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.1.1
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-additional-sticker-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-additional-sticker-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-additional-sticker-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-additional-sticker-public.php';

		$this->loader = new Additional_Sticker_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Additional_Sticker_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.1.1
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Additional_Sticker_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.1.1
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Additional_Sticker_Admin( $this->get_additional_sticker(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		//$this->loader->add_action( 'admin_menu', $plugin_admin, 'additional_sticker_menu_init' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.1.1
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Additional_Sticker_Public( $this->get_additional_sticker(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		$this->loader->add_filter('comment_form_defaults', $plugin_public, 'insert_panel', 25);
		//$this->loader->add_filter('comment_form_before_fields', $plugin_public, 'insert_panel', 25);
		$this->loader->add_filter('comment_text', $plugin_public, 'insert_stickers');
		

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.1.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.1.1
	 * @return    string    The name of the plugin.
	 */
	public function get_additional_sticker() {
		return $this->additional_sticker;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.1.1
	 * @return    Additional_Sticker_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.1.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
