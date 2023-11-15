<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Additional_Sticker
 * @subpackage Additional_Sticker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Additional_Sticker
 * @subpackage Additional_Sticker/admin
 * @author     Your Name <email@example.com>
 */
class Additional_Sticker_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $additional_sticker    The ID of this plugin.
	 */
	private $additional_sticker;

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
	 * @param      string    $additional_sticker       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $additional_sticker, $version ) {

		$this->additional_sticker = $additional_sticker;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Additional_Sticker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Additional_Sticker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->additional_sticker, plugin_dir_url( __FILE__ ) . 'css/additional-sticker-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Additional_Sticker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Additional_Sticker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->additional_sticker, plugin_dir_url( __FILE__ ) . 'js/additional-sticker-admin.js', array( 'jquery' ), $this->version, false );

	}

	
	public function additional_sticker_menu_init(){



		add_menu_page(
			'测试页面',
			'测试选项',
			'manage_options',
			'additional_sticker_menu',
			'additional_sticker_section_cb',
			'',
			13
		);
	}

}





function additional_sticker_section_cb() {
	echo "test1";
}


function additional_sticker_field_cb () {
	echo "test2";
}