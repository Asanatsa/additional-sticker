<?php
/**
 * exract functions of plugin
 * 
 * @since    1.1.1
 * @package    Additional_Sticker
 * @subpackage Additional_Sticker/includes
 */
class Additional_sticker_functions {
    /**
     * adds sticker from dir
     *
     * @since 1.1.1
     * @param string $source_dir source dir
     * @return array
     */
    public static function add_stiker( $source_dir ) {

        global $wpdb;

        if ( is_null( $source_dir ) || !file_exists( $source_dir . '/info.json' ) || !is_admin() ) {
            return array( false, 1,'info.json not exists' );
        }

        $sticker_dir = WP_CONTENT_DIR . '/' . 'stickers';
        if ( !file_exists($sticker_dir) ) {
            mkdir( $sticker_dir );
        }

        //covert info.json to array
        $data = json_decode( file_get_contents( $source_dir . '/info.json' ) );

        $rn = $wpdb->query( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}sticker_group` WHERE group_id=%s;", $data->id ) );
        if ($rn != 0 || $rn === false) {
            return array(false, 2, 'already exists');
        }


        if ( is_null( $data ) || !isset( $data->id ) || !isset( $data->name ) || !isset( $data->icon ) || !isset( $data->stickers ) ) {
            return array(false, 3, 'format error');
        }


        $s = $sticker_dir . '/' . $data->id;
        if ( !file_exists( $s ) ){
            mkdir( $s );
        }
        

        //copy file
        //$source_files = scandir($source_dir);
        foreach ( $data->stickers as $v ) {
            $fname = $v->src;
            $f = $source_dir . '/' . $fname;

            if ( !copy( $f, $s . '/' . $fname )) {
                wp_die("failed when copy {$f} to {$s}/{$fname}", "Copy failed");
                return array( false, 4, 'copy failed' );
            }

        }

        $notice = isset( $data->notice ) ? $data->notice : '';
        $author = isset( $data->author ) ? $data->author : '';
        $url = isset( $data->url ) ? $data->url : '';
        $copyright = isset( $data->copyright ) ? $data->copyright : '';


        $stickers_sql = "INSERT INTO `{$wpdb->prefix}stickers` (`id`, `group_id`, `name`, `src`, `data`) VALUES ";
        $values = array();
        foreach ( $data->stickers as $v ) {
            $values[] = $wpdb->prepare(
            '(%s, %s, %s, %s, %s)',
            $v->id,
            $data->id,
            $v->name,
            $v->src,
            ''
            );
        }
        $stickers_sql .= implode( ',', $values ) . ";";
        if (!$wpdb->query( $stickers_sql )) {
            return array( false, 6, 'insert stickers failed' );
        }

        //insert to db
        $info_sql = $wpdb->prepare(
            "INSERT IGNORE INTO `{$wpdb->prefix}sticker_group` (`group_id`, `name`, `icon`, `description`, `author`, `copyright`, `url`) VALUES (%s, %s, %s, %s, %s, %s, %s);",
            $data->id,
            $data->name,
            $data->icon,
            $notice,
            $author,
            $copyright,
            $url
        );

        if (!$wpdb->query( $info_sql ) ) {
            return array( false, 5, 'insert group info failed' );
        }

        

        return array( true, 0, 'success' );
    }

    /**
     * init db
     *
     * @since 1.1.1
     */
    public static function init_db() {
        global $wpdb;

		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}stickers` (
			`id` varchar(255) NOT NULL,
			`group_id` varchar(255) NOT NULL,
			`name` text NOT NULL,
			`src` text NOT NULL,
			`data` text NOT NULL,
			`num` int NOT NULL AUTO_INCREMENT PRIMARY KEY
		  ) ENGINE=`InnoDB`;";
        $wpdb->query( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sticker_group` (
			`group_id` varchar(255) NOT NULL,
			`name` text NOT NULL,
			`icon` text NOT NULL,
			`description` text NOT NULL,
			`author` text NOT NULL,
            `copyright` text NOT NULL,
			`url` text NOT NULL,
            `enabled` int NOT NULL DEFAULT 1,
			PRIMARY KEY (`group_id`)
		  ) ENGINE=`InnoDB`;";// MyISAM dosen't support large-size key
		$wpdb->query( $sql );
    }

    /**
     * removes dir and all files in it
     *
     * @since 1.1.1
     * @param string $dir dir to remove
     * @return bool
     */
    public static function rm_all_dir( $dir ){
        if ( !is_dir( $dir ) ){
            return false;
        }

        foreach ( scandir($dir) as $s ) {
            if ( $s === '.' || $s === '..' ){
                continue;
            } elseif ( is_dir( $dir . '/' . $s ) ) {
                self::rm_all_dir( $dir . '/' . $s );
            } else{
                unlink( $dir . '/' . $s );
            }
        }
        rmdir( $dir );

        return true;
    }

    /**
     * remove sticker
     *
     * @since 2.0.0
     * @param string $group_id group id of sticker
     * @return bool
     */
    public static function remove_sticker( $group_id ) {
        global $wpdb;

        if ( is_null( $group_id ) || is_admin() ){
            return false;
        }
        
        // check if group_id exists
        $rn = $wpdb->query( $wpdb->prepare( "SELECT * FROM `{$wpdb->prefix}sticker_group` WHERE group_id=%s;", $group_id ) );
        if ( $rn === 0 || $rn === false ) {
            return false;
        }

        $dir = WP_CONTENT_DIR . "/" . "stickers" . "/" . $group_id;
        if ( file_exists( $dir ) ){
            self::rm_all_dir( $dir );
        }

        $wpdb->query( $wpdb->prepare( "DELETE FROM `{$wpdb->prefix}stickers` WHERE group_id=%s;", $group_id ) );
        $wpdb->query( $wpdb->prepare( "DELETE FROM `{$wpdb->prefix}sticker_group` WHERE group_id=%s;", $group_id ) );

        return true;

    }

}
?>