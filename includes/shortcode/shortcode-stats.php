<?php
add_action( 'init', 'register_shortcodes_widget_stats');
function register_shortcodes_widget_stats(){

   add_shortcode('player_stats', 'player_stats_function');
   add_shortcode('best_player', 'best_player_function');
   add_shortcode('player_profile_stats', 'player_profile_stats_function');
     
}

function player_stats_function($atts, $content = null) {

   extract(shortcode_atts(array(
   
      'post_type' => 'hrac',
      'season' => '199',
      'team' => '19',
      'orderby' => 'ab', 
      'stats' => 'name,ab', 
      'order' => 'DESC',
      'orderby' => '',
      'limit' => '',
      'only_prijmeni' => 'false' //defaultne nastaveno na "No" - tedy bude defaultne zobrazovat cele jmeno        
      
   ), $atts));

   $player_stats_labels = pasos_get_player_stats_labels();
   $stats_labels = array_merge( pasos_player_header_labels(), $player_stats_labels );

   if ( $limit == 0 ) {
		$limit = -1;
   }


   
   $stats = explode( ',', $stats );

   foreach( $stats as $key => $value ) {
				$stats[$key] = strtolower( trim( $value ) );
				if ( !array_key_exists( $stats[$key], $stats_labels ) )
					unset( $stats[$key] );
	}


   	$numposts = $limit;
   	
   	if ( array_intersect_key( array_flip( $stats ), $player_stats_labels ) ){
		$numposts = -1;
	}

	$orderby = strtolower( $orderby );	
	$order = strtoupper( $order );

   	 $args = array(                                           
                      'post_type' => $post_type,
                      'numposts' => $numposts,
					  'posts_per_page' => $numposts,  
                      'tax_query' => array(), 
                      'orderby' => 'meta_value_num',
                      'order' => $order,
                      'suppress_filters' => 0                                         
                    
     		       );

   	 if ( $season ) {
					$args['tax_query'][] = array(
					'taxonomy' => 'sezona',
					'terms' => $season,
					'field' => 'term_id'
				);
	}

	if ( $team ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'tym',
					'terms' => $team,
					'field' => 'term_id'
				);
	}



    $players = get_posts( $args );

   	$count = 0;	
    if ( sizeof( $players ) > 0 ) {

		foreach( $players as $player ) {
			
			$player_details[$player->ID] = array();


			$active_link_player = get_post_meta($player->ID, "active_player_link", true);

			$count++;
			if ( array_intersect_key( array_flip( $stats ), $player_stats_labels ) ) {
				$player_stats = get_pasos_player_stats( $player->ID );
			}

           foreach( $stats as $stat ) {


						$player_details[$player->ID][$stat] = '';

						
						if ( array_key_exists( $stat, $player_stats_labels ) )  {

							if ( $team ) {
								if ( $season ) {
									$player_details[$player->ID][$stat] = $player_stats[$team][$season]['total'][$stat];

								} else {
									$player_details[$player->ID][$stat] = $player_stats[$team][0]['total'][$stat];
								}
							} else {
								if ( $season ) {
									$player_details[$player->ID][$stat] = $player_stats[0][$season]['total'][$stat];
								} else {
									$player_details[$player->ID][$stat] = $player_stats[0][0]['total'][$stat];								
								}
							} 


       
						} else {
            
              
							switch ( $stat ) {								
								
								case 'name':
									if($only_prijmeni == "true"){
										if($active_link_player == "yes"){
											$player_details[$player->ID][$stat] = '<a href="' . get_permalink( $player->ID ) . '">' . show_prijmeni($player->ID). '</a>';	
										}else{
											$player_details[$player->ID][$stat] = show_prijmeni($player->ID);
										}
										
									}else{
										if($active_link_player == "yes"){
											$player_details[$player->ID][$stat] = '<a href="' . get_permalink( $player->ID ) . '">'. $player->post_title . '</a>';	
										}else{
											$player_details[$player->ID][$stat] = $player->post_title;
										}
										
									}
									  
								break;								
															
							}
						}
					}
      
      
         }
         
         
         if ( array_key_exists( $orderby, $player_stats_labels ) ) {

         		$player_details = subval_sort( $player_details, $orderby );

	        	if ( $order == 'DESC' ) {
					$player_details = array_reverse( $player_details );
				}
			}

       		$output = "";
      		//ob_start();
      		
      		$output .='<table class="sortable seznam-hracu-tabulka"><tbody>';
      		$output .= '<tr>'; 
      		
      		foreach( $stats as $stat ) {

					
					if ( $stat !== 'subs' ) {

						$output .= '<td id="'.$stat.'" class=" '.$stat.' table-menu nadpis">'.$stats_labels[$stat].'</td>';

					}

				}
			$output .= '<td id="'.$stat.'" class=" '.$stat.' table-menu nadpis"> BA </td>';
      		$output .=  '</tr>';
      		
      		$count = 0;
			foreach( $player_details as $player_detail ) {
				$count++;
				if ( $limit > 0 && $count > $limit ) {
					break;
				}

				$output .=  "<tr>";
				foreach( $stats as $stat ) {
					if ( $stat !== 'subs' ) {

						$output .=  '<td class="'.$stat.'">';
	           				$output .=  pasos_get_player_stat( $player_detail, $stat ); 
						$output .=  "</td>";

					}
				}
						if ( pasos_get_player_stat( $player_detail, "h" ) != 0 ) {
							$ba_nezaokrouhlene = (pasos_get_player_stat( $player_detail, "h" )/pasos_get_player_stat( $player_detail, "ab" ))*1000;# code...
							$ba = round($ba_nezaokrouhlene,0);
						}else{
							$ba = "0";
						}
						  
						$output .=  '<td class="'.$stat.'">';
			           		$output .= $ba;
						$output .=  "</td>";

				$output .=  "</tr>";

			} 
      		$output .= '</tbody></table>';
      		//$output .= ob_get_clean();

      		wp_reset_postdata();
      
      
      }						

   

   return $output;
  
}


