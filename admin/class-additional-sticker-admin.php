<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.asanatsa.cc/project/additional-sticker
 * @since      1.1.1
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
 * @author     Asanatsa <me@asanatsa.cc>
 */
class Additional_Sticker_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1.1
	 * @access   private
	 * @var      string    $additional_sticker    The ID of this plugin.
	 */
	private $additional_sticker;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.1.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * sticker url
	 *
	 * @since 1.1.1
	 * @var string
	 * @access private
	 */
	private $notice_type = array(array('type' => 'error', 'dismissible' => true, array('additional_classes' => 'notice-alt')),
		array('type' => 'success', 'dismissible' => true, array('additional_classes' => 'notice-alt')),
		array('type' => 'warning', 'dismissible' => true, array('additional_classes' => 'notice-alt')),
		array('type' => 'info', 'dismissible' => true, array('additional_classes' => 'notice-alt')));

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.1.1
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
	 * @since    1.1.1
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
	 * @since    1.1.1
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
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-sortable' , '', array( 'jquery' ));
		wp_enqueue_script('jquery-ui-touch-punch', plugin_dir_url( __FILE__ ) . 'js/lib/jquery.ui.touch-punch.js', array('jquery-ui-core', 'jquery-ui-mouse', 'jquery-ui-sortable'), '0.2.3', false);
		wp_enqueue_script( $this->additional_sticker, plugin_dir_url( __FILE__ ) . 'js/additional-sticker-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function additional_sticker_menu_init() {
		add_menu_page(
			__( 'Additional Sticker', 'additional-sticker' ),
			__( 'Additional Sticker', 'additional-sticker' ),
			'manage_options',
			"additional-sticker",
			array( $this, 'render_additional_sticker_page' ),
			'dashicons-format-image',
			6
		);
	}


	public function render_additional_sticker_page() {
		echo '<h1>Additional sticker</h1>';


		echo get_temp_dir();
		
		// test 
		if (isset($_GET['action'])){
			include plugin_dir_path( __FILE__ ) . "../includes/". "class-additional-sticker-functions.php";
			switch ($_GET['action']) {

				case 'delete':
					if (isset($_GET['group_id'])) {
						if (Additional_sticker_functions::remove_sticker($_GET['group_id'])) {
							wp_admin_notice('删除表情成功', $this->notice_type[1]);
						} else {
							wp_admin_notice('删除表情失败', $this->notice_type[0]);
						}
					}

					echo '<script>history.pushState(null, null, "admin.php?page=additional-sticker");</script>';
					
					break;


				case 'upload':
					if (isset($_FILES['sticker_file']) && $_FILES['sticker_file']['error'] === UPLOAD_ERR_OK) {
						$tmp_dir = get_temp_dir();
						$zip = new ZipArchive();
						$zip_dirname = wp_generate_password(12, false);
						$upload_file = $tmp_dir . basename($_FILES['sticker_file']['name']);
						if (move_uploaded_file($_FILES['sticker_file']['tmp_name'], $upload_file)) {
							if ($zip->open($upload_file) === TRUE) {
								$zip->extractTo($tmp_dir . $zip_dirname);
								$zip->close();
								$result = Additional_sticker_functions::add_stiker($tmp_dir . $zip_dirname);
								if ($result[0]) {
									wp_admin_notice('上传表情成功', $this->notice_type[1]);
								} else {
									if ($result[1] === 2){
										wp_admin_notice('表情已存在', $this->notice_type[2]);
									}else{
										wp_admin_notice('上传表情失败', $this->notice_type[0]);
									}
								}
									
							} else {
								wp_admin_notice('解压缩文件失败', $this->notice_type[0]);
							}

							Additional_sticker_functions::rm_all_dir($tmp_dir . $zip_dirname);
							unlink($upload_file);

						} else {
							wp_admin_notice('上传文件失败', $this->notice_type[0]);
						}
					} else {
						wp_admin_notice('没有选择文件或文件上传错误', $this->notice_type[0]);
					}

					echo '<script>history.pushState(null, null, "admin.php?page=additional-sticker");</script>';

					break;
				

				default:
					wp_admin_notice('( O_o) ?', $this->notice_type[2]);
					break;
			}
			
		}




		

		echo '<h2>已安装表情列表</h2>';
		global $wpdb;
		$stickers = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}sticker_group`;");

		if( count($stickers) === 0) {
			echo '<p>暂无表情</p>';
		} else {

			echo '<table class="wp-list-table widefat fixed striped" >';
			echo '<thead><tr>';
			echo '<th scope="col" class="manage-column column-cb check-column"><input type="checkbox" id="group-select-all">';
			echo '<label for="group-select-all" class="screen-reader-text">全选</th>';

			echo '<th scope="col" class="manage-column column-primary column-title">名称</th>';
			echo '<th scope="col" class="manage-column">表情组ID</th>';
			echo '<th scope="col" class="manage-column">描述</th>';
			echo '<th scope="col" class="manage-column">作者</th>';
			echo '<th scope="col" class="manage-column">链接</th>';
			//echo '<th>操作</th>';
			echo '</tr></thead>';

			echo '<tbody id="sticker-list">';

			foreach ($stickers as $sticker) {
				
				echo '<tr id="' . esc_html($sticker->group_id) . '">';

				// checkbox
				echo '<th scope="row" class="check-column"><input type="checkbox" class="sticker-checkbox" id="group-checkbox-' . esc_html($sticker->group_id) . '">';
				echo '<label for="group-checkbox-' . esc_html($sticker->group_id) . '" class="screen-reader-text">选择表情</label>';
				echo '</th>';

				// name
				echo '<td scope="row" class="column-primary" data-colname="名称">';
				echo '<img src="' . esc_url(WP_CONTENT_URL . '/stickers/' . $sticker->group_id . '/' . $sticker->icon) . '" alt="' . esc_attr($sticker->name) . '" style="width: 50px; height: 50px;">';
				echo esc_html($sticker->name); 
				echo '<div class="row-actions">';
				echo '<span class="delete"><a href="' . esc_url(admin_url('admin.php?page=additional-sticker&action=delete&group_id=' . $sticker->group_id)) . '" class="delete-sticker" data-group-id="' . esc_html($sticker->group_id) . '">删除</a></span>';
				echo '</div>';
				// wordpress toggle button
				echo '<button type="button" class="toggle-row"></button>';
				echo '</td>';

				// group_id
				echo '<td scope="row" data-colname="表情组ID">' . esc_html($sticker->group_id) . '</td>';
				
				
				echo '<td scope="row" data-colname="描述">' . esc_html($sticker->description) . '</td>';
				echo '<td scope="row" data-colname="作者">' . esc_html($sticker->author) . '</td>';
				echo '<td scope="row" data-colname="链接"><a href="' . esc_url($sticker->url) . '" target="_blank">' . esc_html($sticker->url) . '</a></td>';
				//echo '<td scope="row"><a href="#" class="button remove-sticker" data-group-id="' . esc_html($sticker->group_id) . '">删除</a></td>';
				echo '</tr>';
			}

			echo '</tbody>';
			echo '</table>';
		}
			// echo '<ul id="sticker-list">';
			// foreach ($stickers as $sticker) {
			// 	echo '<li class="sticker-item">';
			// 	echo '<img src="' . esc_url(WP_CONTENT_DIR . '/stickers/' . $sticker->group_id . '/' . $sticker->icon) . '" alt="' . esc_attr($sticker->name) . '">';
			// 	echo '<span>' . esc_html($sticker->name) . '</span>';
			// 	echo '</li>';
			// }
			// echo '</ul>';
			// 
			echo '<h2>上传表情文件</h2>';
			echo '<p>请上传后缀为spck的表情文件</p>';
			echo '<form method="post" action="' . esc_url(admin_url('admin.php?page=additional-sticker&action=upload')) . '" enctype="multipart/form-data">';
			echo '<input type="hidden" name="action" value="upload">';
			echo '<input type="file" name="sticker_file" accept=".spck" required>';
			echo '<input type="submit" class="button button-primary" value="上传表情">';
			echo '</form>';
		

	}
	
}

