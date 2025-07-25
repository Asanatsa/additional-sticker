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
			'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjxzdmcKICAgZmlsbD0iIzAwMDAwMCIKICAgd2lkdGg9IjgwMHB4IgogICBoZWlnaHQ9IjgwMHB4IgogICB2aWV3Qm94PSIwIDAgMzIgMzIiCiAgIHZlcnNpb249IjEuMSIKICAgaWQ9InN2ZzEiCiAgIHhtbG5zOmlua3NjYXBlPSJodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy9uYW1lc3BhY2VzL2lua3NjYXBlIgogICB4bWxuczpzb2RpcG9kaT0iaHR0cDovL3NvZGlwb2RpLnNvdXJjZWZvcmdlLm5ldC9EVEQvc29kaXBvZGktMC5kdGQiCiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIKICAgeG1sbnM6c3ZnPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CiAgPGRlZnMKICAgICBpZD0iZGVmczEiIC8+CiAgPHNvZGlwb2RpOm5hbWVkdmlldwogICAgIGlkPSJuYW1lZHZpZXcxIgogICAgIHBhZ2Vjb2xvcj0iI2ZmZmZmZiIKICAgICBib3JkZXJjb2xvcj0iIzAwMDAwMCIKICAgICBib3JkZXJvcGFjaXR5PSIwLjI1IgogICAgIGlua3NjYXBlOnNob3dwYWdlc2hhZG93PSIyIgogICAgIGlua3NjYXBlOnBhZ2VvcGFjaXR5PSIwLjAiCiAgICAgaW5rc2NhcGU6cGFnZWNoZWNrZXJib2FyZD0iMCIKICAgICBpbmtzY2FwZTpkZXNrY29sb3I9IiNkMWQxZDEiCiAgICAgaW5rc2NhcGU6em9vbT0iMC45MjYyNSIKICAgICBpbmtzY2FwZTpjeD0iMzk5LjQ2MDE5IgogICAgIGlua3NjYXBlOmN5PSIzOTguOTIwMzgiCiAgICAgaW5rc2NhcGU6d2luZG93LXdpZHRoPSIxNTYzIgogICAgIGlua3NjYXBlOndpbmRvdy1oZWlnaHQ9Ijk3OSIKICAgICBpbmtzY2FwZTp3aW5kb3cteD0iLTgiCiAgICAgaW5rc2NhcGU6d2luZG93LXk9Ii04IgogICAgIGlua3NjYXBlOndpbmRvdy1tYXhpbWl6ZWQ9IjEiCiAgICAgaW5rc2NhcGU6Y3VycmVudC1sYXllcj0ic3ZnMSIgLz4KICA8dGl0bGUKICAgICBpZD0idGl0bGUxIj53aW5rPC90aXRsZT4KICA8cGF0aAogICAgIGQ9Im0gMTYuMTg0NzA3LDUuMTM3MTg0OSBjIDYuMTg4LDAgMTEuMjE5LDQuODQ0IDExLjIxOSwxMC45MDYwMDAxIDAsNi4wNjIgLTUuMDMxLDEwLjkwNiAtMTEuMjE5LDEwLjkwNiAtNi4xODgsMCAtMTEuMTg3OTk5OCwtNC44NDQgLTExLjE4Nzk5OTgsLTEwLjkwNiAwLC02LjA2MjAwMDEgNC45OTk5OTk4LC0xMC45MDYwMDAxIDExLjE4Nzk5OTgsLTEwLjkwNjAwMDEgeiBtIDcuMTU2LDEwLjM3NTAwMDEgMC4yNSwtMC42NTYgYyAwLjA5NCwtMC4yNSAtMC4wMzEsLTAuNTMxIC0wLjI4MSwtMC42MjUgbCAtMy42NTYsLTEuMzEzIDMuMzc1LC0xLjkzOCBjIDAuMjE5LC0wLjEyNSAwLjI4MSwtMC40MDYgMC4xNTYsLTAuNjI1IGwgLTAuMzc1LC0wLjYyNTAwMDEgYyAtMC4xMjUsLTAuMjE5IC0wLjQwNiwtMC4yODEgLTAuNTk0LC0wLjE1NiBsIC01LjAzMSwyLjg3NTAwMDEgYyAtMC4yMTksMC4xMjUgLTAuMjUsMC4zMTMgLTAuMjE5LDAuNzE5IDAuMDMxLDAuNDA2IDAuMDk0LDAuNTYzIDAuMzEzLDAuNjU2IGwgNS40NjksMS45NjkgYyAwLjIxOSwwLjA5NCAwLjUsLTAuMDYzIDAuNTk0LC0wLjI4MSB6IG0gLTEwLjI4MSwtNS4wMzEgaCAtMS4yODEgYyAtMC4zNDQsMCAtMC41OTQsMC4yODEgLTAuNTk0LDAuNjI1IHYgMy4xMjUgYyAwLDAuMzQ0IDAuMjUsMC42NTYgMC41OTQsMC42NTYgaCAxLjI4MSBjIDAuMzEzLDAgMC42MjUsLTAuMzEzIDAuNjI1LC0wLjY1NiB2IC0zLjEyNSBjIDAsLTAuMzQ0IC0wLjMxMywtMC42MjUgLTAuNjI1LC0wLjYyNSB6IG0gLTAuOTM4LDcuNTYyIC0xLjIxOSwwLjQwNiBjIC0wLjEyNSwwLjAzMSAtMC4yMTksMC4xMjUgLTAuMjUsMC4yMTkgLTAuMDYzLDAuMDk0IC0wLjA2MywwLjIxOSAtMC4wMzEsMC4zNDQgMC4wMzIsMC4xMjUgMS4xMjUsMy4yODEgNS41NjMsMy4yNSA0LjQzOCwwLjAzMSA1LjU2MywtMy4xMjUgNS41OTQsLTMuMjUgMC4wMzEsLTAuMTI1IDAuMDMxLC0wLjI1IC0wLjAzMSwtMC4zNDQgLTAuMDMxLC0wLjA5NCAtMC4xNTYsLTAuMTg4IC0wLjI4MSwtMC4yMTkgbCAtMS4wNjMsLTAuMzc1IC0wLjE1NiwtMC4wMzEgYyAtMC4xODgsMCAtMC4zNDQsMC4xNTYgLTAuNDA2LDAuMzEzIDAsMC4wMzEgLTAuNjg4LDEuOTA2IC0zLjY1NiwxLjkwNiAtMi45MzgsMCAtMy41OTQsLTEuODEzIC0zLjYyNSwtMS45MDYgLTAuMDYzLC0wLjE1NiAtMC4yNSwtMC4zMTMgLTAuNDM4LC0wLjMxMyB6IgogICAgIGlkPSJwYXRoMSIKICAgICBzdHlsZT0iZmlsbDojYjNiM2IzIiAvPgo8L3N2Zz4K'
		);
	}


	public function render_additional_sticker_page() {

		echo '<div class="wrap">';
		echo '<h1 class="wp-heading-inline">Additional sticker</h1>';


		if ( isset( $_GET['action'] ) ) {
			require_once plugin_dir_path( __FILE__ ) . "../includes/". "class-additional-sticker-functions.php";
			switch ( $_GET['action'] ) {

				case 'delete':
					if ( isset($_GET['group_id']) ) {
						if ( Additional_sticker_functions::remove_sticker( $_GET['group_id'] ) ) {
							wp_admin_notice( __( 'Deleted successfully', 'additional-sticker' ), $this->notice_type[1] );
						} else {
							wp_admin_notice( __( 'Delete failed', 'additional-sticker' ), $this->notice_type[0] );
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
									wp_admin_notice( __( 'Uploaded successfully','additional-sticker' ), $this->notice_type[1] );
								} else {
									if ( $result[1] === 2 ) {
										wp_admin_notice( __( 'Sticker already exists', 'additional-sticker' ), $this->notice_type[2] );
									}else{
										if( wp_get_environment_type() === 'development' ) {
											wp_admin_notice( '上传表情失败: ' . $result[2], $this->notice_type[0] );
										} else {
											wp_admin_notice( __( 'Upload failed', 'additional-sticker' ), $this->notice_type[0] );
										}
									}
								}
									
							} else {
								wp_admin_notice( __( 'Unzip failed', 'additional-sticker' ), $this->notice_type[0] );
							}

							Additional_sticker_functions::rm_all_dir( $tmp_dir . $zip_dirname );
							unlink( $upload_file );

						} else {
							wp_admin_notice( __( 'Upload failed', 'additional-sticker' ), $this->notice_type[0] );
						}
					} else {
						wp_admin_notice( __( 'Error during file upload', 'additional-sticker' ), $this->notice_type[0] );
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
							wp_admin_notice( __( 'Updated successfully', 'additional-sticker' ), $this->notice_type[1] );
						} else {
							wp_admin_notice( __( 'Invalid format', 'additional-sticker' ), $this->notice_type[0] );
						}
					} else {
						wp_admin_notice( __( 'No changes', 'additional-sticker' ), $this->notice_type[2] );
					}
					
					echo '<script>history.pushState(null, null, "admin.php?page=additional-sticker");</script>';

					break;

				default:
					wp_admin_notice( '( O_o) ?', $this->notice_type[2] );
					break;
			}
			
		}




		

		echo '<h2>' . __('Installed Stickers', 'additional-sticker') . '</h2>';
		global $wpdb;
		$stickers = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}sticker_group` ORDER BY `sort`;" );

		if( count( $stickers ) === 0 ): ?>
			<p> <?php _e( 'No results', 'additional-sticker' ); ?></p>
		<?php else: ?>
			<p><?php printf( __( 'Number of installed stickers: %d', 'additional-sticker' ), count( $stickers ) ); ?></p>
			<p><?php _e( 'Drag items in the list to rearrange the order of sticker groups.', 'additional-sticker' ); ?></p>
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th scope="col" class="manage-column column-cb check-column">
							<input type="checkbox" id="group-select-all">
							<label for="group-select-all" class="screen-reader-text"><?php _e( 'Select all', 'additional-sticker' ); ?></label>
						</th>
						<th scope="col" class="manage-column column-primary column-title"><?php _e( 'Name', 'additional-sticker' ); ?></th>
						<th scope="col" class="manage-column"><?php _e( 'Group ID', 'additional-sticker' ); ?></th>
						<th scope="col" class="manage-column"><?php _e( 'Descripion', 'additional-sticker' ); ?></th>
						<th scope="col" class="manage-column"><?php _e( 'Author', 'additional-sticker' ); ?></th>
						<th scope="col" class="manage-column"><?php _e( 'Link', 'additional-sticker' ); ?></th>
					</tr>
				</thead>
				<tbody id="sticker-list">

			
			<?php foreach ( $stickers as $sticker ): ?>
				<tr id="<?php echo esc_html( $sticker->group_id ); ?>">
					<th scope="row" class="check-column">
						<input type="checkbox" class="sticker-checkbox" id="group-checkbox-<?php echo esc_html( $sticker->group_id ); ?>" onchange="updateListData();" <?php echo $sticker->enabled == 1 ? 'checked':''  ?>>
						<label for="group-checkbox-<?php echo esc_html( $sticker->group_id ); ?>" class="screen-reader-text">选择表情</label>
					</th>
					<td scope="row" class="column-primary" data-colname="<?php _e( 'Name', 'additional-sticker' ); ?>">
						<div style="display: flex; align-items: center;">
							<img src="<?php echo esc_url( WP_CONTENT_URL . '/stickers/' . $sticker->group_id . '/' . $sticker->icon ); ?>" alt="<?php echo esc_attr( $sticker->name ); ?>" style="width: 50px; height: 50px; margin-right: 10px;">
							<?php echo esc_html( $sticker->name ); ?>
						</div>
						
						<div class="row-actions">
							<span class="delete">
								<a href="<?php echo esc_url(admin_url('admin.php?page=additional-sticker&action=delete&group_id=' . $sticker->group_id)); ?>" class="delete-sticker" data-group-id="<?php echo esc_html($sticker->group_id); ?>"><?php _e( 'Delete', 'additional-sticker' ); ?></a>
							</span>
						</div>
						<button type="button" class="toggle-row"></button>
					</td>
					<td scope="row" data-colname="<?php _e( 'Group ID', 'additional-sticker' ); ?>"><?php echo esc_html( $sticker->group_id ); ?></td>
					<td scope="row" data-colname="<?php _e( 'Description', 'additional-sticker' ); ?>"><?php echo esc_html( $sticker->description ); ?></td>
					<td scope="row" data-colname="<?php _e( 'Author', 'additional-sticker' ); ?>"><?php echo esc_html( $sticker->author ); ?></td>
					<td scope="row" data-colname="<?php _e( 'Link', 'additional-sticker' ); ?>"><a href="<?php echo esc_url( $sticker->url ); ?>" target="_blank"><?php echo esc_html( $sticker->url ); ?></a></td>
				</tr>
			<?php endforeach; ?>

			</tbody>
			</table>

			<form action="<?php echo esc_url(admin_url( 'admin.php?page=additional-sticker&action=update' )); ?>" method="post" id="bulk-update-form">
				<input type="text" name="data" id="bulk-data" hidden="true">
				<?php submit_button(); ?>
			</form>

			<?php endif; ?>


			
			<h2><?php _e( 'Upload Sticker', 'additional-sticker' ); ?></h2>
			<p><?php _e( 'Please select the file with extension <code>.spck</code>', 'additional-sticker' ); ?></p>
			<form method="post" action="<?php echo esc_url(admin_url( 'admin.php?page=additional-sticker&action=upload' )); ?>" enctype="multipart/form-data">
				<input type="hidden" name="action" value="upload">
				<input type="file" name="sticker_file" accept=".spck" required>
				<input type="submit" class="button button-primary" value="<?php _e( 'Upload', 'additional-sticker' ); ?>">
			</form>

			</div>
			<?php
		

	}
	
}

