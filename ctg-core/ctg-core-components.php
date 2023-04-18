<?php 
defined( 'ABSPATH' ) || exit;

function ctg_is_active( $component = '' ) {
	$retval = false;

	if ( empty( $component ) ) {
		$component = ctg_current_component();
	}

	if ( isset( ctg()->active_components[ $component ] ) || isset( ctg()->required_components[ $component ] ) ) {
		$retval = true;
	}

	return apply_filters( 'ctg_is_active', $retval, $component );
}


/*
 * Creates a list of URI parts to help determine whether the current page 
 * is part of the CTG repertoire.
 * */
function ctg_get_current_component() {

	$path = esc_url($_SERVER['REQUEST_URI']);

	// Strip out any GET parameters
	$path = strtok( $path, '?' );

	$ctg_uri_parts = explode( '/', $path );

	// Dump all the empty bits
	foreach ( (array) $ctg_uri_parts as $key => $uri_chunk ) {
		if ( empty( $ctg_uri_parts[ $key ] ) ) {
			unset( $ctg_uri_parts[ $key ] );
		}
	}

	return $ctg_uri_parts;

}

function ctg_set_component_globals() {

	$ctg = ctg();
	$ctg_uri_parts	= ctg_get_current_component();
	$category_component = ctg_core_is_component_category('locations');

	if ( ! empty( $ctg_uri_parts ) ) {
		if ( isset( $ctg_uri_parts[1] ) ) {
			if ( $ctg_uri_parts[1] === 'category' ) {
				$ctg->current_component = ! empty( $ctg_uri_parts[2] )
					? $ctg_uri_parts[2]
					: '';
			} else {
				if ( $category_component ) {
					$ctg->current_component = 'locations';
				} else {
					$ctg->current_component = $ctg_uri_parts[1];
				}
			}
		}
		if ( isset( $ctg_uri_parts[2] ) ) {
			$ctg->current_item = $ctg_uri_parts[2];
		}
		if ( isset( $ctg_uri_parts[3] ) ) {
			$ctg->current_action = $ctg_uri_parts[3];
		}
	}
}

function ctg_set_current_location() {

	$post = get_post();
	$ctg = ctg();
if ( !isset($post->ID) ) {
return;
}
	$location_id = get_post_meta( $post->ID, '_ctg_location_id', true );
	
	if ( !empty( $location_id ) ) {
		$ctg->current_location = $location_id;
	}
	
	return $ctg->current_location;

}

function ctg_core_is_locations_component() {

	global $post;
	return !empty( get_post_meta( $post->ID, '_ctg_location_id', true ) );

}

function ctg_core_is_franchise_location() {

	$post = get_post();
	return get_post_meta($post->ID, '_ctg_is_franchise', true );

}
