(function($) {

	// we create a copy of the WP inline edit post function
	var $wp_inline_edit = inlineEditPost.edit;
	
	// and then we overwrite the function with our own code
	inlineEditPost.edit = function( id ) {
	
		// "call" the original WP edit function
		// we don't want to leave WordPress hanging
		$wp_inline_edit.apply( this, arguments );
		
		// now we take care of our business
		
		// get the post ID
		var $post_id = 0;
		if ( typeof( id ) == 'object' )
			$post_id = parseInt( this.getId( id ) );
			
		if ( $post_id > 0 ) {
		
			// define the edit row
			var $edit_row = $( '#edit-' + $post_id );
			
			// get the release date
			var $first_name = $( '#first_name-' + $post_id ).text();
			
			// set the release date
			$edit_row.find( 'input[name="first_name"]' ).val( $first_name );
			
			// get the release date
			var $last_name = $( '#last_name-' + $post_id ).text();
			
			// set the film rating
      $edit_row.find( 'input[name="last_name"]' ).val( $last_name );
			
			// get the film rating
			var $active_player = $( '#active_player-' + $post_id ).text();

			// set the film rating
			$edit_row.find( 'input[name="active_player"]' ).val( $active_player );

      // get the film rating
			var $cislo_dresu = $( '#cislo_dresu-' + $post_id ).text();

			// set the film rating
			$edit_row.find( 'input[name="cislo_dresu"]' ).val( $cislo_dresu );


       // get the film rating
			var $age = $( '#age-' + $post_id ).text();

			// set the film rating
			$edit_row.find( 'input[name="age"]' ).val( $age );


        // get the film rating
			var $pozice = $( '#pozice-' + $post_id ).text();

			// set the film rating
			$edit_row.find( 'input[name="pozice"]' ).val( $pozice );


        // get the film rating
			var $palka = $( '#palka-' + $post_id ).text();

			// set the film rating
			$edit_row.find( 'input[name="palka"]' ).val( $palka );


      
		}
		
	};
	
	$( '#bulk_edit' ).live( 'click', function() {
	
		// define the bulk edit row
		var $bulk_row = $( '#bulk-edit' );
		
		// get the selected post ids that are being edited
		var $post_ids = new Array();
		$bulk_row.find( '#bulk-titles' ).children().each( function() {
			$post_ids.push( $( this ).attr( 'id' ).replace( /^(ttle)/i, '' ) );
		});
		
		// get the custom fields
		var $first_name = $bulk_row.find( 'input[name="first_name"]' ).val();
		var $last_name = $bulk_row.find( 'input[name="last_name"]' ).val();
		var $active_player = $bulk_row.find( 'input[name="active_player"]' ).val();
    var $cislo_dresu = $bulk_row.find( 'input[name="cislo_dresu"]' ).val();
    var $age = $bulk_row.find( 'input[name="age"]' ).val();
    var $pozice = $bulk_row.find( 'input[name="pozice"]' ).val();
    var $palka = $bulk_row.find( 'input[name="palka"]' ).val();

		
		// save the data
		$.ajax({
			url: ajaxurl, // this is a variable that WordPress has already defined for us
			type: 'POST',
			async: false,
			cache: false,
			data: {
				action: 'manage_wp_posts_using_bulk_quick_save_bulk_edit', // this is the name of our WP AJAX function that we'll set up next
				post_ids: $post_ids, // and these are the 2 parameters we're passing to our function
				first_name: $first_name,
				last_name: $last_name,
				active_player: $active_player,
        cislo_dresu: $cislo_dresu,
        pozice: $pozice,
        palka: $palka,
        age: $age,
			}
		});
		
	});
	
})(jQuery);