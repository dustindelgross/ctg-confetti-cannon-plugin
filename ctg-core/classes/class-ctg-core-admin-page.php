<?php


class CTG_Core_Admin_Page {

	// class instance
	static $instance;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
	}

	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function plugin_menu() {

		$hook = add_menu_page(
			'CTG Confetti Cannon Settings',
			'CTG Confetti Cannon',
			'manage_options',
			'ctg-settings',
			[ $this, 'plugin_settings_page' ],
			"data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz48c3ZnIGlkPSJiIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0MDAgNDAwIj48ZyBpZD0iYyI+PHBhdGggZmlsbD0iIzJhMmEyYSIgZD0ibTIwMCwwQzg5LjU0LDAsMCw4OS41NCwwLDIwMHM4OS41NCwyMDAsMjAwLDIwMCwyMDAtODkuNTQsMjAwLTIwMFMzMTAuNDYsMCwyMDAsMFptMzcuMTUsMTcyLjIxYy0xOC4xNiwxNy4yNi01OS44Myw2My4yNS03My44Miw4MS41NS0yLjM4LDMuMTMtNS42NiwyLjM4LTMuMjctMS4zNCwxNy4xMS0yNS44OSwzMy4xOS01My4yOCw2Ny44Ni04OS41OSwxMC43Mi0xMS4xNiwxNi4yMiwyLjY4LDkuMjMsOS4zOFptLTExMi42NiwxMC4yN2M2LjEtNS4wNiwzLjI3LDMwLjM2LDIuNjgsMzEuNC0uNiwxLjE5LTguNDgtMjYuNjQtMi42OC0zMS40Wm0tMS42NCw5MC4zM2MtMTMuODQtMjEuNDMtNDAuNjMtNDIuNzEtNDQuMDUtNDkuODYtMy4yNy02LjctLjE1LTkuMDgsMi4zOC05LjIzLDIuMjMtLjE1LDQuNjEsMS4xOSw2Ljg1LDMuNTcsMTguNiwxOS45NCwzMC4wNiw0Mi4xMiwzNS43Miw1NC40Ny42LDEuMTktLjE1LDIuMDgtLjg5LDEuMDRabTcuMjktMy4xM2MtMTEuOTEtMzAuNTEtMzguNC03Ny41NC0zOC4yNS03Ny41NC03LjE0LTE3LjExLDYuODUtMTMuNTQsMTAuNTctOC43OCwxMS4wMSwxNC40NCwyNS4xNSw2Mi44LDI5LjQ3LDg1Ljg3LjE1LDEuMDQtMS4zNCwxLjQ5LTEuNzkuNDVabTguOTMtMTAuNDJjLTMuNDItMjUuMTUtOS4yMy05Ni4yOS0yLjM4LTEyNC43MSw0Ljc2LTE5LjM1LDE0LjE0LTUuMjEsMTMuOTksNC42MS0uMywyNi42NC03LjI5LDk3LjMzLTkuNjcsMTIwLjEtLjE1LDEuMDQtMS43OSwxLjE5LTEuOTMsMFptOC42My0xLjc5YzE2Ljk3LTg0LjY4LDQzLjMxLTEyMy41Miw1MS4wNS0xMzYuNzcsNi41NS0xMS40NiwxMi45NS0zLjU3LDEzLjU0LDIuNjguNiw1LjgtLjg5LDExLjc2LTMuNzIsMTYuOTctMjkuMzIsNTQuNDctNDkuODYsOTQuMDYtNTcuODksMTE5LjM2LTEuMDQsMi44My0zLjU3Ljc0LTIuOTgtMi4yM1ptODUuMTMsMjEuMjhjLTE0LjczLTMuNTctMjkuMTctMy41Ny00NC4zNS0zLjQyLTEzLjEuMTUtMjMuNjYsMy44Ny0yNC4xMSwzLjg3LTIuMDguNDUtMy40Mi0uODktMS4zNC0xLjkzLDcuNzQtMy41NywxNi45Ny02LjI1LDI2LjA0LTcuODksMTAuNDItMS43OSwyMC45OC0yLjk4LDMxLjU1LTMuMjcsMTAuNTctLjMsMjAuOTguMTUsMzEuNCwxLjM0LDcuNzQuODksMTkuNjQsMS4xOSwyMy4yMiw5LjY3LjE1LjQ1LjMuODkuNDUsMS4zNCwyLjUzLDEzLjk5LTM3LjA2LDEuNzktNDIuODYuM1ptODkuNTktOTUuMzljLTEuMTksNi40LTUuOTUsMTEuNjEtMTIuMDUsMTQuMTQtODQuNjgsMzQuODItMTI4LjI4LDYxLjAyLTE0OC42Nyw3NS4zLTIuMzgsMS42NC01LjIxLTEuNDktMy4xMy0zLjcyLDQ2LjczLTUxLjQ5LDE1MC43Ni05My42MSwxNTAuNzYtOTMuNjEsMTEuOTEtMi4yMywxMy45OSwyLjUzLDEzLjEsNy44OVoiLz48L2c+PC9zdmc+",
			20
		);

		add_action( "load-$hook", [ $this, 'screen_option' ] );

	}

	/**
	 * Screen options
	 */
	public function screen_option() {

		$option = 'per_page';
		$args = [];

		add_screen_option( $option, $args );

	}

	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page() {

		$action = isset( $_GET['action'] ) ? $_GET['action'] : '';
		$api_key = get_option('ctg_gm_api_key');
		$pixel_key = get_option('ctg_pixel_verification_key');

?>
<div class="wrap">
	<div id="ctg_admin_feedback_container">
		<div id="ctg_admin_feedback_close"></div>
		<p id="ctg_admin_feedback"></p>
	</div>
	<h1 class="wp-heading-inline">CTG Confetti Cannon Settings</h1>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="post" id="ctg_admin_general_settings_form">
						<div class="ctg-form-section" >
							<label 
								   for="ctg_gm_api_key"
								   style="width:100%;"
								   >Google Maps API Key</label>
							<input 
								   type="text" 
								   name="ctg_gm_api_key" 
								   id="ctg_gm_api_key" 
								   style="width:100%;" 
								   value="<?php echo !empty($api_key) ? $api_key : ''; ?>"
								   />

							<input type="hidden" 
								   name="ctg_core_nonce" 
								   id="ctg_core_nonce" 
								   value="<?php wp_create_nonce('ctg_core_nonce'); ?>"
								   />


						</div>
						<div class="ctg-form-section">
							<label 
								   for="ctg_pixel_verification_key"
								   style="width:100%;"
								   >Facebook Pixel Verification Key</label>
							<input
								   type="text"
								   name="ctg_pixel_verification_key"
								   id="ctg_pixel_verification_key"
								   style="width:100%;" 
								   value="<?php echo !empty($pixel_key) ? $pixel_key : ''; ?>"
								   />
						</div>
						<?php submit_button('Save Settings', 'primary', 'ctg_core_submit'); ?>
					</form>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>
</div>
<?php
	}
	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}