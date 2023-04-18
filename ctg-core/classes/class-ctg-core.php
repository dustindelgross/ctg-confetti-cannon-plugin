<?php

defined( 'ABSPATH' ) || exit;

class CTG_Core extends CTG_Component {

	public $types = array();

	public function __construct() {
		parent::start(
			'core',
			__( 'CTG Core', 'ctg' ),
			CTG_PLUGIN_PATH,
			array(
				'adminbar_myaccount_order' => 10,
			)
		);
		$this->bootstrap();
	}

	private function bootstrap() {

		do_action( 'ctg_core_loaded' );

		$this->load_components();
		
	}

	public function includes( $includes = array() ) {
		$includes = array(
			'admin',
		);
		
		parent::includes($includes);
	}
	
	public function late_includes() {}

	public function setup_globals( $args = array() ) {


		$ctg = ctg();

		if ( empty( $ctg->table_prefix ) ) {
			$ctg->table_prefix = ctg_core_get_table_prefix();
		}

		$current_user = wp_get_current_user();
		$ctg->loggedin_user     = new stdClass();
		$ctg->loggedin_user->id = isset( $current_user->ID ) ? $current_user->ID : 0;


	}

	private function load_components() {

		$ctg = ctg();

		$ctg->optional_components = apply_filters( 
			'ctg_optional_components', 
			array_keys( ctg_core_get_components( 'optional' ) ) 
		);

		$ctg->required_components = apply_filters( 
			'ctg_required_components', 
			array('celebratorcreator', 'locations', 'members') 
		);

		if ( $active_components = ctg_get_option( 'ctg-active-components' ) ) {

			$ctg->active_components = apply_filters( 'ctg_active_components', $active_components );

			$ctg->deactivated_components = apply_filters( 
				'ctg_deactivated_components', 
				array_values( 
					array_diff( 
						array_values( 
							array_merge( 
								$ctg->optional_components, 
								$ctg->required_components 
							) 
						), 
						array_keys( 
							$ctg->active_components
						) 
					) 
				) 
			);

		} else {

			$ctg->deactivated_components = array();

			$active_components = array_fill_keys( 
				array_values( 
					array_merge( 
						$ctg->optional_components, 
						$ctg->required_components 
					) 
				), '1'
			);

			$ctg->active_components = apply_filters( 
				'ctg_active_components', 
				$ctg->active_components 
			);
		}

		foreach ( $ctg->optional_components as $component ) {
			if ( 
				ctg_is_active( $component ) && 
				file_exists( 
					$ctg->plugin_dir . 
					'ctg-' . $component . 
					'/ctg-' . $component . 
					'-loader.php' ) 
			) {
				include $ctg->plugin_dir . 'ctg-' . $component . '/ctg-' . $component . '-loader.php';
			}
		}
		
		foreach ( $ctg->required_components as $component ) {
			if ( file_exists( 
				$ctg->plugin_dir . 
				'ctg-' . $component . 
				'/ctg-' . $component . '-loader.php' ) 
			   ) {
				include $ctg->plugin_dir . 
					'ctg-' . $component . 
					'/ctg-' . $component . 
					'-loader.php';
			}
		}

		$ctg->required_components[] = 'core';

		do_action( 'ctg_core_components_included' );
	}
}
