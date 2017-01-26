<?php

function pasos_get_preset_labels( $type = 'players', $format = 'label' ) {
  
  $sport = "baseball";
  $data = pasos_get_sport_presets();

  
  if( $type == 'standings') {
    $stats = $data[$sport]['standings_columns'];
  } elseif ( $type == 'players' ) {
    $stats = $data[$sport]['stats_labels'];
  }

  foreach( $stats as $key => $value ) {

    $output[$key] = $value[$format];

  }

  $pasos_get_preset_labels = array( 
                                    "ab" => "AB", // počet startů na pálce (bez BB,HB,SF,SH,E2int)
                                    "h" => "H", // metový odpal (i vícemetový) 
                                    "hr" => "HR", // homerun
                                    "so" => "SO", // Strike Out
                                    //"pab" => "PAB", // Celkový počet startů = AB+BB+HB+SH
                                    );
   //print_r($output);
  //return $output;
  return $pasos_get_preset_labels;
}


function pasos_get_player_stats_labels( $subs = false) {

  $appearance_label = pasos_get_appearance_labels();

  $labels = pasos_get_preset_labels();

  foreach( $labels as $label => $value ) {
   // if( get_option( 'wpcm_show_stats_'. $label ) == 'yes' ) {
      $output[$label] = $value;
    //}
  }

  $stats_labels = array_merge( $appearance_label, $output );
  return $stats_labels;
}

function pasos_get_appearance_labels() {

  $appearances = array(
    'appearances' => _x( 'G', 'Games Played (Appearances)', 'pasos' )
  );

  return apply_filters( 'pasos_get_appearance_labels', $appearances );
}

if (!function_exists('get_pasos_player_stats')) {
  function get_pasos_player_stats( $post = null ) {

    if ( !$post ) global $post;

    $output = array();

    $teams = wp_get_object_terms( $post, 'tym' );
    $seasons = wp_get_object_terms( $post, 'sezona' );

    // isolated team stats
    if ( is_array( $teams ) ) {

      foreach ( $teams as $team ) {

        // combined season stats per team
        $stats = get_pasos_player_auto_stats( $post, $team->term_id, null );

        $output[$team->term_id][0] = array(
          'auto' => $stats,
          'total' => $stats
        );

        //print_r($output);
        // isolated season stats per team
        if ( is_array( $seasons ) ) {

          foreach ( $seasons as $season ) {
            //print_r ($season);
            $stats = get_pasos_player_auto_stats( $post, $team->term_id, $season->term_id );
            $output[$team->term_id][$season->term_id] = array(
              'auto' => $stats,
              'total' => $stats
            );

          }
        }

       // print_r($output);

      }
    }
     
    // combined season stats for combined team
    $stats = get_pasos_player_auto_stats( $post );
    $output[0][0] = array(
      'auto' => $stats,
      'total' => $stats
    );

    // isolated season stats for combined team
    if ( is_array( $seasons ) ) {

      foreach ( $seasons as $season ) {

        $stats = get_pasos_player_auto_stats( $post, null, $season->term_id );
        $output[0][$season->term_id] = array(
          'auto' => $stats,
          'total' => $stats
        );

      }
    }

    // manual stats
    
    $stats = (array)unserialize( get_post_meta( $post, 'pasos_stats', true ) );

    if ( is_array($stats) ):

      foreach( $stats as $team_key => $team_val ):

        if ( is_array( $team_val ) && array_key_exists( $team_key, $output ) ):

          foreach( $team_val as $season_key => $season_val ):

            if ( array_key_exists ( $season_key, $output[$team_key] ) ) {

              $output[$team_key][$season_key]['manual'] = $season_val;

              foreach( $output[$team_key][$season_key]['total'] as $index_key => &$index_val ) {

                if ( array_key_exists( $index_key, $season_val ) )

                 $index_val += $season_val[$index_key];
              
              }
            }
          endforeach;
        endif;
      endforeach;
    endif;

    
    return $output;

  }
}


function pasos_get_player_stat( $player_detail, $stat ) {

  if ( $stat == 'rating' ) {
    $stat = pasos_get_player_average_rating( $player_detail['rating'], $player_detail['appearances'] );
  } elseif ( $stat == 'appearances' ) {
    $stat = pasos_get_player_appearances( $player_detail );
  } else {
    $stat = $player_detail[$stat];
  }

  return $stat;
}

function pasos_player_stats_table( $stats = array(), $team = 0, $season = 0 ) {

    if ( array_key_exists( $team, $stats ) ):


      if ( array_key_exists( $season, $stats[$team] ) ):
      
        $stats = $stats[$team][$season];

      endif;

    endif;

    
    $stats_labels = pasos_get_player_stats_labels(); 
?>
    <table>
      <thead>
        <tr>
          <td>&nbsp;</td>
          <?php foreach( $stats_labels as $key => $val ): ?>
              <th><?php echo $val; ?></th>
            <?php
          endforeach; ?>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th align="right">Total</th>
          <?php foreach( $stats_labels as $key => $val ): ?>
              <td><input type="text" data-index="<?php echo $key; ?>" value="<?php pasos_stats_value( $stats, 'total', $key ); ?>" size="3" tabindex="-1" class="player-stats-total-<?php echo $key; ?>" readonly /></td>
            <?php
          endforeach; ?>
        </tr>
      </tfoot>
      <tbody>
        <tr>
          <td align="right"><?php _e( 'Auto' ); ?></td>
          <?php foreach( $stats_labels as $key => $val ):
            ?>
              <td><input type="text" data-index="<?php echo $key; ?>" value="<?php pasos_stats_value( $stats, 'auto', $key ); ?>" size="3" tabindex="-1" class="player-stats-auto-<?php echo $key; ?>" readonly /></td>
            <?php
          endforeach; ?>
        </tr>
        <tr>
          <td align="right"><?php _e( 'Manual', 'pasos' ); ?></td>
          <?php foreach( $stats_labels as $key => $val ):
            ?>
              <td><input type="text" data-index="<?php echo $key; ?>" name="pasos_stats[<?php echo $team; ?>][<?php echo $season; ?>][<?php echo $key; ?>]" value="<?php pasos_stats_value( $stats, 'manual', $key ); ?>" size="3" class="player-stats-manual-<?php echo $key; ?>"<?php echo ( $season == 0 ? ' readonly' : '' ); ?> /></td>
            <?php
          endforeach; ?>
        </tr>
      </tbody>
    </table>

<?php
}

function stats_hrac_save( $post_id ){
    
    // Bail if we're doing an auto save
    //if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    
    // if our current user can't edit this post, bail
    //if( !current_user_can( 'edit_post' ) ) return;

    // Exits script depending on save status
    /*if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
       //return;
    }*/

    if( isset( $_POST['pasos_stats'] ) ) {
      $stats = $_POST['pasos_stats'];
    } else {
      $stats = array();
    }
    if( is_array( $stats ) ) array_walk_recursive( $stats, 'pasos_array_values_to_int' );
    
    update_post_meta( $post_id, 'pasos_stats', serialize( $stats ) );

    do_action( 'delete_plugin_transients' );      
} 

if (!function_exists('pasos_array_values_to_int')) {
  function pasos_array_values_to_int( &$value, $key ) {

    $value = (int)$value;
  }
}

if (!function_exists('get_pasos_player_stats_empty_row')) {
  function get_pasos_player_stats_empty_row() {

    $player_stats_labels = pasos_get_preset_labels();

    $output = array( 'appearances' => 0 );

    foreach( $player_stats_labels as $key => $val ) {
      $output[$key] = 0;
    }

    return $output;
  }
}

