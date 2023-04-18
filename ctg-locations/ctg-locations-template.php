<?php 

function ctg_get_locations_slug() {
	return apply_filters( 'ctg_get_locations_slug', 'locations' );
}

function ctg_location_locate_template_part( $template = '' ) {

	if ( ! $template ) {
		return '';
	}

	$ctg_template_parts = array();
	if ( ctg_core_is_locations_component() && !is_archive('locations') && !is_home() ) {
		$ctg_template_parts[] = 'locations/single/ctg-locations-%s.php';
	} else if ( current_user_can('administrator') ) {
		echo 'Howdy';
	}
	
	$templates = array();

	foreach ( $ctg_template_parts as $ctg_template_part ) {
		$templates[] = sprintf( $ctg_template_part, $template );
	}

	return locate_template( $templates, true, true );

}

function ctg_location_get_template_part( $template = '' ) {

	$located = ctg_location_locate_template_part( $template );

	if ( false !== $located ) {
		$slug = str_replace( '.php', '', $located );
		$name = null;

		do_action( 'get_template_part_' . $slug, $slug, $name );

		load_template( $located, true );

	}

	return $located;
}

function ctg_location_template_part() {

	$templates = array();

	if ( ctg_core_is_locations_component() && !is_archive('locations') && !is_home() ) {
		$templates[] = 'single';
	}

	foreach ( $templates as $template ) {
		ctg_location_get_template_part( $template );
	}

}

add_filter( 'astra_entry_content_before', 'ctg_location_template_part');

function ctg_location_directory_template_part() {

	if ( ctg_core_is_locations_component() && is_archive('locations') && !is_home() ) {

		global $post;
		$location_id = get_post_meta( $post->ID, '_ctg_location_id', true );
		$location = new CTG_Locations_Location($location_id);
		$phone_href = str_replace(array( "(",")"," ","-" ),array(), $location->phone);
		$gm_api_key = get_option( 'ctg_gm_api_key' );
		$maps_address = implode(
			"%20",
			array(
				str_replace( " ", "%20", $location->street_primary ),
				str_replace( " ", "%20", $location->street_secondary ),
				str_replace( " ", "%20", $location->city ),
				$location->state,
				$location->zip,
			)
		);

?>
<div class="ctg-flex-container rowwrap jc-c ai-c" style="flex-flow:row wrap;justify-content:center;align-items:center;">
	<div class="ctg-flex-item half">
		<h4>
			<?php echo $location->street_primary; ?>
		</h4>
		<?php if ( isset( $location->street_secondary ) ) {?>
		<h4>
			<?php echo $location->street_secondary; ?>
		</h4>
		<?php } ?>
		<h5>
			<?php echo "{$location->city}, {$location->state} {$location->zip}"; ?>
		</h5>
		<h6>
			<a href="tel:<?php echo $phone_href; ?>"><?php echo $location->phone; ?></a>
		</h6>
	</div>
	<div class="ctg-flex-item half">
		<?php if ($gm_api_key) { ?>
		<iframe loading="lazy"
				allowfullscreen
				src="https://maps.google.com/maps/embed/v1/place?key=<?php echo $gm_api_key; ?>&zoom=14&q=<?php echo $maps_address; ?>"
				width="200px"
				height="200px"
				referrerpolicy="no-referrer-when-downgrade"
				title="<?php echo $maps_address; ?>"
				aria-label="<?php echo $maps_address; ?>"
				style="filter:grayscale(100%);width:100%;max-height:50vh;min-height:200px;">
		</iframe>
		<?php } ?>
	</div>
</div>
<?php
	}
}
add_filter( 'the_excerpt', 'ctg_location_directory_template_part');
/*
function custom_excerpt_length( $length ) {
    return 20; // Change this number to adjust the length of the excerpt
}
add_filter( 'excerpt_length', 'custom_excerpt_length' );

function custom_excerpt_more( $more ) {
    return '...'; // Change this text to adjust the "read more" link
}
add_filter( 'excerpt_more', 'custom_excerpt_more' );

function custom_archive_content() {
    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post();
            the_title( '<h2><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
            the_excerpt();
        }
    }
}
add_action( 'astra_entry_content_before', 'custom_archive_content' );
*/