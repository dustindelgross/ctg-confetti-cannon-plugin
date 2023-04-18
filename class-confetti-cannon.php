<?php 
/**
 * Confetti Cannon class.
 *
 * The main class that initiates and runs the addon.
 *
 * @since 1.0.0
 */
defined( 'ABSPATH' ) || exit;

class Confetti_Cannon {

	public $ctg_nav = array();

	public $ctg_options_nav = array();

	public $unfiltered_uri = array();

	public $canonical_stack = array();

	public $action_variables = array();

	public $current_member_type = '';

	public $required_components = array();

	public $loaded_components = array();

	public $active_components = array();

	public $available_integrations = array();

	public $integrations = array();

	public $do_autoload = true;

	public $options = array();

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @return \CTG_Confetti_Cannon\Confetti_Cannon An instance of the class.
	 */
	public static function instance() {

		static $instance = null;

		if ( null === $instance ) {
			$instance = new Confetti_Cannon();
			$instance->constants();
			$instance->setup_globals();
			$instance->includes();
		}

		return $instance;

	}

	public function constants() {

		global $wpdb;

		if ( ! defined( 'CTG_PLUGIN_FILE' ) ) {
			define( 'CTG_PLUGIN_FILE', __FILE__ );				
		}

		if ( ! defined( 'CTG_PLUGIN_IS_INSTALLED' ) ) {
			define( 'CTG_PLUGIN_IS_INSTALLED', 1);	
		}

		if ( ! defined( 'CTG_VERSION' ) ) {
			define( 'CTG_VERSION', '1.0.1');				
		}

		if ( ! defined( 'CTG_PLUGIN_DB_VERSION' ) ) {
			define( 'CTG_PLUGIN_DB_VERSION', '2.2.0');				
		}

		if ( ! defined( 'CTG_PLUGIN_BASENAME' ) ) {
			define( 'CTG_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );				
		}

		if ( ! defined( 'CTG_PLUGIN_PATH' ) ) {
			define( 'CTG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );				
		}

		if ( ! defined( 'CTG_PLUGIN_URL' ) ) {
			define( 'CTG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );				
		}

		if ( ! defined( 'MINIMUM_ELEMENTOR_VERSION' ) ) {
			define( 'MINIMUM_ELEMENTOR_VERSION', '3.5.0' );				
		}

		if ( ! defined( 'MINIMUM_PHP_VERSION' ) ) {
			define( 'MINIMUM_PHP_VERSION', '7.3' );				
		}

		$this->table_prefix = $wpdb->base_prefix;


	}

	private function setup_globals() {

		$this->version    = defined( 'CTG_VERSION' ) ? CTG_VERSION : '1.0.1';
		$this->db_version = 18701;

		$this->my_account_menu_id = '';

		$this->unfiltered_uri_offset = 0;

		$this->no_status_set = false;

		$this->current_component = '';

		$this->current_item = '';

		$this->current_action = '';

		$this->is_single_item = false;

		$this->file       = constant( 'CTG_PLUGIN_PATH' ) . 'ctg-confetti-cannon-loader.php';
		$this->basename   = basename( constant( 'CTG_PLUGIN_PATH' ) ) . '/ctg-confetti-cannon-loader.php';
		$this->plugin_dir = trailingslashit( constant( 'CTG_PLUGIN_PATH' ) );
		$this->plugin_url = trailingslashit( constant( 'CTG_PLUGIN_URL' ) );

		$this->themes_dir = $this->plugin_dir . 'ctg-templates';
		$this->themes_url = $this->plugin_url . 'ctg-templates';

		$this->theme_compat = new stdClass(); // Base theme compatibility class.
		$this->filters      = new stdClass(); // Used when adding/removing filters.

		$this->current_user   = new stdClass();
		$this->displayed_user = new stdClass();

		$this->celebrator_type_post_type = apply_filters( 'ctg_celebrator_type_post_type', 'ctg-celebrator-type' );

	}

	public function includes() {

		spl_autoload_register( array( $this, 'autoload' ) );
		require $this->plugin_dir . 'ctg-core/ctg-core-dependency.php';
		require $this->plugin_dir . 'ctg-core/ctg-core-functions.php';
		require $this->plugin_dir . 'ctg-core/ctg-core-actions.php';
		require $this->plugin_dir . 'ctg-core/ctg-core-components.php';
		require $this->plugin_dir . 'ctg-core/ctg-core-loader.php';
		require $this->plugin_dir . 'ctg-core/ctg-core-template.php';
		//		require $this->plugin_dir . 'ctg-core/ctg-core-settings.php';
		require $this->plugin_dir . 'ctg-core/ctg-core-install.php';

		require $this->plugin_dir . 'ctg-core/ctg-core-cssjs.php';

		ctg_core_install();

	}

	public function autoload( $class ) {

		$class_parts = explode( '_', strtolower( $class ) );
		if ( 'ctg' !== $class_parts[0] ) {
			return;
		}

		$components = array(
			'core',
			'celebratorcreator',
			'locations',
			'members',
		);

		$irregular_map = array(
			'CTG_Component'	=> 'core',
		);

		$component = null;

		if ( isset( $irregular_map[ $class ] ) ) {
			$component = $irregular_map[ $class ];

		} elseif ( in_array( $class_parts[1], $components, true ) ) {
			$component = $class_parts[1];
		}


		if ( ! $component ) {
			return;
		}

		$class = strtolower( str_replace( '_', '-', $class ) );

		$path = dirname( __FILE__ ) . "/ctg-{$component}/classes/class-{$class}.php";

		if ( ! file_exists( $path ) ) {
			return;
		}

		if ( ! in_array( $component, array( 'core', 'celebratorcreator', 'locations', 'members' ), true ) && ! ctg_is_active( $component ) ) {
			return;
		}

		require $path;
	}


	/**
	 * Constructor. Do nothing.
	 */
	public function __construct() {}

	/**
	 * Compatibility Checks
	 *
	 * Run some dependency checks here if we ever have any.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function is_compatible() {}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a dependency installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'ctg' ),
			'<strong>' . esc_html__( 'CTG Confetti Cannon', 'ctg' ) . '</strong>',
			'<strong>' . esc_html__( 'a plugin', 'ctg' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required version for a dependency.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ctg' ),
			'<strong>' . esc_html__( 'CTG Confetti Cannon', 'ctg' ) . '</strong>',
			'<strong>' . esc_html__( 'a plugin', 'ctg' ) . '</strong>',
			'1.0.0'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ctg' ),
			'<strong>' . esc_html__( 'CTG Confetti Cannon', 'ctg' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'ctg' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Initialize
	 *
	 * Load this functionality only after dependencies are initialized.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {}

}
