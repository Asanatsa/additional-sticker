<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Additional_Sticker
 * @subpackage Additional_Sticker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Additional_Sticker
 * @subpackage Additional_Sticker/public
 * @author     Your Name <email@example.com>
 */
class Additional_Sticker_Public
{

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
	 * @param      string    $additional_sticker       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($additional_sticker, $version)
	{

		$this->additional_sticker = $additional_sticker;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

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

		wp_enqueue_style($this->additional_sticker, plugin_dir_url(__FILE__) . 'css/additional-sticker-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

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

		wp_enqueue_script($this->additional_sticker, plugin_dir_url(__FILE__) . 'js/additional-sticker-public.js', null, $this->version, true);
	}


	
	/**
	 * for insert sticker panel 
	 *
	 * @param [Array] $defaults
	 * @return defaults
	 */
	public function insert_panel($defaults)
	{
		global $wpdb;
		$db_prefix = $wpdb->prefix;
		$sticker_dir = WP_CONTENT_URL . "/stickers";

		$rad_html = "";
		$sticker_list_html = "";

		$sticker_groups = $wpdb->get_results("SELECT * FROM `{$db_prefix}sticker_group`;");


		//莫得内容就返回“noting”
		if (count($sticker_groups) === 0) {
			$defaults['comment_field'] .= '<div class="sticker-panel"><img src="./img/shabulaji/foxing.png" height="30" width="30"><div class="sticker-popmenu"><div class="sticker-rad-list"></div><div class="sticker-list"><div style="width: 100%;margin-top: 30px;text-align: center;color: gray;">Nothing.</div></div></div></div>';
			return $defaults;
		}




		foreach ($sticker_groups as $a => $single_group) {

			$is_hidden = "";
			$is_checked = "";

			if ($a === 0) {
				$is_checked = "checked";
			} else {
				$is_hidden = "hidden";
			}

			$rad_html .= "<input type='radio' class='sticker-rad' id='{$single_group->group_id}' name='sticker-select' onclick='selectStickers()' {$is_checked}><label for='{$single_group->group_id}'><img class='sticker-logo-thumb' loading='lazy' title='{$single_group->name}' src='{$sticker_dir}/{$single_group->group_id}/{$single_group->icon}'></label>";



			$stickers = $wpdb->get_results("SELECT * FROM `{$db_prefix}stickers` WHERE group_id='{$single_group->group_id}';");
			$g_html = "<div id='sticker-{$single_group->group_id}' data-id='{$single_group->group_id}' {$is_hidden}>";

			if (count($stickers) !== 0) {
				foreach ($stickers as $b => $s) {
					$g_html .= "<img class='sticker-img' title='{$s->name}' data-name='{$s->id}' onclick='clickSticker(event)' loading='lazy' src='{$sticker_dir}/{$s->group_id}/{$s->src}'>";
					if ($b + 1 === count($stickers)) {
						$g_html .= "<br><span class='sticker-copyright'>{$single_group->description}</span>";
					}
				}
			} else {
				$g_html .= '<div style="width: 100%;margin-top: 30px;text-align: center;color: gray;">Nothing.</div>';
			}

			$g_html .= '</div>';
			$sticker_list_html .= $g_html;
		}


		$out_html = "<div class='sticker-panel'><img src='{$sticker_dir}/sblj/foxing.png' height='30' width='30'><div class='sticker-popmenu'>
		<div class='sticker-rad-list'>{$rad_html}</div>
		<div class='sticker-list'>{$sticker_list_html}</div>
		</div></div>";

		//var_dump($test);
		//var_dump($rad_html);
		//var_dump($sticker_list_html);
		$defaults['comment_field'] .= $out_html;
		return $defaults;
	}



	public function insert_stickers($comment_text)
	{
		global $wpdb;
		$db_prefix = $wpdb->prefix;
		$sticker_dir = WP_CONTENT_URL . "/stickers";
		$prg_text = $comment_text;

		while (true) {
			if (!preg_match('/\{(\w{1,10})#(\w{1,10})\}/i', $prg_text, $match_text_array)) {
				break; //break loop when dosen't match any stickertext
			}

			$sticker_data = $wpdb->get_results("SELECT * FROM `{$db_prefix}stickers` WHERE group_id='{$match_text_array[1]}' AND id='{$match_text_array[2]}';");

			if (count($sticker_data) === 0) {
				$prg_text = str_replace($match_text_array[0], ' [unknow] ', $prg_text);  //replace when doesn't match any record
				continue;
			}

			$sticker_img_html = "<img src='{$sticker_dir}/{$sticker_data[0]->group_id}/{$sticker_data[0]->src}' title='{$sticker_data[0]->name}' class='text-sticker-img' load='lazy'>";
			$prg_text = str_replace($match_text_array[0], $sticker_img_html, $prg_text);
		}

		return $prg_text;
	}
}
