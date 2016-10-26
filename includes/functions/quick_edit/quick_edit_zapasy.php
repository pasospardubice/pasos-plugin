<?php
add_filter( 'manage_posts_columns', 'manage_wp_posts_be_qe_manage_posts_columns_zapasy', 10, 2 );
function manage_wp_posts_be_qe_manage_posts_columns_zapasy( $columns, $post_type ) {

	/**
	 * The first example adds our new columns at the end.
	 * Notice that we're specifying a post type because our function covers ALL post types.
	 *
	 * Uncomment this code if you want to add your column at the end
	 */
	if ( $post_type == 'klub' ) {
		//$columns[ 'release_date' ] = 'Release Date';
		//$columns[ 'coming_soon' ] = 'Coming Soon';
		//$columns[ 'skore_win' ] = 'Skóre W';
    //$columns[ 'skore_lose' ] = 'Skóre L';
	
	}	
	return $columns;
	
	/**
	 * The second example adds our new column after the �Title� column.
	 * Notice that we're specifying a post type because our function covers ALL post types.
	 */
	switch ( $post_type ) {
	
		case 'zapas':
		
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
					$new_columns[ 'klub_sezona' ] = 'Sezóna';
					$new_columns[ 'home_klub_win' ] = 'Výhry';
					$new_columns[ 'home_klub_lose' ] = 'Prohry';
          $new_columns[ 'klub_points' ] = 'Body';
          $new_columns[ 'skore_win' ] = 'Skóre W';
          $new_columns[ 'skore_lose' ] = 'Skóre L';
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
add_filter( 'manage_edit-klub_sortable_columns', 'manage_wp_posts_be_qe_manage_sortable_columns_zapasy' );
function manage_wp_posts_be_qe_manage_sortable_columns_zapasy( $sortable_columns ) {

	/**
	 * In order to make a column sortable, add the
	 * column data to the $sortable_columns array.
	 *
	 * I want to make my 'Release Date' column
	 * sortable so the array indexes (the 'release_date_column'
	 * value between the []) need to match from
	 * where we added the column in the
	 * manage_wp_posts_be_qe_manage_posts_columns()
	 * function.
	 *
	 * The array value (after the =) should be set to
	 * identify the data that is going to be sorted,
	 * i.e. what will be placed in the URL when it's sorted.
	 * Since my release date is a custom field, I just
	 * use the custom field name, 'release_date'.
	 *
	 * When the column is clicked, the URL will look like this:
	 * http://mywebsite.com/wp-admin/edit.php?post_type=movies&orderby=release_date&order=asc
	 */
	$sortable_columns[ 'klub_sezona' ] = 'klub_sezona';
	$sortable_columns[ 'home_klub_win' ] = 'home_klub_win';
  $sortable_columns[ 'home_klub_lose' ] = 'home_klub_lose';
  $sortable_columns[ 'klub_points' ] = 'klub_points';
  $sortable_columns[ 'skore_win' ] = 'skore_win';
  $sortable_columns[ 'skore_lose' ] = 'skore_lose';

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
add_action( 'manage_klub_posts_custom_column', 'manage_wp_posts_be_qe_manage_posts_custom_column_zapasy', 10, 2 );
function manage_wp_posts_be_qe_manage_posts_custom_column_zapasy( $column_name, $post_id ) {

	switch( $column_name ) {
	
		case 'klub_sezona':
		
			echo '<div id="klub_sezona-' . $post_id . '">' . get_post_meta( $post_id, 'klub_sezona', true ) . '</div>';
			break;
			
		case 'home_klub_win':
		
			echo '<div id="home_klub_win-' . $post_id . '">' . get_post_meta( $post_id, 'home_klub_win', true ) . '</div>';
			break;
			
		case 'home_klub_lose':
		
			echo '<div id="home_klub_lose-' . $post_id . '">' . get_post_meta( $post_id, 'home_klub_lose', true ) . '</div>';
			break;
      
    case 'klub_points':
		
			echo '<div id="klub_points-' . $post_id . '">' . get_post_meta( $post_id, 'klub_points', true ) . '</div>';
			break;
      
    case 'skore_win':
		
			echo '<div id="skore_win-' . $post_id . '">' . get_post_meta( $post_id, 'skore_win', true ) . '</div>';
			break;
      
    case 'skore_lose':
		
			echo '<div id="skore_lose-' . $post_id . '">' . get_post_meta( $post_id, 'skore_lose', true ) . '</div>';
			break;
			
	}
	
}



add_action( 'bulk_edit_custom_box', 'manage_wp_posts_be_qe_bulk_quick_edit_custom_box_zapasy', 10, 2 );
add_action( 'quick_edit_custom_box', 'manage_wp_posts_be_qe_bulk_quick_edit_custom_box_zapasy', 10, 2 );
function manage_wp_posts_be_qe_bulk_quick_edit_custom_box_zapasy( $column_name, $post_type ) {

	switch ( $post_type ) {
	
		case 'zapas':
		
			switch( $column_name ) {
			
				case 'klub_sezona':
				
					?><fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<label>
								<span class="title">Sezóna</span>
								<span class="input-text-wrap">
									<input type="text" value="" name="klub_sezona">
								</span>
							</label>
						</div>
					</fieldset><?php
					break;
					
				case 'home_klub_win':
				
					?><fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<label>
								<span class="title">Výhry</span>
								<span class="input-text-wrap">
									<input type="text" value="" name="home_klub_win">
								</span>
							</label>
						</div>
					</fieldset><?php
					break;
					
				case 'home_klub_lose':
				
					?><fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<label>
								<span class="title">Prohry</span>
								<span class="input-text-wrap">
									<input type="text" value="" name="home_klub_lose">
								</span>
							</label>
						</div>
					</fieldset><?php
					break;
          
       case 'klub_points':
				
					?><fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<label>
								<span class="title">Body</span>
								<span class="input-text-wrap">
									<input type="text" value="" name="klub_points">
								</span>
							</label>
						</div>
					</fieldset><?php
					break;
          
          
      case 'skore_win':
				
					?>a<fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<label>
								<span class="title">Skóre W</span>
								<span class="input-text-wrap">
									<input type="text" value="" name="skore_win">
								</span>
							</label>
						</div>
					</fieldset><?php
					break;
          
      case 'skore_lose':
				
					?>b<fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<label>
								<span class="title">Skóre L</span>
								<span class="input-text-wrap">
									<input type="text" value="" name="skore_lose">
								</span>
							</label>
						</div>
					</fieldset><?php
					break;
					
			}
			
			break;
			
	}
	
}


add_action( 'admin_print_scripts-edit.php', 'manage_wp_posts_be_qe_enqueue_admin_scripts_zapasy' );
function manage_wp_posts_be_qe_enqueue_admin_scripts_zapasy() {

	// if using code as plugin
	wp_enqueue_script( 'manage-wp-posts-using-bulk-quick-edit', trailingslashit( PASOS_JS_PATH ) . 'bulk_quick_edit_klub.js', array( 'jquery', 'inline-edit-post' ), '', true );
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
add_action( 'save_post', 'manage_wp_posts_be_qe_save_post_zapasy', 10, 2 );
function manage_wp_posts_be_qe_save_post_zapasy( $post_id, $post ) {

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
	
		case 'klub':
		
			/**
			 * Because this action is run in several places, checking for the array key
			 * keeps WordPress from editing data that wasn't in the form, i.e. if you had
			 * this post meta on your "Quick Edit" but didn't have it on the "Edit Post" screen.
			 */
			$custom_fields = array( 'klub_sezona', 'home_klub_win', 'home_klub_lose', 'klub_points' , 'skore_win' , 'skore_lose' );
			
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
add_action( 'wp_ajax_manage_wp_posts_using_bulk_quick_save_bulk_edit', 'manage_wp_posts_using_bulk_quick_save_bulk_edit_zapasy' );
function manage_wp_posts_using_bulk_quick_save_bulk_edit_zapasy() {

	// we need the post IDs
	$post_ids = ( isset( $_POST[ 'post_ids' ] ) && !empty( $_POST[ 'post_ids' ] ) ) ? $_POST[ 'post_ids' ] : NULL;
		
	// if we have post IDs
	if ( ! empty( $post_ids ) && is_array( $post_ids ) ) {
	
		// get the custom fields
		$custom_fields = array( 'klub_sezona', 'home_klub_win', 'home_klub_lose' , 'klub_points' , 'skore_win' , 'skore_lose' );
		
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