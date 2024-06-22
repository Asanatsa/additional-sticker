<?php
/**
 * 
 * 
 * @since    1.1.1
 * @package    Additional_Sticker
 * @subpackage Additional_Sticker/includes
 */
class Additional_sticker_setup {
    public static function add_stiker($source_dir){

        global $wpdb;

        if (is_null($source_dir) || !file_exists($source_dir . "/info.json") || !is_admin()) {
            return false;
        }

        $sticker_dir = WP_CONTENT_DIR . "/" . "stickers";
        if (!file_exists($sticker_dir)) {
            mkdir($sticker_dir);
        }

        //covert info.json to array
        $data = json_decode(file_get_contents($source_dir . "/info.json"));


        $s = $sticker_dir . "/" . $data->id;
        if (!file_exists($s)){
            mkdir($s);
        }
        

        //copy file
        $source_files = scandir($source_dir);
        foreach ($source_files as $v) {
            $f = $source_dir . "/" . $v;

            if (is_dir($f) || $v === "info.json") {
                continue;
            }

            if (!copy($f, $s . "/" . $v)) {
                wp_die($f, $s . "/" . $v, "");
                return false;
            }

        }

        //insert to db
        $info_sql = "INSERT IGNORE INTO `{$wpdb->prefix}sticker_group` (`group_id`, `name`, `icon`, `description`, `author`, `url`) VALUES 
        ('{$data->id}', '{$data->name}', '{$data->icon}', '{$data->notice}', '{$data->author}', '{$data->url}');
    ";

        if (!$wpdb->query($info_sql)) {
            return false;
        }

        $stickers_sql = "INSERT INTO `{$wpdb->prefix}stickers` (`id`, `group_id`, `name`, `src`, `data`) VALUES ";
        foreach ($data->stickers as $n => $v) {
            if ($n + 1 === count($data->stickers)) {
                $stickers_sql .= "('{$v->id}', '{$data->id}', '{$v->name}', '{$v->src}', '');";
            } else {
                $stickers_sql .= "('{$v->id}', '{$data->id}', '{$v->name}', '{$v->src}', ''),";
            }
        }

        if (!$wpdb->query($stickers_sql)) {
            return false;
        }

        return true;
    }


    public static function init_db(){
        global $wpdb;

		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}stickers` (
			`id` varchar(255) NOT NULL,
			`group_id` varchar(255) NOT NULL,
			`name` text NOT NULL,
			`src` text NOT NULL,
			`data` text NOT NULL,
			`num` int NOT NULL AUTO_INCREMENT PRIMARY KEY
		  ) ENGINE=`InnoDB`;";
        $wpdb->query($sql);

		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sticker_group` (
			`group_id` varchar(255) NOT NULL,
			`name` text NOT NULL,
			`icon` text NOT NULL,
			`description` text NOT NULL,
			`author` text NOT NULL,
			`url` text NOT NULL,
			PRIMARY KEY (`group_id`)
		  ) ENGINE=`InnoDB`;";// MyISAM dosen't support large-size key
		$wpdb->query($sql);
    }

    public static function rm_all_dir($dir){
        if (!is_dir($dir)){
            return false;
        }

        foreach (scandir($dir) as $s) {
            if ($s === "." || $s === ".."){
                continue;
            } elseif (is_dir($dir . "/" . $s)) {
                self::rm_all_dir($dir . "/" . $s);
            } else{
                unlink($dir . "/" . $s);
            }
        }
        rmdir($dir);
    }
}
?>