if (!function_exists('pasos_stats_value')) {
  function pasos_stats_value( $stats, $type, $index ) {

    echo get_pasos_stats_value( $stats, $type, $index );
  }
}

if (!function_exists('get_pasos_stats_value')) {
  function get_pasos_stats_value( $stats = array(), $type = 'manual', $index = 'goals' ) {

    if ( is_array( $stats ) ) {


      if ( array_key_exists( $type, $stats ) ) {


        if ( array_key_exists( $index, $stats[$type] ) ) {

          return (float)$stats[$type][$index];
        }
      }
    }

    return 0;
  }
}

if (!function_exists('get_pasos_player_stats_empty_row')) {
  function get_pasos_player_stats_empty_row() {

    $player_stats_labels = pasos_get_preset_labels();
    $player_stats_labels = pasos_get_player_stats_labels();

    $output = array( 'appearances' => 0 );

    foreach( $player_stats_labels as $key => $val ) {
      $output[$key] = 0;
    }

    return $output;
  }
}

function pasos_get_ordered_post_terms( $post, $taxonomy ) {

  $terms = wp_get_object_terms( $post, $taxonomy );
  if ( $terms ) {
      $term_ids = array();
      foreach ( $terms as $term ) {
          $term_ids[] = $term->term_id;
      }
      if( !empty( $term_ids ) ) {

        return get_terms( array( 'taxonomy' => $taxonomy, 'include' => $term_ids ) );

      } else {

        return wp_get_object_terms( $post, $taxonomy );
        
      }

  }
}

function pasos_get_player_average_rating( $rating, $appearances ) {

  if ( $rating > 0 ) {
    $avrating = pasos_divide( $rating, $appearances );
    $average = sprintf( "%01.2f", round($avrating, 2) );
  } else {
    $average = '0';
  }

  return $average;

}

function pasos_get_player_appearances( $player_detail ) {

  if ( array_key_exists( 'subs', $player_detail ) ) {
    $subs = $player_detail['subs'];
    if( $subs >= 1 ){
      $appearances = $player_detail['appearances'] . ' <span class="wpcm-sub-appearances">(' . $subs . ')</span>';
    } else {
      $appearances = $player_detail['appearances'];
    }
  } else {
    $appearances = $player_detail['appearances'];
  }

  return $appearances;
}

function pasos_divide($a, $b){
   if($b != 0){
     $result = $a/$b;
   }else{
     $result = 0;
   }
   return $result;
}

if (!function_exists('get_pasos_player_auto_stats')) {
  function get_pasos_player_auto_stats( $post_id = null, $team = null, $season_id = null ) {

    $stats_labels = pasos_get_player_stats_labels(); 
    

    $output = get_pasos_player_stats_empty_row();

    $args = array(
      'post_type' => 'zapas',
      'tax_query' => array(),
      'showposts' => -1,
      
    );

    if ( isset( $season_id ) ) {    
      $args['tax_query'][] = array(
        'taxonomy' => 'sezona',
        'terms' => $season_id,
        'field' => 'term_id'
      );
    }

     if ( isset( $team ) ) {      
      $args['tax_query'][] = array(
        'taxonomy' => 'tym',
        'terms' => $team,
        'field' => 'term_id'
      );
    }


    $matches = get_posts( $args );

    foreach( $matches as $match ) {

    
          $all_players = unserialize( get_post_meta( $match->ID, 'pasos_players', true ) );

          if ( is_array( $all_players ) ) {

                foreach( $all_players as $players ) {

                      if ( is_array( $players ) && array_key_exists( $post_id, $players ) ) {

                        $stats = $players[$post_id];
                        $output['appearances'] ++;

                        foreach( $stats as $key => $value ) {
                              if ( array_key_exists( $key, $stats_labels ) )  {
                                    if(isset($stats[ $key ])){ $output[ $key ] += $stats[ $key ];}
                              }
                        }
                      }

                }
          }
    }  
    return $output;
  }
}

function pasos_player_header_labels(){
   
   $pasos_player_header_labels = array( 
                                      "name" => "Jméno",                                      
                                      );

   return $pasos_player_header_labels;

}

//META boxes - NAME: start;

if (!function_exists('pasos_stats_value')) {
  function pasos_stats_value( $stats, $type, $index ) {
    echo (get_pasos_stats_value( $stats, $type, $index ));
  }
}

if (!function_exists('get_pasos_stats_value')) {
  function get_pasos_stats_value( $stats = array(), $type = 'manual', $index = 'goals' ) {

    if ( is_array( $stats ) ) {


      if ( array_key_exists( $type, $stats ) ) {

        if ( array_key_exists( $index, $stats[$type] ) ) {

          return (float)$stats[$type][$index];
        }
        return (float)$stats[$type][$index];
      }
    }

    return 0;
  }
}

function subval_sort($a,$subkey) {

  foreach($a as $k=>$v) {

    $b[$k] = strtolower($v[$subkey]);
  }

  if ($b != null) {

    asort($b);

    foreach($b as $key=>$val) {

      $c[] = $a[$key];
    }

    return $c;
  }

  return array();
}

if (!function_exists('pasos_array_filter_checked')) {
  function pasos_array_filter_checked( $value) {
    
    return ( array_key_exists( 'checked', $value ) );
  }
}

