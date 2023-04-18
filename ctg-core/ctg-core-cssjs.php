<?php

function ctg_core_styles() {
	$url = ctg()->plugin_url . 'ctg-core/assets/css/';

	$styles = array(
		'ctg-core-css' => array(
			'file' => "{$url}ctg-core.css",
			'dependencies' => array(),
		),
	);

	foreach ( $styles as $id => $style ) {
		wp_enqueue_style( $id, $style['file'], $style['dependencies'], ctg_get_version() );
	}
}
add_action( 'ctg_enqueue_scripts', 'ctg_core_styles' );

function ctg_common_admin_scripts() {
	wp_enqueue_style( 
		'ctg-common-admin-css', 
		CTG_PLUGIN_URL . '/ctg-core/assets/css/ctg-common-admin.css'
	);
	wp_enqueue_script(
		'ctg-core-admin-js',
		CTG_PLUGIN_URL . '/ctg-core/assets/js/ctg-core-admin.js', 
		'jquery'
	);

	$ctg_localized_variables = array(
		'update_options'		=> admin_url( 
			'admin-ajax.php?action=ctg_update_options' 
		),
		'core_nonce' => wp_create_nonce('ctg_core_nonce'),
	);

	wp_localize_script( 
		'ctg-core-admin-js',
		'ctg',
		$ctg_localized_variables
	);

}
add_action( 'admin_enqueue_scripts', 'ctg_common_admin_scripts' );

function ctg_members_scripts() {
	if ( is_front_page() ) {
		wp_enqueue_script(
			'ctg-members-search-js',
			CTG_PLUGIN_URL . 'ctg-core/assets/js/ctg-members-search.js',
			array('jquery')
		);
		wp_enqueue_script( 
			'ctg-locations-home-js', 
			CTG_PLUGIN_URL . 'ctg-core/assets/js/ctg-locations-home.js', 
			array('jquery')
		);
	}

	$ctg_localized_variables = array(
		'members_search'		=> admin_url( 
			'admin-ajax.php?action=ctg_get_members_by_search_terms' 
		),
		'get_location' => admin_url(
			'admin-ajax.php?action=ctg_get_location_by_id'
		),
		'total_members' => admin_url(
			'admin-ajax.php?action=ctg_get_total_members'
		),
		'total_locations' => admin_url(
			'admin-ajax.php?action=ctg_get_total_locations'
		),
		'locations_meta' => admin_url(
			'admin-ajax.php?action=ctg_locations_meta_query'
		),
		'get_locations'		=> admin_url( 
			'admin-ajax.php?action=ctg_get_locations' 
		),
		'get_members_by_location_id'		=> admin_url( 
			'admin-ajax.php?action=ctg_get_members_by_location_id' 
		),
		'get_member_by_id'		=> admin_url( 
			'admin-ajax.php?action=ctg_get_member_by_id'
		),
		'locations_nonce'	=> wp_create_nonce( 
			'ctg_locations_nonce' 
		),
		'members_nonce'		=> wp_create_nonce( 
			'ctg_members_nonce' 
		),
	);

	wp_localize_script( 
		'ctg-locations-home-js', 
		'ctg', 
		$ctg_localized_variables 
	);

	wp_localize_script( 
		'ctg-members-search-js', 
		'ctg', 
		$ctg_localized_variables 
	);

	wp_enqueue_style( 
		'ctg-locations-css', 
		CTG_PLUGIN_URL . 'ctg-core/assets/css/ctg-locations.css'
	);

}
add_action( 'wp_enqueue_scripts', 'ctg_members_scripts' );

function ctg_locations_scripts() {

	if ( ctg_core_is_locations_component() && !is_archive() ) {
		wp_enqueue_script( 
			'ctg-locations-single-js', 
			CTG_PLUGIN_URL . 'ctg-core/assets/js/ctg-locations-single.js', 
			array('jquery')
		);

	}

	wp_enqueue_style( 
		'ctg-locations-css', 
		CTG_PLUGIN_URL . 'ctg-core/assets/css/ctg-locations.css'
	);

	$ctg_localized_variables = array(
		'get_locations'		=> admin_url( 
			'admin-ajax.php?action=ctg_get_locations' 
		),
		'get_members_by_location_id'		=> admin_url( 
			'admin-ajax.php?action=ctg_get_members_by_location_id' 
		),
		'get_member_by_id'		=> admin_url( 
			'admin-ajax.php?action=ctg_get_member_by_id'
		),
		'locations_nonce'	=> wp_create_nonce( 
			'ctg_locations_nonce' 
		),
		'members_nonce'		=> wp_create_nonce( 
			'ctg_members_nonce' 
		),
	);

	wp_localize_script( 
		'ctg-locations-single-js', 
		'ctg', 
		$ctg_localized_variables 
	);

}
add_action('ctg_enqueue_scripts', 'ctg_locations_scripts');

