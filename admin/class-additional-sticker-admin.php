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
	private $notice_type = array(
		array('type' => 'error', 'dismissible' => true, array('additional_classes' => 'notice-alt')),
		array('type' => 'success', 'dismissible' => true, array('additional_classes' => 'notice-alt')),
		array('type' => 'warning', 'dismissible' => true, array('additional_classes' => 'notice-alt')),
		array('type' => 'info', 'dismissible' => true, array('additional_classes' => 'notice-alt'))
	);

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
		wp_enqueue_script( 'jquery-ui-touch-punch', plugin_dir_url( __FILE__ ) . 'js/lib/jquery.ui.touch-punch.js', array( 'jquery-ui-core', 'jquery-ui-mouse', 'jquery-ui-sortable' ), '0.2.3', false );
		wp_enqueue_script( $this->additional_sticker, plugin_dir_url( __FILE__ ) . 'js/additional-sticker-admin.js', array( 'jquery' ), $this->version, false  );

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
		echo '<h1 class="wp-heading-inline">Additional sticker</h1>';


		if ( isset( $_GET['action'] ) ) {
			require_once plugin_dir_path( __FILE__ ) . "../includes/". "class-additional-sticker-functions.php";
			switch ( $_GET['action'] ) {

				case 'delete':
					if ( isset($_GET['group_id']) ) {
						if ( Additional_sticker_functions::remove_sticker( $_GET['group_id'] ) ) {
							wp_admin_notice( '删除表情成功', $this->notice_type[1] );
						} else {
							wp_admin_notice( '删除表情失败', $this->notice_type[0] );
						}
					}

					echo '<script>history.pushState(null, null, "admin.php?page=additional-sticker");</script>';

					break;


				case 'upload':
					if ( isset( $_FILES['sticker_file'] ) && $_FILES['sticker_file']['error'] === UPLOAD_ERR_OK ) {
						$tmp_dir = get_temp_dir();
						$zip = new ZipArchive();
						$zip_dirname = wp_generate_password( 12, false );
						$upload_file = $tmp_dir . basename( $_FILES['sticker_file']['name'] );
						if (move_uploaded_file( $_FILES['sticker_file']['tmp_name'], $upload_file) ) {
							if ( $zip->open( $upload_file ) === TRUE ) {
								$zip->extractTo( $tmp_dir . $zip_dirname );
								$zip->close();
								$result = Additional_sticker_functions::add_stiker( $tmp_dir . $zip_dirname );
								if ($result[0]) {
									wp_admin_notice( '上传表情成功', $this->notice_type[1] );
								} else {
									if ( $result[1] === 2 ) {
										wp_admin_notice( '表情已存在', $this->notice_type[2] );
									}else{
										if( wp_get_environment_type() === 'development' ) {
											wp_admin_notice( '上传表情失败: ' . $result[2], $this->notice_type[0] );
										} else {
											wp_admin_notice( '上传表情失败', $this->notice_type[0] );
										}
									}
								}
									
							} else {
								wp_admin_notice( '解压缩文件失败', $this->notice_type[0] );
							}

							Additional_sticker_functions::rm_all_dir( $tmp_dir . $zip_dirname );
							unlink( $upload_file );

						} else {
							wp_admin_notice( '上传文件失败', $this->notice_type[0] );
						}
					} else {
						wp_admin_notice( '没有选择文件或文件上传错误', $this->notice_type[0] );
					}

					echo '<script>history.pushState(null, null, "admin.php?page=additional-sticker");</script>';

					break;
				

				case 'update':
					if ( isset( $_POST['data'] ) && !empty( $_POST['data'] ) ) {

						global $wpdb;
						$data = json_decode( stripslashes( $_POST['data'] ), true );

						if ( is_array( $data ) && count( $data ) > 0 ) {
							foreach ( $data as $i => $group ) {
								$enabled = $group['checked'] ? 1 : 0;
								$wpdb->update(
									"{$wpdb->prefix}sticker_group",
									array( 'enabled' => $enabled, 
											'sort' => $i ),
									array( 'group_id' => $group['id'] )
								);
							}
							wp_admin_notice( '更新成功', $this->notice_type[1] );
						} else {
							wp_admin_notice( '数据错误', $this->notice_type[0] );
						}
					} else {
						wp_admin_notice( '无更改', $this->notice_type[2] );
					}
					
					echo '<script>history.pushState(null, null, "admin.php?page=additional-sticker");</script>';

					break;

				default:
					wp_admin_notice( '( O_o) ?', $this->notice_type[2] );
					break;
			}
			
		}




		

		echo '<h2>已安装表情列表</h2>';
		global $wpdb;
		$stickers = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}sticker_group` ORDER BY `sort`;" );

		if( count( $stickers ) === 0 ): ?>
			<p>暂无表情</p>
		<?php else: ?>
			<p><?php printf( __( 'Number of installed stickers: %d', 'additional-sticker' ), count( $stickers ) ); ?></p>
			<p>拖动表情组来调整表情组的顺序</p>
			<div class="margin-fix">
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th scope="col" class="manage-column column-cb check-column">
							<input type="checkbox" id="group-select-all">
							<label for="group-select-all" class="screen-reader-text">全选</label>
						</th>
						<th scope="col" class="manage-column column-primary column-title">名称</th>
						<th scope="col" class="manage-column">表情组ID</th>
						<th scope="col" class="manage-column">描述</th>
						<th scope="col" class="manage-column">作者</th>
						<th scope="col" class="manage-column">链接</th>
					</tr>
				</thead>
				<tbody id="sticker-list">

			
			<?php foreach ( $stickers as $sticker ): ?>
				<tr id="<?php echo esc_html( $sticker->group_id ); ?>">
					<th scope="row" class="check-column">
						<input type="checkbox" class="sticker-checkbox" id="group-checkbox-<?php echo esc_html( $sticker->group_id ); ?>" onchange="updateListData();" <?php echo $sticker->enabled == 1 ? 'checked':''  ?>>
						<label for="group-checkbox-<?php echo esc_html( $sticker->group_id ); ?>" class="screen-reader-text">选择表情</label>
					</th>
					<td scope="row" class="column-primary" data-colname="名称">
						<div style="display: flex; align-items: center;">
							<img src="<?php echo esc_url( WP_CONTENT_URL . '/stickers/' . $sticker->group_id . '/' . $sticker->icon ); ?>" alt="<?php echo esc_attr( $sticker->name ); ?>" style="width: 50px; height: 50px; margin-right: 10px;">
							<?php echo esc_html( $sticker->name ); ?>
						</div>
						
						<div class="row-actions">
							<span class="delete">
								<a href="<?php echo esc_url(admin_url('admin.php?page=additional-sticker&action=delete&group_id=' . $sticker->group_id)); ?>" class="delete-sticker" data-group-id="<?php echo esc_html($sticker->group_id); ?>">删除</a>
							</span>
						</div>
						<button type="button" class="toggle-row"></button>
					</td>
					<td scope="row" data-colname="表情组ID"><?php echo esc_html( $sticker->group_id ); ?></td>
					<td scope="row" data-colname="描述"><?php echo esc_html( $sticker->description ); ?></td>
					<td scope="row" data-colname="作者"><?php echo esc_html( $sticker->author ); ?></td>
					<td scope="row" data-colname="链接"><a href="<?php echo esc_url( $sticker->url ); ?>" target="_blank"><?php echo esc_html( $sticker->url ); ?></a></td>
				</tr>
			<?php endforeach; ?>

			</tbody>
			</table>

			<form action="<?php echo esc_url(admin_url( 'admin.php?page=additional-sticker&action=update' )); ?>" method="post" id="bulk-update-form">
				<input type="text" name="data" id="bulk-data">
				<?php submit_button(); ?>
			</form>
			</div>

			<?php endif; ?>


			
			<h2>上传表情文件</h2>
			<p>请上传后缀为spck的表情文件</p>
			<form method="post" action="<?php echo esc_url(admin_url( 'admin.php?page=additional-sticker&action=upload' )); ?>" enctype="multipart/form-data">
				<input type="hidden" name="action" value="upload">
				<input type="file" name="sticker_file" accept=".spck" required>
				<input type="submit" class="button button-primary" value="上传表情">
			</form>
			<?php
		

	}
	
}

