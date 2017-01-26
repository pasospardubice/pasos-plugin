<?php
add_filter( 'wp_insert_post_data', 'wp_insert_post_data' , 99, 2 );

function wp_insert_post_data( $data, $postarr ) {

		if ( $data['post_type'] == 'zapas'  ) :
			//&& $data['post_title'] == ''
			
				$side1 = NULL;
				$side2 = NULL;

			if ($_POST['turnaj'] == "yes") {

				$post_name =  remove_accents(sanitize_title_with_dashes( $postarr['ID'] . '-' . $_POST["turnaj_name"] ) );
				
				

				$data['post_title'] = $_POST["turnaj_name"]; //Title prispevku
				$data['post_name'] = $post_name; //url prispevku



			}else{

				if (isset($_POST['home_team'])){
					$home_id = $_POST['home_team'];	
					$side1 = $home_id;
				}
				if (isset($_POST['away_team'])){
					$away_id = $_POST['away_team'];
					$side2 = $away_id;	
				}

				$separator = "vs";
				

				
				


				$title = $side1 . ' ' . $separator . ' ' . $side2;
				$title2 = "Reportáž: ".$side1 . ' ' . $separator . ' ' . $side2;
				$post_name = sanitize_title_with_dashes( $postarr['ID'] . '-' . $title );

				$data['post_title'] = $title2; //Title prispevku
				$data['post_name'] = strtolower($post_name); //url prispevku
			}

		endif;
		return $data;
}
/*
if (!function_exists('pasos_get_team_name')) {
	function pasos_get_team_name( $post, $id ) {

		$club = get_default_club();

		if( $post == $club ) {

			$teams = wp_get_object_terms( $id, 'tym' );

			if ( ! empty( $teams ) && is_array( $teams ) ) {

				foreach ( $teams as $team ) {

					$team = reset($teams);
					$t_id = $team->term_id;
					$team_meta = get_option( "taxonomy_term_$t_id" );
					$team_label = $team_meta['wpcm_team_label'];

					if ( $team_label ) {
						$team_name =  $team_label;
					} else {
						$team_name = get_the_title( $post );
					}

				}

			} else {

				$team_name = get_the_title( $post );

			}

		} else {

			$team_name = get_the_title( $post );

		}

		return $team_name;
	}
}*/
?>