<?php
add_filter( 'manage_posts_columns', 'manage_wp_posts_be_qe_manage_posts_columns_hraci', 10, 2 );
function manage_wp_posts_be_qe_manage_posts_columns_hraci( $columns, $post_type ) {

	/**
	 * The first example adds our new columns at the end.
	 * Notice that we're specifying a post type because our function covers ALL post types.
	 *
	 * Uncomment this code if you want to add your column at the end
	 */
	if ( $post_type == 'hrac' ) {
		//$columns[ 'release_date' ] = 'Release Date';
		//$columns[ 'coming_soon' ] = 'Coming Soon';
		//$columns[ 'skore_win' ] = 'Skóre W';
	
	}	
	return $columns;
	
	/**
	 * The second example adds our new column after the �Title� column.
	 * Notice that we're specifying a post type because our function covers ALL post types.
	 */
	switch ( $post_type ) {
	
		case 'hrac':
		
			// building a new array of column data
			$new_columns = array();
			
			foreach( $columns as $key => $value ) {
			
				// default-ly add every original column
				$new_columns[ $key ] = $value;
				
				/**
				 * If currently adding the title column,
				 * follow immediately with our custom columns.
				 */
				if ( $key == 'title' ) {
				  $new_columns[ 'first_name' ] = 'Křestní jméno';
				  $columns['last_name'] = __( 'Příjmení', 'pasos' );
		          $columns['active_player'] = __( 'Aktivní', 'pasos' );
		          $columns['age'] = __( 'Věk', 'pasos' );
		          $columns['cislo_dresu'] = __( 'Číslo dresu', 'pasos' );
		          $columns['pozice'] = __( 'Pozice', 'pasos' );
		          $columns['palka'] = __( 'Pálka', 'pasos' );
				}
					
			}
			
			return $new_columns;			
	}
	
	return $columns;
	
}

/**
 * The following filter allows you to make your column(s) sortable.
 *
 * The 'edit-movies' section of the filter name is the custom part
 * of the filter name, which tells WordPress you want this to run
 * on the main 'movies' custom post type edit screen. So, e.g., if
 * your custom post type's name was 'books', then the filter name
 * would be 'manage_edit-books_sortable_columns'.
 *
 * Don't forget that filters must ALWAYS return a value.
 */
add_filter( 'manage_edit-hrac_sortable_columns', 'manage_wp_posts_be_qe_manage_sortable_columns_hraci' );
function manage_wp_posts_be_qe_manage_sortable_columns_hraci( $sortable_columns ) {


	$sortable_columns[ 'first_name' ] = 'first_name';
  $sortable_columns[ 'last_name' ] = 'last_name';
  $sortable_columns[ 'active_player' ] = 'active_player';
  $sortable_columns[ 'age' ] = 'age';
  $sortable_columns[ 'cislo_dresu' ] = 'cislo_dresu';
  $sortable_columns[ 'pozice' ] = 'pozice';
  $sortable_columns[ 'palka' ] = 'palka';

	return $sortable_columns;
	
}

/**
 * Now that we have a column, we need to fill our column with data.
 * The filters to populate your custom column are pretty similar to the ones
 * that added your column: 'manage_pages_custom_column', 'manage_posts_custom_column',
 * and 'manage_{$post_type_name}_posts_custom_column'. All three pass the same
 * 2 arguments: $column_name (a string) and the $post_id (an integer).
 *
 * Our custom column data is post meta so it will be a pretty simple case of retrieving
 * the post meta with the meta key 'release_date'.
 *
 * Note that we are wrapping our post meta in a div with an id of �release_date-� plus the post id.
 * This will come in handy when we are populating our �Quick Edit� row.
 */
add_action( 'manage_hrac_posts_custom_column', 'manage_wp_posts_be_qe_manage_posts_custom_column_hraci', 10, 2 );
function manage_wp_posts_be_qe_manage_posts_custom_column_hraci( $column_name, $post_id ) {

	switch( $column_name ) {
	
		case 'first_name':
		
			echo '<div id="first_name-' . $post_id . '">' . get_post_meta( $post_id, 'first_name', true ) . '</div>';
			break;
			
		case 'last_name':
		
			echo '<div id="last_name-' . $post_id . '">' . get_post_meta( $post_id, 'last_name', true ) . '</div>';
			break;
			
		case 'active_player':
		
			echo '<div id="active_player-' . $post_id . '">' . get_post_meta( $post_id, 'active_player', true ) . '</div>';
			break;
    
    case 'age':
		
			echo '<div id="age-' . $post_id . '">' . get_post_meta( $post_id, 'age', true ) . '</div>';
			break;
      
    case 'cislo_dresu':
		
			echo '<div id="cislo_dresu-' . $post_id . '">' . get_post_meta( $post_id, 'cislo_dresu', true ) . '</div>';
			break;
      
    case 'pozice':
		
			echo '<div id="pozice-' . $post_id . '">' . get_post_meta( $post_id, 'pozice', true ) . '</div>';
			break;
      
    case 'palka':
		
			echo '<div id="palka-' . $post_id . '">' . get_post_meta( $post_id, 'palka', true ) . '</div>';
			break;
			
	}
	
}