function best_player_function($atts, $content = null) {

   extract(shortcode_atts(array(
   
      'post_type' => 'hrac',
      'sezona' => '2016',
      'tym' => 'muzi-a',
      'orderby' => 'ab', 
      'stats' => 'name,ab,h', 
      'order' => 'DESC',
      'limit' => 3,
      'only_prijmeni' => 'false' //defaultne nastaveno na "False" - tedy bude defaultne zobrazovat cele jmeno        
      
   ), $atts));

   $output .= "<table id='best-player-widget'>";
   
    $args = array(   
              	'post_type' => 'hrac',
              	'posts_per_page ' => $limit,
              	'ignore_sticky_posts ' => true, 
              	'no_found_rows' => true,           
              	 );

   $the_query = new WP_Query( $args );
   // The Loop
   if ( $the_query->have_posts() ) {
    
   $i=0;
	   while ( $the_query->have_posts() ) {
	   $i++;
	   $the_query->the_post();

	   if($only_prijmeni == "false"){
	   
	   		$cele_jmeno = get_post_meta(get_the_ID(), "last_name", true);
	   
	   }else{
	   
	   		$cele_jmeno = get_post_meta(get_the_ID(), "first_name", true) . " " .get_post_meta(get_the_ID(), "last_name", true);
	   
	   }

	   if( has_post_thumbnail(get_the_ID()) ){

	   		$circle_profil_image =  get_the_post_thumbnail_url( get_the_ID() ); 

	   }else{

	   		$circle_profil_image = get_stylesheet_directory_uri(). "/includes/images/player-circle-default-profile.jpg";

	   }
	   
	   


	   $output .= "			<tr>
	   							<td style='width: 20px;'>$i. </td>";
					$output .= "<td style='width: 46px;'><img src='".$circle_profil_image."' class='circle'></td>";   							
				    $output .= "<td  style='width: 200px;'><span class='player_name'>".$cele_jmeno."</span></td>";
				    $output .= "<td>.365</td>";
				    $output .= "<td>.365</td>";
				    $output .= "<td>.365</td>";
				    $output .= "
	   						</tr>";

		}
		wp_reset_query();
      	wp_reset_postdata();
	}   						

   	$output .= "</table>";

   return $output;

 }

