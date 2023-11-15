<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Additional_Sticker
 * @subpackage Additional_Sticker/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Additional_Sticker
 * @subpackage Additional_Sticker/includes
 * @author     Your Name <email@example.com>
 */
class Additional_Sticker_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		
		global $wpdb;

		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}stickers` (
			`id` varchar(255) NOT NULL,
			`group_id` varchar(255) NOT NULL,
			`name` text NOT NULL,
			`src` text NOT NULL,
			`data` text NOT NULL,
			`num` int NOT NULL,
			PRIMARY KEY (`num`)
		  ) ENGINE=InnoDB;";

		$wpdb->query($sql);


		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sticker_group` (
			`group_id` varchar(255) NOT NULL,
			`name` text NOT NULL,
			`icon` text NOT NULL,
			`description` text NOT NULL,
			`author` text NOT NULL,
			`url` text NOT NULL,
			PRIMARY KEY (`group_id`)
		  ) ENGINE=InnoDB";
		$wpdb->query($sql);



		$sticker_dir = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . "stickers";
		if ( !file_exists( $sticker_dir )){
			mkdir( $sticker_dir );



			//test only
			$s = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
			mkdir( $sticker_dir . DIRECTORY_SEPARATOR . "sblj");
			mkdir( $sticker_dir . DIRECTORY_SEPARATOR . "urara");

			//别问我为什么数组不单独声明，问就是懒，另外只是个测试，后期会删
			foreach (array("sblj", "urara") as $value) {
				$files = scandir($s . "stickers" . DIRECTORY_SEPARATOR . $value);
				foreach ($files as $v) {
					if(is_dir($s . "stickers" . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR . $v)){
						continue;
					}

					if(!copy($s . "stickers" . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR . $v , $sticker_dir . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR . $v)){
						wp_die( "文件复制出错", "错误");
					}
				}
			}

		}



		

		//test only

		$sql = "INSERT INTO `{$wpdb->prefix}stickers` (`id`, `group_id`, `name`, `src`, `data`, `num`) VALUES
		('cry', 'sblj', '哭哭', 'cry.png', '', 1),
		('endure', 'urara', '忍住', 'endure.png', '', 2),
		('foxing', 'sblj', '佛性', 'foxing.png', '', 3),
		('happy', 'sblj', '乐了', 'happy.png', '', 4),
		('huh', 'urara', '哼哼', 'huh.png', '', 5),
		('lol', 'urara', '乐', 'lol.png', '', 6),
		('sad', 'sblj', '大悲', 'sad.png', '', 7),
		('sad', 'urara', '伤心', 'sad.png', '', 8),
		('shocked', 'sblj', '惊', 'shocked.png', '', 9),
		('shy', 'sblj', '羞', 'shy.png', '', 10),
		('silly', 'sblj', '变傻', 'silly.png', '', 11),
		('sleepy', 'urara', '打瞌睡', 'sleepy.png', '', 12),
		('sleepy', 'sblj', '困', 'sleepy.png', '', 13),
		('so_what', 'urara', '切', 'so_what.png', '', 14),
		('supported', 'urara', '打call', 'supported.png', '', 15),
		('wow', 'urara', '哇哦', 'wow.png', '', 16);";
		$wpdb->query($sql);

		$sql = "INSERT INTO `{$wpdb->prefix}sticker_group` (`group_id`, `name`, `icon`, `description`, `author`, `url`) VALUES
		('sblj', '傻不啦叽诗歌剧', 'foxing.png', '@祭祭', '祭祭', ''),
		('urara', '乌拉拉', 'supported.png', '@祭祭', '祭祭', '');";

		$wpdb->query($sql);



	}
}