function pasos_get_sport_presets() {
  return apply_filters( 'wpcm_sports', array(
    'baseball' => array(
      'name' => __( 'Baseball', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => '',
            'slug' => '',
          ),
        ),
      ),
      'stats_labels' => array(
        'ab' => array(
          'name'    => __( 'At Bats', 'wp-club-manager' ),
          'label'   => _x( 'AB', 'At Bats', 'wp-club-manager' ),
        ),
        'h' => array(
          'name'    => __( 'Hits', 'wp-club-manager' ),
          'label'   => _x( 'H', 'Hits', 'wp-club-manager' ),
        ),
        'r' => array(
          'name'    => __( 'Runs', 'wp-club-manager' ),
          'label'   => _x( 'R', 'Runs', 'wp-club-manager' ),
        ),
        'er' => array(
          'name'    => __( 'Earned Runs', 'wp-club-manager' ),
          'label'   => _x( 'ER', 'Earned Runs', 'wp-club-manager' ),
        ),
        'hr' => array(
          'name'    => __( 'Home Runs', 'wp-club-manager' ),
          'label'   => _x( 'HR', 'Home Runs', 'wp-club-manager' ),
          ),
        '2b' => array(
          'name'    => __( 'Doubles', 'wp-club-manager' ),
          'label'   => _x( '2B', 'Doubles', 'wp-club-manager' ),
        ),
        '3b' => array(
          'name'    => __( 'Triples', 'wp-club-manager' ),
          'label'   => _x( '3B', 'Triples', 'wp-club-manager' ),
        ),
        'rbi' => array(
          'name'    => __( 'Runs Batted In', 'wp-club-manager' ),
          'label'   => _x( 'RBI', 'Runs Batted In', 'wp-club-manager' ),
        ),
        'bb' => array(
          'name'    => __( 'Bases on Bulk', 'wp-club-manager' ),
          'label'   => _x( 'BB', 'Bases on Bulk', 'wp-club-manager' ),
        ),
        'so' => array(
          'name'    => __( 'Strike Outs', 'wp-club-manager' ),
          'label'   => _x( 'SO', 'Strike Outs', 'wp-club-manager' ),
        ),
        'sb' => array(
          'name'    => __( 'Stolen Bases', 'wp-club-manager' ),
          'label'   => _x( 'SB', 'Stolen Bases', 'wp-club-manager' ),
        ),
        'cs' => array(
          'name'    => __( 'Caught Stealing', 'wp-club-manager' ),
          'label'   => _x( 'CS', 'Caught Stealing', 'wp-club-manager' ),
        ),
        'tc' => array(
          'name'    => __( 'Total Chances', 'wp-club-manager' ),
          'label'   => _x( 'TC', 'Total Chances', 'wp-club-manager' ),
        ),
        'po' => array(
          'name'    => __( 'Putouts', 'wp-club-manager' ),
          'label'   => _x( 'PO', 'Putouts', 'wp-club-manager' ),
          ),
        'a' => array(
          'name'    => __( 'Assists', 'wp-club-manager' ),
          'label'   => _x( 'A', 'Assists', 'wp-club-manager' ),
        ),
        'e' => array(
          'name'    => __( 'Errors', 'wp-club-manager' ),
          'label'   => _x( 'E', 'Errors', 'wp-club-manager' ),
        ),
        'dp' => array(
          'name'    => __( 'Double Plays', 'wp-club-manager' ),
          'label'   => _x( 'DP', 'Double Plays', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'pct' => array(
          'name'  => __( 'Win Percentage', 'wp-club-manager' ),
          'label' => _x( 'PCT', 'Win Percentage', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Runs For', 'wp-club-manager' ),
          'label' => _x( 'RF', 'Runs For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Runs Against', 'wp-club-manager' ),
          'label' => _x( 'RA', 'Runs Against', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Run Difference', 'wp-club-manager' ),
          'label' => _x( 'RD', 'Run Difference', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
    'basketball' => array(
      'name' => __( 'Basketball', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => 'Point Guard',
            'slug' => 'pointguard',
          ),
          array(
            'name' => 'Shooting Guard',
            'slug' => 'shootingguard',
          ),
          array(
            'name' => 'Small Forward',
            'slug' => 'smallforward',
          ),
          array(
            'name' => 'Power Forward',
            'slug' => 'powerforward',
          ),
          array(
            'name' => 'Center',
            'slug' => 'center',
          ),
        ),
      ),
      'stats_labels' => array(
        'min' => array(
          'name'    => __( 'Minutes', 'wp-club-manager' ),
          'label'   => _x( 'MIN', 'Minutes', 'wp-club-manager' ),
        ),
        'fgm' => array(
          'name'    => __( 'Field Goals Made', 'wp-club-manager' ),
          'label'   => _x( 'FGM', 'Field Goals Made', 'wp-club-manager' ),
        ),
        'fga' => array(
          'name'    => __( 'Field Goals Attempted', 'wp-club-manager' ),
          'label'   => _x( 'FGA', 'Field Goals Attempted', 'wp-club-manager' ),
        ),
        '3pm' => array(
          'name'    => __( '3 Points Made', 'wp-club-manager' ),
          'label'   => _x( '3PM', '3 Points Made', 'wp-club-manager' ),
        ),
        '3pa' => array(
          'name'    => __( '3 Points Attempted', 'wp-club-manager' ),
          'label'   => _x( '3PA', '3 Points Attempted', 'wp-club-manager' ),
          ),
        'ftm' => array(
          'name'    => __( 'Free Throws Made', 'wp-club-manager' ),
          'label'   => _x( 'FTM', 'Free Throws Made', 'wp-club-manager' ),
        ),
        'fta' => array(
          'name'    => __( 'Free Throws Attempted', 'wp-club-manager' ),
          'label'   => _x( 'FTA', 'Free Throws Attempted', 'wp-club-manager' ),
        ),
        'or' => array(
          'name'    => __( 'Offensive Rebounds', 'wp-club-manager' ),
          'label'   => _x( 'OR', 'Offensive Rebounds', 'wp-club-manager' ),
        ),
        'dr' => array(
          'name'    => __( 'Defensive Rebounds', 'wp-club-manager' ),
          'label'   => _x( 'DR', 'Defensive Rebounds', 'wp-club-manager' ),
        ),
        'reb' => array(
          'name'    => __( 'Rebounds', 'wp-club-manager' ),
          'label'   => _x( 'REB', 'Rebounds', 'wp-club-manager' ),
        ),
        'ast' => array(
          'name'    => __( 'Assists', 'wp-club-manager' ),
          'label'   => _x( 'AST', 'Assists', 'wp-club-manager' ),
        ),
        'blk' => array(
          'name'    => __( 'Blocks', 'wp-club-manager' ),
          'label'   => _x( 'BLK', 'Blocks', 'wp-club-manager' ),
        ),
        'stl' => array(
          'name'    => __( 'Steals', 'wp-club-manager' ),
          'label'   => _x( 'STL', 'Steals', 'wp-club-manager' ),
        ),
        'pf' => array(
          'name'    => __( 'Personal Fouls', 'wp-club-manager' ),
          'label'   => _x( 'PF', 'Personal Fouls', 'wp-club-manager' ),
          ),
        'to' => array(
          'name'    => __( 'Turnovers', 'wp-club-manager' ),
          'label'   => _x( 'TO', 'Turnovers', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'    => __( 'Points', 'wp-club-manager' ),
          'label'   => _x( 'PTS', 'Points', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'd' => array(
          'name'  => __( 'Draw', 'wp-club-manager' ),
          'label' => _x( 'D', 'Draw', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'otw' => array(
          'name'  => __( 'Overtime Wins', 'wp-club-manager' ),
          'label' => _x( 'OTW', 'Overtime Wins', 'wp-club-manager' ),
        ),
        'otl' => array(
          'name'  => __( 'Overtime Losses', 'wp-club-manager' ),
          'label' => _x( 'OTL', 'Overtime Losses', 'wp-club-manager' ),
        ),
        'pct' => array(
          'name'  => __( 'Win Percentage', 'wp-club-manager' ),
          'label' => _x( 'PCT', 'Win Percentage', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Points For', 'wp-club-manager' ),
          'label' => _x( 'PF', 'Points For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Points Against', 'wp-club-manager' ),
          'label' => _x( 'PA', 'Points Against', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Points Difference', 'wp-club-manager' ),
          'label' => _x( 'PD', 'Points Difference', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
    'floorball' => array(
      'name' => __( 'Floorball', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => 'Goalkeeper',
            'slug' => 'goalkeeper',
          ),
          array(
            'name' => 'Defender',
            'slug' => 'defender',
          ),
          array(
            'name' => 'Forward',
            'slug' => 'forward',
          ),
        ),
      ),
      'stats_labels' => array(
        'g' => array(
          'name'    => __( 'Goals', 'wp-club-manager' ),
          'label'   => _x( 'G', 'Goals', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'    => __( 'Assists', 'wp-club-manager' ),
          'label'   => _x( 'A', 'Assists', 'wp-club-manager' ),
        ),
        'plusminus' => array(
          'name'    => __( 'Plus/Minus Rating', 'wp-club-manager' ),
          'label'   => _x( '+/-', 'Plus/Minus Rating', 'wp-club-manager' ),
          ),
        'sog' => array(
          'name'    => __( 'Shots on Goal', 'wp-club-manager' ),
          'label'   => _x( 'SOG', 'Shots on Goal', 'wp-club-manager' ),
        ),
        'pim' => array(
          'name'    => __( 'Penalty Minutes', 'wp-club-manager' ),
          'label'   => _x( 'PM', 'Penalty Minutes', 'wp-club-manager' ),
        ),
        'redcards' => array(
          'name'    => __( 'Red Cards', 'wp-club-manager' ),
          'label'   => _x( 'RC', 'Red Cards', 'wp-club-manager' ),
        ),
        'sav' => array(
          'name'    => __( 'Saves', 'wp-club-manager' ),
          'label'   => _x( 'SAV', 'Saves', 'wp-club-manager' ),
        ),
        'ga' => array(
          'name'    => __( 'Goals Against', 'wp-club-manager' ),
          'label'   => _x( 'GA', 'Goals Against', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'd' => array(
          'name'  => __( 'Draw', 'wp-club-manager' ),
          'label' => _x( 'D', 'Draw', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Goals For', 'wp-club-manager' ),
          'label' => _x( 'F', 'Goals For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Goals Against', 'wp-club-manager' ),
          'label' => _x( 'A', 'Goals Against', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Goal Difference', 'wp-club-manager' ),
          'label' => _x( 'GD', 'Goal Difference', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
    'football' => array(
      'name' => __( 'American Football', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => 'Quarterback',
            'slug' => 'quarterback',
          ),
          array(
            'name' => 'Running Back',
            'slug' => 'runningback',
          ),
          array(
            'name' => 'Wide Receiver',
            'slug' => 'widereceiver',
          ),
          array(
            'name' => 'Tight End',
            'slug' => 'tightend',
          ),
          array(
            'name' => 'Defensive Lineman',
            'slug' => 'defensivelineman',
          ),
          array(
            'name' => 'Linebacker',
            'slug' => 'linebacker',
          ),
          array(
            'name' => 'Defensive Back',
            'slug' => 'defensiveback',
          ),
          array(
            'name' => 'Kickoff Kicker',
            'slug' => 'kickoffkicker',
          ),
          array(
            'name' => 'Kick Returner',
            'slug' => 'kickreturner',
          ),
          array(
            'name' => 'Punter',
            'slug' => 'punter',
          ),
          array(
            'name' => 'Punt Returner',
            'slug' => 'puntreturner',
          ),
          array(
            'name' => 'Field Goal Kicker',
            'slug' => 'fieldgoalkicker',
          ),
        ),
      ),
      'stats_labels' => array(
        'pa_cmp' => array(
          'name'    => __( 'Pass Completions', 'wp-club-manager' ),
          'label'   => _x( 'CMP', 'Pass Completions', 'wp-club-manager' ),
        ),
        'pa_yds' => array(
          'name'    => __( 'Passing Yards', 'wp-club-manager' ),
          'label'   => _x( 'YDS', 'Passing Yards', 'wp-club-manager' ),
        ),
        'sc_pass' => array(
          'name'    => __( 'Passing Touchdowns', 'wp-club-manager' ),
          'label'   => _x( 'PASS', 'Passing Touchdowns', 'wp-club-manager' ),
        ),
        'pa_int' => array(
          'name'    => __( 'Passing Interceptions', 'wp-club-manager' ),
          'label'   => _x( 'INT', 'Passing Interceptions', 'wp-club-manager' ),
        ),
        'ru_yds' => array(
          'name'    => __( 'Rushing Yards', 'wp-club-manager' ),
          'label'   => _x( 'YDS', 'Rushing Yards', 'wp-club-manager' ),
          ),
        'sc_rush' => array(
          'name'    => __( 'Rushing Touchdowns', 'wp-club-manager' ),
          'label'   => _x( 'RUSH', 'Rushing Touchdowns', 'wp-club-manager' ),
        ),
        're_rec' => array(
          'name'    => __( 'Receptions', 'wp-club-manager' ),
          'label'   => _x( 'REC', 'Receptions', 'wp-club-manager' ),
        ),
        're_yds' => array(
          'name'    => __( 'Receiving Yards', 'wp-club-manager' ),
          'label'   => _x( 'YDS', 'Receiving Yards', 'wp-club-manager' ),
        ),
        'sc_rec' => array(
          'name'    => __( 'Receiving Touchdowns', 'wp-club-manager' ),
          'label'   => _x( 'REC', 'Receiving Touchdowns', 'wp-club-manager' ),
        ),
        'de_total' => array(
          'name'    => __( 'Total Tackles', 'wp-club-manager' ),
          'label'   => _x( 'TOTAL', 'Total Tackles', 'wp-club-manager' ),
        ),
        'de_sack' => array(
          'name'    => __( 'Sacks', 'wp-club-manager' ),
          'label'   => _x( 'SACK', 'Sacks', 'wp-club-manager' ),
        ),
        'de_ff' => array(
          'name'    => __( 'Fumbles', 'wp-club-manager' ),
          'label'   => _x( 'FF', 'Fumbles', 'wp-club-manager' ),
        ),
        'de_int' => array(
          'name'    => __( 'Interceptions', 'wp-club-manager' ),
          'label'   => _x( 'INT', 'Interceptions', 'wp-club-manager' ),
        ),
        'de_kb' => array(
          'name'    => __( 'Blocked Kicks', 'wp-club-manager' ),
          'label'   => _x( 'KB', 'Blocked Kicks', 'wp-club-manager' ),
          ),
        'sc_td' => array(
          'name'    => __( 'Touchdowns', 'wp-club-manager' ),
          'label'   => _x( 'T', 'Touchdowns', 'wp-club-manager' ),
        ),
        'sc_2pt' => array(
          'name'    => __( '2 Point Conversions', 'wp-club-manager' ),
          'label'   => _x( '2PT', '2 Point Conversions', 'wp-club-manager' ),
        ),
        'sc_fg' => array(
          'name'    => __( 'Field Goals', 'wp-club-manager' ),
          'label'   => _x( 'FG', 'Field Goals', 'wp-club-manager' ),
        ),
        'sc_pat' => array(
          'name'    => __( 'Extra Points', 'wp-club-manager' ),
          'label'   => _x( 'PAT', 'Extra Points', 'wp-club-manager' ),
        ),
        'sc_pts' => array(
          'name'    => __( 'Total Points', 'wp-club-manager' ),
          'label'   => _x( 'PTS', 'Total Points', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'd' => array(
          'name'  => __( 'Tie', 'wp-club-manager' ),
          'label' => _x( 'T', 'Tie', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Points For', 'wp-club-manager' ),
          'label' => _x( 'PF', 'Points For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Points Against', 'wp-club-manager' ),
          'label' => _x( 'PA', 'Points Against', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Points Difference', 'wp-club-manager' ),
          'label' => _x( 'PD', 'Points Difference', 'wp-club-manager' ),
        ),
        'pct' => array(
          'name'  => __( 'Win Percentage', 'wp-club-manager' ),
          'label' => _x( 'PCT', 'Win Percentage', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
    'footy' => array(
      'name' => __( 'Australian Rules Football', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => 'Full Back',
            'slug' => 'full-back',
          ),
          array(
            'name' => 'Back Pocket',
            'slug' => 'back-pocket',
          ),
          array(
            'name' => 'Centre Half-Back',
            'slug' => 'centre-half-back',
          ),
          array(
            'name' => 'Half-Back Flank',
            'slug' => 'half-back-flank',
          ),
          array(
            'name' => 'Centre Half-Forward',
            'slug' => 'centre-half-forward',
          ),
          array(
            'name' => 'Half-Forward Flank',
            'slug' => 'half-forward-flank',
          ),
          array(
            'name' => 'Full Forward',
            'slug' => 'full-forward',
          ),
          array(
            'name' => 'Forward Pocket',
            'slug' => 'forward-pocket',
          ),
          array(
            'name' => 'Follower',
            'slug' => 'follower',
          ),
          array(
            'name' => 'Inside Midfield',
            'slug' => 'inside-midfield',
          ),
          array(
            'name' => 'Outside Midfield',
            'slug' => 'outside-midfield',
          ),
        ),
      ),
      'stats_labels' => array(
        'k' => array(
          'name'    => __( 'Kicks', 'wp-club-manager' ),
          'label'   => _x( 'K', 'Kicks', 'wp-club-manager' ),
        ),
        'hb' => array(
          'name'    => __( 'Handballs', 'wp-club-manager' ),
          'label'   => _x( 'HB', 'Handballs', 'wp-club-manager' ),
        ),
        'd' => array(
          'name'    => __( 'Disposals', 'wp-club-manager' ),
          'label'   => _x( 'D', 'Disposals', 'wp-club-manager' ),
        ),
        'cp' => array(
          'name'    => __( 'Contested Possesion', 'wp-club-manager' ),
          'label'   => _x( 'CP', 'Contested Possesion', 'wp-club-manager' ),
        ),
        'm' => array(
          'name'    => __( 'Marks', 'wp-club-manager' ),
          'label'   => _x( 'M', 'Marks', 'wp-club-manager' ),
          ),
        'cm' => array(
          'name'    => __( 'Contested Marks', 'wp-club-manager' ),
          'label'   => _x( 'CM', 'Contested Marks', 'wp-club-manager' ),
        ),
        'ff' => array(
          'name'    => __( 'Frees For', 'wp-club-manager' ),
          'label'   => _x( 'FF', 'Frees For', 'wp-club-manager' ),
        ),
        'fa' => array(
          'name'    => __( 'Frees Against', 'wp-club-manager' ),
          'label'   => _x( 'FA', 'Frees Against', 'wp-club-manager' ),
        ),
        'clg' => array(
          'name'    => __( 'Clangers', 'wp-club-manager' ),
          'label'   => _x( 'C', 'Clangers', 'wp-club-manager' ),
        ),
        'tkl' => array(
          'name'    => __( 'Tackles', 'wp-club-manager' ),
          'label'   => _x( 'T', 'Tackles', 'wp-club-manager' ),
        ),
        'i50' => array(
          'name'    => __( 'Inside 50s', 'wp-club-manager' ),
          'label'   => _x( 'I50', 'Inside 50s', 'wp-club-manager' ),
        ),
        'r50' => array(
          'name'    => __( 'Rebound 50s', 'wp-club-manager' ),
          'label'   => _x( 'R50', 'Rebound 50s', 'wp-club-manager' ),
        ),
        '1pct' => array(
          'name'    => __( 'One-Percenters', 'wp-club-manager' ),
          'label'   => _x( '1PCT', 'One-Percenters', 'wp-club-manager' ),
        ),
        'ho' => array(
          'name'    => __( 'Hit Outs', 'wp-club-manager' ),
          'label'   => _x( 'HO', 'Hit Outs', 'wp-club-manager' ),
          ),
        'g' => array(
          'name'    => __( 'Goals', 'wp-club-manager' ),
          'label'   => _x( 'G', 'Goals', 'wp-club-manager' ),
        ),
        'b' => array(
          'name'    => __( 'Behinds', 'wp-club-manager' ),
          'label'   => _x( 'B', 'Behinds', 'wp-club-manager' ),
        ),
        'yellowcards' => array(
          'name'    => __( 'Yellow Cards', 'wp-club-manager' ),
          'label'   => _x( 'YC', 'Yellow Cards', 'wp-club-manager' ),
        ),
        'redcards' => array(
          'name'    => __( 'Red Cards', 'wp-club-manager' ),
          'label'   => _x( 'RC', 'Red Cards', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'd' => array(
          'name'  => __( 'Draw', 'wp-club-manager' ),
          'label' => _x( 'D', 'Draw', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Points For', 'wp-club-manager' ),
          'label' => _x( 'F', 'Points For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Points Against', 'wp-club-manager' ),
          'label' => _x( 'A', 'Points Against', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Points Difference', 'wp-club-manager' ),
          'label' => _x( 'PD', 'Points Difference', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
    'gaelic' => array(
      'name' => __( 'Gaelic Football / Hurling', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => 'Goalkeeper',
            'slug' => 'goalkeeper',
          ),
          array(
            'name' => 'Defender',
            'slug' => 'defender',
          ),
          array(
            'name' => 'Midfielder',
            'slug' => 'midfielder',
          ),
          array(
            'name' => 'Forward',
            'slug' => 'forward',
          ),
        ),
      ),
      'stats_labels' => array(
        'g' => array(
          'name'    => __( 'Goals', 'wp-club-manager' ),
          'label'   => _x( 'G', 'Goals', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'    => __( 'Points', 'wp-club-manager' ),
          'label'   => _x( 'P', 'Points', 'wp-club-manager' ),
        ),
        'gff' => array(
          'name'    => __( 'Goals From Frees', 'wp-club-manager' ),
          'label'   => _x( 'GFF', 'Goals From Frees', 'wp-club-manager' ),
          ),
        'sog' => array(
          'name'    => __( 'Points From Frees', 'wp-club-manager' ),
          'label'   => _x( 'PFF', 'Points From Frees', 'wp-club-manager' ),
        ),
        'yellowcards' => array(
          'name'    => __( 'Yellow Cards', 'wp-club-manager' ),
          'label'   => _x( 'YC', 'Yellow Cards', 'wp-club-manager' ),
        ),
        'blackcards' => array(
          'name'    => __( 'Black Cards', 'wp-club-manager' ),
          'label'   => _x( 'BC', 'Black Cards', 'wp-club-manager' ),
        ),
        'redcards' => array(
          'name'    => __( 'Red Cards', 'wp-club-manager' ),
          'label'   => _x( 'RC', 'Red Cards', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'd' => array(
          'name'  => __( 'Draw', 'wp-club-manager' ),
          'label' => _x( 'D', 'Draw', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Goals For', 'wp-club-manager' ),
          'label' => _x( 'F', 'Goals For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Goals Against', 'wp-club-manager' ),
          'label' => _x( 'A', 'Goals Against', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Goal Difference', 'wp-club-manager' ),
          'label' => _x( 'GD', 'Goal Difference', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
    'handball' => array(
      'name' => __( 'Handball', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => 'Goalkeeper',
            'slug' => 'goalkeeper',
          ),
          array(
            'name' => 'Left Wing',
            'slug' => 'left-wing',
          ),
          array(
            'name' => 'Left Back',
            'slug' => 'left-back',
          ),
          array(
            'name' => 'Center',
            'slug' => 'center',
          ),
          array(
            'name' => 'Right Wing',
            'slug' => 'right-wing',
          ),
          array(
            'name' => 'Right Back',
            'slug' => 'right-back',
          ),
          array(
            'name' => 'Pivot',
            'slug' => 'pivot',
          ),
        ),
      ),
      'stats_labels' => array(
        'goals' => array(
          'name'    => __( 'Goals', 'wp-club-manager' ),
          'label'   => _x( 'GLS', 'Goals', 'wp-club-manager' ),
        ),
        '2min' => array(
          'name'    => __( '2 Minute Suspension', 'wp-club-manager' ),
          'label'   => _x( '2MIN', '2 Minute Suspension', 'wp-club-manager' ),
        ),
        'yellowcards' => array(
          'name'    => __( 'Yellow Cards', 'wp-club-manager' ),
          'label'   => _x( 'YC', 'Yellow Cards', 'wp-club-manager' ),
        ),
        'redcards' => array(
          'name'    => __( 'Red Cards', 'wp-club-manager' ),
          'label'   => _x( 'RC', 'Red Cards', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'd' => array(
          'name'  => __( 'Draw', 'wp-club-manager' ),
          'label' => _x( 'D', 'Draw', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Goals For', 'wp-club-manager' ),
          'label' => _x( 'F', 'Goals For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Goals Against', 'wp-club-manager' ),
          'label' => _x( 'A', 'Goals Against', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Goal Difference', 'wp-club-manager' ),
          'label' => _x( 'GD', 'Goal Difference', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
    'hockey_field' => array(
      'name' => __( 'Field Hockey', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => 'Goalie',
            'slug' => 'goalie',
          ),
          array(
            'name' => 'Defence',
            'slug' => 'defence',
          ),
          array(
            'name' => 'Midfield',
            'slug' => 'midfield',
          ),
          array(
            'name' => 'Forward',
            'slug' => 'forward',
          ),
        ),
      ),
      'stats_labels' => array(
        'gls' => array(
          'name'    => __( 'Goals', 'wp-club-manager' ),
          'label'   => _x( 'G', 'Goals', 'wp-club-manager' ),
        ),
        'ass' => array(
          'name'    => __( 'Assists', 'wp-club-manager' ),
          'label'   => _x( 'A', 'Assists', 'wp-club-manager' ),
        ),
        'sho' => array(
          'name'    => __( 'Shots', 'wp-club-manager' ),
          'label'   => _x( 'SH', 'Shots', 'wp-club-manager' ),
          ),
        'sog' => array(
          'name'    => __( 'Shots on Goal', 'wp-club-manager' ),
          'label'   => _x( 'SOG', 'Shots on Goal', 'wp-club-manager' ),
        ),
        'sav' => array(
          'name'    => __( 'Saves', 'wp-club-manager' ),
          'label'   => _x( 'SAV', 'Saves', 'wp-club-manager' ),
        ),
        'greencards' => array(
          'name'    => __( 'Green Cards', 'wp-club-manager' ),
          'label'   => _x( 'GC', 'Green Cards', 'wp-club-manager' ),
        ),
        'yellowcards' => array(
          'name'    => __( 'Yellow Cards', 'wp-club-manager' ),
          'label'   => _x( 'YC', 'Yellow Cards', 'wp-club-manager' ),
        ),
        'redcards' => array(
          'name'    => __( 'Red Cards', 'wp-club-manager' ),
          'label'   => _x( 'RC', 'Red Cards', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'd' => array(
          'name'  => __( 'Draw', 'wp-club-manager' ),
          'label' => _x( 'D', 'Draw', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Goals For', 'wp-club-manager' ),
          'label' => _x( 'F', 'Goals For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Goals Against', 'wp-club-manager' ),
          'label' => _x( 'A', 'Goals Against', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Goal Difference', 'wp-club-manager' ),
          'label' => _x( 'GD', 'Goal Difference', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
    'hockey' => array(
      'name' => __( 'Ice Hockey', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => 'Goalie',
            'slug' => 'goalie',
          ),
          array(
            'name' => 'Defense',
            'slug' => 'defense',
          ),
          array(
            'name' => 'Center',
            'slug' => 'center',
          ),
          array(
            'name' => 'Right Wing',
            'slug' => 'right-wing',
          ),
          array(
            'name' => 'Left Wing',
            'slug' => 'left-wing',
          ),
        ),
      ),
      'stats_labels' => array(
        'g' => array(
          'name'    => __( 'Goals', 'wp-club-manager' ),
          'label'   => _x( 'G', 'Goals', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'    => __( 'Assists', 'wp-club-manager' ),
          'label'   => _x( 'A', 'Assists', 'wp-club-manager' ),
        ),
        'plusminus' => array(
          'name'    => __( 'Plus/Minus Rating', 'wp-club-manager' ),
          'label'   => _x( '+/-', 'Plus/Minus Rating', 'wp-club-manager' ),
          ),
        'sog' => array(
          'name'    => __( 'Shots On Goal', 'wp-club-manager' ),
          'label'   => _x( 'SOG', 'Shots On Goal', 'wp-club-manager' ),
        ),
        'ms' => array(
          'name'    => __( 'Missed Shots', 'wp-club-manager' ),
          'label'   => _x( 'MS', 'Missed Shots', 'wp-club-manager' ),
        ),
        'bs' => array(
          'name'    => __( 'Blocked Shots', 'wp-club-manager' ),
          'label'   => _x( 'BS', 'Blocked Shots', 'wp-club-manager' ),
        ),
        'pim' => array(
          'name'    => __( 'Penalty Minutes', 'wp-club-manager' ),
          'label'   => _x( 'PIM', 'Penalty Minutes', 'wp-club-manager' ),
        ),
        'ht' => array(
          'name'    => __( 'Hits', 'wp-club-manager' ),
          'label'   => _x( 'HT', 'Hits', 'wp-club-manager' ),
        ),
        'fw' => array(
          'name'    => __( 'Faceoffs Won', 'wp-club-manager' ),
          'label'   => _x( 'FW', 'Faceoffs Won', 'wp-club-manager' ),
        ),
        'fl' => array(
          'name'    => __( 'Faceoffs Lost', 'wp-club-manager' ),
          'label'   => _x( 'FL', 'Faceoffs Lost', 'wp-club-manager' ),
        ),
        'sav' => array(
          'name'    => __( 'Saves', 'wp-club-manager' ),
          'label'   => _x( 'SAV', 'Saves', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'otw' => array(
          'name'  => __( 'Overtime Wins', 'wp-club-manager' ),
          'label' => _x( 'OTW', 'Overtime Wins', 'wp-club-manager' ),
        ),
        'otl' => array(
          'name'  => __( 'Overtime Losses', 'wp-club-manager' ),
          'label' => _x( 'OTL', 'Overtime Losses', 'wp-club-manager' ),
        ),
        'pct' => array(
          'name'  => __( 'Win Percentage', 'wp-club-manager' ),
          'label' => _x( 'PCT', 'Win Percentage', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Goals For', 'wp-club-manager' ),
          'label' => _x( 'F', 'Goals For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Goals Against', 'wp-club-manager' ),
          'label' => _x( 'A', 'Goals Against', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Goal Difference', 'wp-club-manager' ),
          'label' => _x( 'GD', 'Goal Difference', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
    'lacrosse' => array(
      'name' => __( 'Lacrosse', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => 'Goalie',
            'slug' => 'goalie',
          ),
          array(
            'name' => 'Defender',
            'slug' => 'defender',
          ),
          array(
            'name' => 'Midfielder',
            'slug' => 'midfielder',
          ),
          array(
            'name' => 'Attack',
            'slug' => 'attack',
          ),
        ),
      ),
      'stats_labels' => array(
        'goals' => array(
          'name'    => __( 'Goals', 'wp-club-manager' ),
          'label'   => _x( 'GLS', 'Goals', 'wp-club-manager' ),
        ),
        'assists' => array(
          'name'    => __( 'Assists', 'wp-club-manager' ),
          'label'   => _x( 'AST', 'Assists', 'wp-club-manager' ),
        ),
        'groundballs' => array(
          'name'    => __( 'Ground Balls', 'wp-club-manager' ),
          'label'   => _x( 'GRB', 'Ground Balls', 'wp-club-manager' ),
          ),
        'saves' => array(
          'name'    => __( 'Saves', 'wp-club-manager' ),
          'label'   => _x( 'SAV', 'Saves', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'd' => array(
          'name'  => __( 'Draw', 'wp-club-manager' ),
          'label' => _x( 'D', 'Draw', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Goals For', 'wp-club-manager' ),
          'label' => _x( 'F', 'Goals For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Goals Against', 'wp-club-manager' ),
          'label' => _x( 'A', 'Played', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Goal Difference', 'wp-club-manager' ),
          'label' => _x( 'GD', 'Goal Difference', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
    'netball' => array(
      'name' => __( 'Netball', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => 'Goal Shooter',
            'slug' => 'goal-shooter',
          ),
          array(
            'name' => 'Goal Attack',
            'slug' => 'goal-attack',
          ),
          array(
            'name' => 'Wing Attack',
            'slug' => 'wing-attack',
          ),
          array(
            'name' => 'Centre',
            'slug' => 'centre',
          ),
          array(
            'name' => 'Wing Defence',
            'slug' => 'wing-defence',
          ),
          array(
            'name' => 'Goal Defence',
            'slug' => 'goal-defence',
          ),
          array(
            'name' => 'Goal Keeper',
            'slug' => 'goal-keeper',
          ),
        ),
      ),
      'stats_labels' => array(
        'g' => array(
          'name'    => __( 'Goals', 'wp-club-manager' ),
          'label'   => _x( 'GLS', 'Goals', 'wp-club-manager' ),
        ),
        'gatt' => array(
          'name'    => __( 'Goal Attempts', 'wp-club-manager' ),
          'label'   => _x( 'ATT', 'Goal Attempts', 'wp-club-manager' ),
        ),
        'gass' => array(
          'name'    => __( 'Goal Assists', 'wp-club-manager' ),
          'label'   => _x( 'AST', 'Goal Assists', 'wp-club-manager' ),
        ),
        'rbs' => array(
          'name'    => __( 'Rebounds', 'wp-club-manager' ),
          'label'   => _x( 'REB', 'Rebounds', 'wp-club-manager' ),
          ),
        'cpr' => array(
          'name'    => __( 'Center Pass Receives', 'wp-club-manager' ),
          'label'   => _x( 'CPR', 'Center Pass Receives', 'wp-club-manager' ),
        ),
        'int' => array(
          'name'    => __( 'Interceptions', 'wp-club-manager' ),
          'label'   => _x( 'INT', 'Interceptions', 'wp-club-manager' ),
        ),
        'def' => array(
          'name'    => __( 'Deflections', 'wp-club-manager' ),
          'label'   => _x( 'DEF', 'Deflections', 'wp-club-manager' ),
        ),
        'pen' => array(
          'name'    => __( 'Penalties', 'wp-club-manager' ),
          'label'   => _x( 'PEN', 'Penalties', 'wp-club-manager' ),
        ),
        'to' => array(
          'name'    => __( 'Turnovers', 'wp-club-manager' ),
          'label'   => _x( 'TO', 'Turnovers', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'd' => array(
          'name'  => __( 'Draw', 'wp-club-manager' ),
          'label' => _x( 'D', 'Draw', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Goals For', 'wp-club-manager' ),
          'label' => _x( 'F', 'Goals For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Goals Against', 'wp-club-manager' ),
          'label' => _x( 'A', 'Goals Against', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Goals Difference', 'wp-club-manager' ),
          'label' => _x( 'PD', 'Goals Difference', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
    'rugby_league' => array(
      'name' => __( 'Rugby League', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => 'Scrum Half',
            'slug' => 'scrum-half',
          ),
          array(
            'name' => 'Stand Off',
            'slug' => 'stand-off',
          ),
          array(
            'name' => 'Centre',
            'slug' => 'centre',
          ),
          array(
            'name' => 'Winger',
            'slug' => 'winger',
          ),
          array(
            'name' => 'Full Back',
            'slug' => 'full-back',
          ),
          array(
            'name' => 'Prop',
            'slug' => 'prop',
          ),
          array(
            'name' => 'Hooker',
            'slug' => 'hooker',
          ),
          array(
            'name' => '2nd Row',
            'slug' => 'second-row',
          ),
          array(
            'name' => 'Lock',
            'slug' => 'lock',
          ),
        ),
      ),
      'stats_labels' => array(
        't' => array(
          'name'    => __( 'Tries', 'wp-club-manager' ),
          'label'   => _x( 'TR', 'Tries', 'wp-club-manager' ),
        ),
        'c' => array(
          'name'    => __( 'Conversions', 'wp-club-manager' ),
          'label'   => _x( 'CON', 'Conversions', 'wp-club-manager' ),
        ),
        'p' => array(
          'name'    => __( 'Penalties', 'wp-club-manager' ),
          'label'   => _x( 'PEN', 'Penalties', 'wp-club-manager' ),
          ),
        'dg' => array(
          'name'    => __( 'Drop Goals', 'wp-club-manager' ),
          'label'   => _x( 'DG', 'Drop Goals', 'wp-club-manager' ),
        ),
        'yellowcards' => array(
          'name'    => __( 'Yellow Cards', 'wp-club-manager' ),
          'label'   => _x( 'YC', 'Yellow Cards', 'wp-club-manager' ),
        ),
        'redcards' => array(
          'name'    => __( 'Red Cards', 'wp-club-manager' ),
          'label'   => _x( 'RC', 'Red Cards', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'd' => array(
          'name'  => __( 'Draw', 'wp-club-manager' ),
          'label' => _x( 'D', 'Draw', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Points For', 'wp-club-manager' ),
          'label' => _x( 'PF', 'Points For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Points Against', 'wp-club-manager' ),
          'label' => _x( 'PA', 'Points Against', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Points Difference', 'wp-club-manager' ),
          'label' => _x( 'PD', 'Points Difference', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
    'rugby' => array(
      'name' => __( 'Rugby Union', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => 'Scrum Half',
            'slug' => 'scrum-half',
          ),
          array(
            'name' => 'Fly Half',
            'slug' => 'fly-half',
          ),
          array(
            'name' => 'Centre',
            'slug' => 'centre',
          ),
          array(
            'name' => 'Winger',
            'slug' => 'winger',
          ),
          array(
            'name' => 'Full Back',
            'slug' => 'full-back',
          ),
          array(
            'name' => 'Prop',
            'slug' => 'prop',
          ),
          array(
            'name' => 'Hooker',
            'slug' => 'hooker',
          ),
          array(
            'name' => 'Lock',
            'slug' => 'lock',
          ),
          array(
            'name' => 'Flanker',
            'slug' => 'flanker',
          ),
          array(
            'name' => 'No. 8',
            'slug' => 'no-8',
          ),
        ),
      ),
      'stats_labels' => array(
        't' => array(
          'name'    => __( 'Tries', 'wp-club-manager' ),
          'label'   => _x( 'TR', 'Tries', 'wp-club-manager' ),
        ),
        'c' => array(
          'name'    => __( 'Conversions', 'wp-club-manager' ),
          'label'   => _x( 'CON', 'Conversions', 'wp-club-manager' ),
        ),
        'p' => array(
          'name'    => __( 'Penalties', 'wp-club-manager' ),
          'label'   => _x( 'PEN', 'Penalties', 'wp-club-manager' ),
          ),
        'dg' => array(
          'name'    => __( 'Drop Goals', 'wp-club-manager' ),
          'label'   => _x( 'DG', 'Drop Goals', 'wp-club-manager' ),
        ),
        'yellowcards' => array(
          'name'    => __( 'Yellow Cards', 'wp-club-manager' ),
          'label'   => _x( 'YC', 'Yellow Cards', 'wp-club-manager' ),
        ),
        'redcards' => array(
          'name'    => __( 'Red Cards', 'wp-club-manager' ),
          'label'   => _x( 'RC', 'Red Cards', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'd' => array(
          'name'  => __( 'Draw', 'wp-club-manager' ),
          'label' => _x( 'D', 'Draw', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Points For', 'wp-club-manager' ),
          'label' => _x( 'PF', 'Points For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Points Against', 'wp-club-manager' ),
          'label' => _x( 'PA', 'Points Against', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Points Difference', 'wp-club-manager' ),
          'label' => _x( 'PD', 'Points Difference', 'wp-club-manager' ),
        ),
        'b' => array(
          'name'  => __( 'Bonus Points', 'wp-club-manager' ),
          'label' => _x( 'B', 'Bonus Points', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
    'soccer' => array(
      'name' => __( 'Football (Soccer)', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => 'Goalkeeper',
            'slug' => 'goalkeeper',
          ),
          array(
            'name' => 'Defender',
            'slug' => 'defender',
          ),
          array(
            'name' => 'Midfielder',
            'slug' => 'midfielder',
          ),
          array(
            'name' => 'Forward',
            'slug' => 'forward',
          ),
        ),
      ),
      'stats_labels' => array(
        'goals' => array(
          'name'    => __( 'Goals', 'wp-club-manager' ),
          'label'   => _x( 'GLS', 'Goals', 'wp-club-manager' ),
        ),
        'assists' => array(
          'name'    => __( 'Assists', 'wp-club-manager' ),
          'label'   => _x( 'AST', 'Assists', 'wp-club-manager' ),
        ),
        'penalties' => array(
          'name'    => __( 'Penalties', 'wp-club-manager' ),
          'label'   => _x( 'PENS', 'Penalties', 'wp-club-manager' ),
          ),
        'og' => array(
          'name'    => __( 'Own Goals', 'wp-club-manager' ),
          'label'   => _x( 'OG', 'Own Goals', 'wp-club-manager' ),
        ),
        'cs' => array(
          'name'    => __( 'Clean Sheets', 'wp-club-manager' ),
          'label'   => _x( 'CS', 'Clean Sheets', 'wp-club-manager' ),
        ),
        'yellowcards' => array(
          'name'    => __( 'Yellow Cards', 'wp-club-manager' ),
          'label'   => _x( 'YC', 'Yellow Cards', 'wp-club-manager' ),
        ),
        'redcards' => array(
          'name'    => __( 'Red Cards', 'wp-club-manager' ),
          'label'   => _x( 'RC', 'Red Cards', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'd' => array(
          'name'  => __( 'Draw', 'wp-club-manager' ),
          'label' => _x( 'D', 'Draw', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Goals For', 'wp-club-manager' ),
          'label' => _x( 'F', 'Goals For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Goals Against', 'wp-club-manager' ),
          'label' => _x( 'A', 'Played', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Goal Difference', 'wp-club-manager' ),
          'label' => _x( 'GD', 'Goal Difference', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
    'volleyball' => array(
      'name' => __( 'Volleyball', 'wp-club-manager' ),
      'terms' => array(
        'wpcm_position' => array(
          array(
            'name' => 'Outside Hitter',
            'slug' => 'outside-hitter',
          ),
          array(
            'name' => 'Middle Blocker',
            'slug' => 'middle-blocker',
          ),
          array(
            'name' => 'Setter',
            'slug' => 'setter',
          ),
          array(
            'name' => 'Opposite',
            'slug' => 'opposite',
          ),
          array(
            'name' => 'Defensive Specialist',
            'slug' => 'defensive-specialist',
          ),
          array(
            'name' => 'Libero',
            'slug' => 'libero',
          ),
        ),
      ),
      'stats_labels' => array(
        'ace' => array(
          'name'    => __( 'Aces', 'wp-club-manager' ),
          'label'   => _x( 'ACE', 'Aces', 'wp-club-manager' ),
        ),
        'kill' => array(
          'name'    => __( 'Kills', 'wp-club-manager' ),
          'label'   => _x( 'KILL', 'Kills', 'wp-club-manager' ),
        ),
        'blk' => array(
          'name'    => __( 'Blocks', 'wp-club-manager' ),
          'label'   => _x( 'BLK', 'Blocks', 'wp-club-manager' ),
          ),
        'bass' => array(
          'name'    => __( 'Block Assists', 'wp-club-manager' ),
          'label'   => _x( 'BA', 'Block Assists', 'wp-club-manager' ),
        ),
        'sass' => array(
          'name'    => __( 'Setting Assists', 'wp-club-manager' ),
          'label'   => _x( 'SA', 'Setting Assists', 'wp-club-manager' ),
        ),
        'dig' => array(
          'name'    => __( 'Digs', 'wp-club-manager' ),
          'label'   => _x( 'DIG', 'Digs', 'wp-club-manager' ),
        ),
        'rating' => array(
          'name'    => __( 'Rating', 'wp-club-manager' ),
          'label'   => _x( 'RAT', 'Rating', 'wp-club-manager' ),
        ),
        'mvp' => array(
          'name'    => __( 'Player of Match', 'wp-club-manager' ),
          'label'   => _x( 'POM', 'Player of Match', 'wp-club-manager' ),
        ),
      ),
      'standings_columns' => array(
        'p' => array(
          'name'  => __( 'Played', 'wp-club-manager' ),
          'label' => _x( 'P', 'Played', 'wp-club-manager' ),
        ),
        'w' => array(
          'name'  => __( 'Won', 'wp-club-manager' ),
          'label' => _x( 'W', 'Won', 'wp-club-manager' ),
        ),
        'l' => array(
          'name'  => __( 'Lost', 'wp-club-manager' ),
          'label' => _x( 'L', 'Lost', 'wp-club-manager' ),
        ),
        'f' => array(
          'name'  => __( 'Sets For', 'wp-club-manager' ),
          'label' => _x( 'SF', 'Sets For', 'wp-club-manager' ),
        ),
        'a' => array(
          'name'  => __( 'Sets Against', 'wp-club-manager' ),
          'label' => _x( 'SA', 'Sets Against', 'wp-club-manager' ),
        ),
        'gd' => array(
          'name'  => __( 'Set Difference', 'wp-club-manager' ),
          'label' => _x( 'SD', 'Set Difference', 'wp-club-manager' ),
        ),
        'pts' => array(
          'name'  => __( 'Points', 'wp-club-manager' ),
          'label' => _x( 'Pts', 'Points', 'wp-club-manager' ),
        ),
      ),
    ),
  ));
}

function pasos_exclude_keys() {

  $exclude_keys = array();
  $exclude_keys[] = 'checked';
  $exclude_keys[] = 'sub';
  $exclude_keys[] = 'greencards';
  $exclude_keys[] = 'yellowcards';
  $exclude_keys[] = 'blackcards';
  $exclude_keys[] = 'redcards';
  $exclude_keys[] = 'mvp';

  return $exclude_keys;
}

