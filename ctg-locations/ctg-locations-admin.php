<?php

function ctg_locations_add_meta_box() {
	$screens = [ 'post' ];
	foreach ( $screens as $screen ) {
		add_meta_box(
			'ctg_locations_meta',		// Unique ID
			'CTG Confetti Cannon',		// Box title
			'ctg_locations_meta_box',	// Content callback, must be of type callable
			$screen						// Post type
		);
	}
}
add_action( 'add_meta_boxes', 'ctg_locations_add_meta_box' );

function ctg_locations_meta_box( $post ) {
	$is_franchise = get_post_meta( $post->ID, '_ctg_is_franchise', true );
	$location_id = get_post_meta( $post->ID, '_ctg_location_id', true );
	$member_id = get_post_meta( $post->ID, '_ctg_owner_id', true );
	$locations_options = array();
	$members_options = array();
	$locations = ctg_get_all_locations();
	$members = ctg_members_get_all_members();
	foreach( $locations as $location ) {

		$selected = $location['id'] === $location_id ? 'selected' : '';

		$locations_options[] = sprintf(
			"<option %s value='%s'>%s</option>",
			$selected,
			$location['id'],
			$location['location_name']
		);
	}
	foreach ( $members as $member ) {
		$selected = $member['id'] === $member_id ? 'selected' : '';
		
		$members_options[] = sprintf(
			"<option %s value='%s'>%s %s</option>",
			$selected,
			$member['id'],
			$member['first_name'],
			$member['last_name']
		);
	}
?>
<div style="margin:1rem;">
	<div class="ctg-form-section" style="display:flex;flex-flow: row wrap;align-items:center;">
		<label 
			   for="ctg_location_id"
			   style="width:100%;"
			   >Location</label>
		<select type="text" name="ctg_location_id" id="ctg_location_id" style="width:100%;">
			<option value="">Please select a CTG location</option>
			<option value="0">Remote</option>
			<?php foreach ($locations_options as $locations_option ) { echo $locations_option; } ?>
		</select>
	</div>
</div>
<div style="margin:1rem;">
	<div class="ctg-form-section" style="display:flex;flex-flow: row wrap;align-items:center;">
		<label 
			   for="ctg_owner_id"
			   style="width:100%;"
			   >Location Owner</label>
		<select type="text" name="ctg_owner_id" id="ctg_owner_id" style="width:100%;">
			<option value="">Please select the owner for this CTG location</option>
			<?php foreach ($members_options as $members_option ) { echo $members_option; } ?>
		</select>
	</div>
</div>
<div style="margin:1rem;">
	<div class="ctg-form-section" style="display:flex;flex-flow: row wrap;align-items:center;">
		<input 
			   type="checkbox" 
			   name="ctg_franchise_location" 
			   id="ctg_franchise_location" 
			   value="1"
			   <?php echo checked( $is_franchise ); ?>
			   />
		<label for="ctg_franchise_location">Franchise Location</label>
	</div>
</div>
<?php
}

function ctg_locations_save_post_meta( $post_id ) {

	if ( array_key_exists('ctg_location_id', $_POST ) ) {
		update_post_meta(
			$post_id,
			'_ctg_location_id',
			intval( $_POST['ctg_location_id'] )
		);
	} else {
		update_post_meta(
			$post_id,
			'_ctg_location_id',
			''
		);
	}
	if ( array_key_exists('ctg_owner_id', $_POST ) ) {
		update_post_meta(
			$post_id,
			'_ctg_owner_id',
			intval( $_POST['ctg_owner_id'] )
		);
	} else {
		update_post_meta(
			$post_id,
			'_ctg_owner_id',
			''
		);
	}
	if ( array_key_exists( 'ctg_franchise_location', $_POST ) ) {
		update_post_meta(
			$post_id,
			'_ctg_is_franchise',
			true
		);
	} else {
		update_post_meta(
			$post_id,
			'_ctg_is_franchise',
			false
		);
	}
}
add_action( 'save_post', 'ctg_locations_save_post_meta' );