function player_profile_stats_function($atts, $content = null) {

   extract(shortcode_atts(array(
   
      'post_type' => 'hrac',
      'season' => '199',
      'team' => '19',
      'orderby' => 'ab', 
      'stats' => 'name,ab', 
      'order' => 'DESC',
      'orderby' => '',
      'limit' => '',
      'only_prijmeni' => 'false' //defaultne nastaveno na "No" - tedy bude defaultne zobrazovat cele jmeno        
      
   ), $atts));

	global $post;
	//$post->ID = 12528;
	$stats = get_pasos_player_stats( $post->ID );
	$teams = pasos_get_ordered_post_terms( $post->ID, 'tym' );
	$seasons = pasos_get_ordered_post_terms( $post->ID, 'sezona' );


	if( is_array( $teams ) && count( $teams ) > 1 ) {
		foreach( $teams as $team ) {

		$rand = rand(1,99999);
		$name = $team->name;

		if ( $team->parent ) {
			$parent_team = get_term( $team->parent, 'tym');
			$name .= ' (' . $parent_team->name . ')';
		} ?>

		<div class="pasos-profile-stats-block">

			<h3><?php echo $name; ?></h3>

			<ul class="stats-tabs-<?php echo $rand; ?> stats-tabs-multi">
							
				<li class="tabs-multi"><a href="#pasos_team-0_season-0-<?php echo $rand; ?>"><?php printf( __( 'Všechny %s', 'pasos' ), __( 'sezóny', 'pasos' ) ); ?></a></li>

				<?php if( is_array( $seasons ) ): foreach( $seasons as $season ): ?>

					<li><a href="#pasos_team-<?php echo $team->term_id; ?>_season-<?php echo $season->term_id; ?>"><?php echo $season->name; ?></a></li>

				<?php endforeach; endif; ?>
				
			</ul>

			<div id="pasos_team-0_season-0-<?php echo $rand; ?>" class="tabs-panel-<?php echo $rand; ?> tabs-panel-multi stats-table-season-<?php echo $rand; ?>">
							
				<?php pasos_get_template( 'stats-table.php', array( 'stats' => $stats, 'team' => $team->term_id, 'season' => 0 ) ); ?>

				
			</div>
						
			<?php if( is_array( $seasons ) ): foreach( $seasons as $season ): ?>
							
				<div id="pasos_team-<?php echo $team->term_id; ?>_season-<?php echo $season->term_id; ?>" class="tabs-panel-<?php echo $rand; ?> tabs-panel-multi stats-table-season-<?php echo $rand; ?>" style="display: none;">
								
					<?php pasos_get_template( 'stats-table.php', array( 'stats' => $stats, 'team' => $team->term_id, 'season' => $season->term_id ) ); ?>

					
							
				</div>
				
			<?php endforeach; endif; ?>

		</div>
					
		<script type="text/javascript">
			(function($) {
				$('.pasos-stats-tabs-<?php echo $rand; ?> a').click(function(){
					var t = $(this).attr('href');
					
					$(this).parent().addClass('pasos-tabs-multi <?php echo $rand; ?>').siblings('li').removeClass('pasos-tabs-multi <?php echo $rand; ?>');
					$(this).parent().parent().parent().find('.pasos-tabs-panel-<?php echo $rand; ?>').hide();
					$(t).show();

					return false;
				});
			})(jQuery);
		</script>
		<?php	
	}
	}else{ 
		
		foreach( $teams as $team ) {

				$rand = rand(1,99999);
				$name = $team->name;

				if ( $team->parent ) {
					$parent_team = get_term( $team->parent, 'pasos_team');
					$name .= ' (' . $parent_team->name . ')';
				}

		?>

				<div class="pasos-profile-stats-block">

					<h3><?php _e("Tým: ","pasos"); echo $name; ?></h3>

					<ul class="pasos-stats-tabs">
								
						<li class="pasos-tabs"><a href="#pasos_team-0_season-0"><?php printf( __( 'Všechny %s', 'pasos' ), __( 'sezóny', 'pasos' ) ); ?></a></li>

						<?php if( is_array( $seasons ) ): foreach( $seasons as $season ): ?>

							<li><a href="#pasos_team-0_season-<?php echo $season->term_id; ?>"><?php echo $season->name; ?></a></li>

						<?php endforeach; endif; ?>
						
					</ul>
								
					<?php if( is_array( $seasons ) ): foreach( $seasons as $season ): ?>
									
						<div id="pasos_team-0_season-<?php echo $season->term_id; ?>" class="pasos-tabs-panel" style="display: none;">
									
							<?php pasos_get_template( 'stats-table.php', array( 'stats' => $stats, 'team' => 0, 'season' => $season->term_id ) ); ?>			
									
						</div>
						
					<?php endforeach; endif; ?>
								
					<div id="pasos_team-0_season-0" class="pasos-tabs-panel">
									
						<?php pasos_get_template( 'stats-table.php', array( 'stats' => $stats, 'team' => 0, 'season' => 0 ) ); ?>
								
					</div>

				</div>

		<?php
		}
		?>

<?php
	}		
}
?>