add_action( 'bulk_edit_custom_box', 'manage_wp_posts_be_qe_bulk_quick_edit_custom_box_hraci', 10, 2 );
add_action( 'quick_edit_custom_box', 'manage_wp_posts_be_qe_bulk_quick_edit_custom_box_hraci', 10, 2 );
function manage_wp_posts_be_qe_bulk_quick_edit_custom_box_hraci( $column_name, $post_type ) {

	switch ( $post_type ) {
	
		case 'hrac':
		
			switch( $column_name ) {
			
				case 'first_name':
				
					?><fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<label>
								<span class="title">Křestní jméno</span>
								<span class="input-text-wrap">
									<input type="text" value="" name="first_name">
								</span>
							</label>
						</div>
					</fieldset><?php
					break;
					
				case 'last_name':
				
					?><fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<label>
								<span class="title">Příjmení</span>
								<span class="input-text-wrap">
									<input type="text" value="" name="last_name">
								</span>
							</label>
						</div>
					</fieldset><?php
					break;
					
				case 'active_player':
				
					?><fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<label>
								<span class="title">Aktivní hráč</span>
								<span class="input-text-wrap">
									<input type="text" value="" name="active_player">
								</span>
							</label>
						</div>
					</fieldset><?php
					break;
       
       case 'age':
				
					?><fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<label>
								<span class="title">Věk</span>
								<span class="input-text-wrap">
									<input type="text" value="" name="age">
								</span>
							</label>
						</div>
					</fieldset><?php
					break;
          
       case 'cislo_dresu':
				
					?><fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<label>
								<span class="title">Číslo dresu</span>
								<span class="input-text-wrap">
									<input type="text" value="" name="cislo_dresu">
								</span>
							</label>
						</div>
					</fieldset><?php
					break;

          case 'pozice':
				
					?><fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<label>
								<span class="title">Pozice</span>
								<span class="input-text-wrap">
									<input type="text" value="" name="pozice">
								</span>
							</label>
						</div>
					</fieldset><?php
					break;


          case 'palka':
				
					?><fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<label>
								<span class="title">Pálka</span>
								<span class="input-text-wrap">
									<input type="text" value="" name="palka">
								</span>
							</label>
						</div>
					</fieldset><?php
					break;
					
			}
			
			break;
			
	}
	
}


add_action( 'admin_print_scripts-edit.php', 'manage_wp_posts_be_qe_enqueue_admin_scripts_hraci' );
function manage_wp_posts_be_qe_enqueue_admin_scripts_hraci() {

	// if using code as plugin
	wp_enqueue_script( 'manage-wp-posts-using-bulk-quick-edit-hrac', trailingslashit( PASOS_JS_PATH ) . 'bulk_quick_edit_hrac.js', array( 'jquery', 'inline-edit-post' ), '', true );
  //wp_enqueue_script( 'manage-wp-posts-using-bulk-quick-edit', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'bulk_quick_edit.js', array( 'jquery', 'inline-edit-post' ), '', true );
	
}

/**
 * Saving your 'Quick Edit' data is exactly like saving custom data
 * when editing a post, using the 'save_post' hook. With that said,
 * you may have already set this up. If you're not sure, and your
 * 'Quick Edit' data is not saving, odds are you need to hook into
 * the 'save_post' action.
 *
 * The 'save_post' action passes 2 arguments: the $post_id (an integer)
 * and the $post information (an object).
 */
add_action( 'save_post', 'manage_wp_posts_be_qe_save_post_hraci', 10, 2 );
function manage_wp_posts_be_qe_save_post_hraci( $post_id, $post ) {

	// pointless if $_POST is empty (this happens on bulk edit)
	if ( empty( $_POST ) )
		return $post_id;
		
	// verify quick edit nonce
	if ( isset( $_POST[ '_inline_edit' ] ) && ! wp_verify_nonce( $_POST[ '_inline_edit' ], 'inlineeditnonce' ) )
		return $post_id;
			
	// don't save for autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;
		
	// dont save for revisions
	if ( isset( $post->post_type ) && $post->post_type == 'revision' )
		return $post_id;
		
	switch( $post->post_type ) {
	
		case 'hrac':
		
			/**
			 * Because this action is run in several places, checking for the array key
			 * keeps WordPress from editing data that wasn't in the form, i.e. if you had
			 * this post meta on your "Quick Edit" but didn't have it on the "Edit Post" screen.
			 */
			$custom_fields = array( 'first_name', 'last_name', 'age', 'active_player', 'cislo_dresu', 'pozice' , 'palka'  );
			
			foreach( $custom_fields as $field ) {
			
				if ( array_key_exists( $field, $_POST ) )
					update_post_meta( $post_id, $field, $_POST[ $field ] );
					
			}
				
			break;
			
	}
	
}

/**
 * Saving the 'Bulk Edit' data is a little trickier because we have
 * to get JavaScript involved. WordPress saves their bulk edit data
 * via AJAX so, guess what, so do we.
 *
 * Your javascript will run an AJAX function to save your data.
 * This is the WordPress AJAX function that will handle and save your data.
 */
add_action( 'wp_ajax_manage_wp_posts_using_bulk_quick_save_bulk_edit', 'manage_wp_posts_using_bulk_quick_save_bulk_edit_hraci' );
function manage_wp_posts_using_bulk_quick_save_bulk_edit_hraci() {

	// we need the post IDs
	$post_ids = ( isset( $_POST[ 'post_ids' ] ) && !empty( $_POST[ 'post_ids' ] ) ) ? $_POST[ 'post_ids' ] : NULL;
		
	// if we have post IDs
	if ( ! empty( $post_ids ) && is_array( $post_ids ) ) {
	
		// get the custom fields
		$custom_fields = array( 'first_name', 'last_name', 'age' , 'active_player' , 'cislo_dresu' , 'pozice' , 'palka'  );
		
		foreach( $custom_fields as $field ) {
			
			// if it has a value, doesn't update if empty on bulk
			if ( isset( $_POST[ $field ] ) && !empty( $_POST[ $field ] ) ) {
			
				// update for each post ID
				foreach( $post_ids as $post_id ) {
					update_post_meta( $post_id, $field, $_POST[ $field ] );
				}
				
			}
			
		}
		
	}
	
}

?>