function ctg_locations_admin_scripts() {

	if ( !isset($_GET['page'] ) ) { 
		return; 
	} else {
		if ( is_admin() && $_GET['page'] === 'ctg_locations' ) {

			wp_enqueue_script( 
				'ctg-locations-admin-js', 
				CTG_PLUGIN_URL . '/ctg-core/assets/js/ctg-locations-admin.js', 
				'jquery' 
			);

			wp_enqueue_style( 
				'ctg-locations-admin-css', 
				CTG_PLUGIN_URL . '/ctg-core/assets/css/ctg-locations-admin.css'
			);

			$ctg_localized_variables = array(
				'get_locations'		=> admin_url( 
					'admin-ajax.php?action=ctg_get_locations' 
				),
				'get_location_by_id'		=> admin_url( 
					'admin-ajax.php?action=ctg_get_location_by_id'
				),
				'add_location'	=> admin_url( 
					'admin-ajax.php?action=ctg_add_location'
				),
				'update_location'	=> admin_url( 
					'admin-ajax.php?action=ctg_update_location'
				),
				'delete_location' => admin_url(
					'admin-ajax.php?action=ctg_delete_location'
				),
				'refresh_locations_table'		=> admin_url( 
					'admin-ajax.php?action=ctg_refresh_locations_table'
				),
				'locations_nonce'	=> wp_create_nonce( 
					'ctg_locations_nonce' 
				),
				'delete_location_nonce'	=> wp_create_nonce( 
					'ctg_delete_location' 
				),
			);

			wp_localize_script( 
				'ctg-locations-admin-js', 
				'ctg', 
				$ctg_localized_variables 
			);
		}
	}

}
add_action('admin_enqueue_scripts', 'ctg_locations_admin_scripts');

function ctg_members_admin_scripts() {

	if ( ! isset($_GET['page'])) {
		return;
	} else {
		if ( is_admin() && $_GET['page'] === 'ctg_members') {
			wp_enqueue_script( 
				'ctg-members-admin-js', 
				CTG_PLUGIN_URL . '/ctg-core/assets/js/ctg-members-admin.js', 
				'jquery' 
			);

			wp_enqueue_style( 
				'ctg-members-admin-css', 
				CTG_PLUGIN_URL . '/ctg-core/assets/css/ctg-members-admin.css'
			);

			$ctg_localized_variables = array(
				'get_members_by_location_id'		=> admin_url( 
					'admin-ajax.php?action=ctg_get_members_by_location_id' 
				),
				'get_member_by_id'		=> admin_url( 
					'admin-ajax.php?action=ctg_get_member_by_id' 
				),
				'add_member'	=> admin_url( 
					'admin-ajax.php?action=ctg_add_member'
				),
				'update_member'	=> admin_url( 
					'admin-ajax.php?action=ctg_update_member'
				),
				'delete_member' => admin_url(
					'admin-ajax.php?action=ctg_delete_member'
				),
				'refresh_members_table'		=> admin_url( 
					'admin-ajax.php?action=ctg_refresh_members_table'
				),
				'members_nonce'		=> wp_create_nonce( 
					'ctg_members_nonce' 
				),
				'delete_member_nonce' => wp_create_nonce(
					'ctg_delete_member'
				),
				'attachment_id'	=> get_option( 'media_selector_attachment_id', 0 )
			);

			wp_localize_script( 
				'ctg-members-admin-js', 
				'ctg', 
				$ctg_localized_variables 
			);

		}
	}

}
add_action('admin_enqueue_scripts', 'ctg_members_admin_scripts');

function ctg_gtag_head() {
	echo "<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WNXWW46');</script>
<!-- End Google Tag Manager -->";
}
add_action( 'wp_head', 'ctg_gtag_head', 1 );

function ctg_gtag_body() {
	echo "<!-- Google Tag Manager (noscript) -->
<noscript><iframe src='https://www.googletagmanager.com/ns.html?id=GTM-WNXWW46'
height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->";
}
add_action( 'wp_body_open', 'ctg_gtag_body', 1 );