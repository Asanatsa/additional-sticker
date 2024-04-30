<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.asanatsa.cc/project/additional-sticker
 * @since      1.1.0
 *
 * @package    Additional_Sticker
 * @subpackage Additional_Sticker/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.1.0
 * @package    Additional_Sticker
 * @subpackage Additional_Sticker/includes
 * @author     Asanatsa <me@asanatsa.cc>
 */
class Additional_Sticker_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.1.0
	 */
	public static function activate()
	{
		include dirname(__FILE__) . "/". "class-additional-sticker-setup.php";

		// init db (create table)
		Additional_sticker_setup::init_db();

		$s = dirname(dirname(__FILE__)) . "/" . "stickers";
		
		foreach (scandir($s) as $f) {
			if (!is_dir($s . "/" . $f) || $f === "." || $f ===".."){
				continue;
			} elseif (!Additional_sticker_setup::add_stiker($s . "/" . $f)) {
				wp_die("add stiker failed", "Error");
			}
		}

		
		if (!copy(dirname(dirname(__FILE__)) . "/" . "stickers/icon.png", WP_CONTENT_DIR . "/" . "stickers/icon.png")){
			wp_die("copy failed", "Error");
		}



	}
}
