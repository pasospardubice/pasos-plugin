<?php

function stats_zapas_add()
{
    add_meta_box( 'stats_id', __( 'Statistiky zápasu', 'pasos' ), 'stats_zapas_box', 'zapas', 'normal', 'high' );
}

add_action( 'add_meta_boxes', 'stats_zapas_add' );


function stats_zapas_box(){

    global $post;
    wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
    $values = get_post_meta( $post->ID );
   
    $selected_players = unserialize( get_post_meta( $post->ID, 'pasos_players', true ) );
    
    $teams = get_the_terms( $post->ID, 'tym' );
    $seasons = get_the_terms( $post->ID, 'sezona' );


    if ( is_array( $teams ) ) {
              
      $match_teams = array();

      foreach ( $teams as $team ) {
        
        $match_teams[] = $team->term_id;
      }
    } else {
      $match_teams = array();
    }

    if ( is_array( $seasons ) ) {
              
      $match_seasons = array();

      foreach ( $seasons as $season ) {
        
        $match_seasons[] = $season->term_id;
      }
    }else {

      $match_seasons = array();

    }

    $args = array(
      'post_type' => 'hrac',
      'meta_key' => 'cislo_dresu',
      'orderby' => 'menu_order meta_value_num',
      'order' => 'ASC',
      'showposts' => -1
    );

    if( $teams ) {
      $args['tax_query'] = array(
        'relation' => 'AND',
        array(
          'taxonomy' => 'tym',
          'field' => 'term_id',
          'terms' => $match_teams
        ),
        array(
          'taxonomy' => 'sezona',
          'field' => 'term_id',
          'terms' => $match_seasons
        )
      );
    }

    $players = get_posts( $args );

    ?>
    <div id="pasos_players">
    
        <table>

          <tr>
            <td><input type="checkbox" value="1" name="zobrazit_statistiky" <?php if ( isset ( $values['zobrazit_statistiky'] ) ) checked( $values['zobrazit_statistiky'][0], '1' ); ?>> <?php _e("Zobrazit statistiky na detailu zápasu.","pasos"); ?></td>
          </tr>

        </table>

        <div style="height: 50px;"></div>

        <table>


    <?php

    if ( empty( $players ) ) { 

           _e( '<tr><td class="error">Žádní hráči pro tuto sezónu, nebo vybraný tým.</td></tr>' );
    
    }else{

        $type = "lineup";
        $pasos_player_stats_labels = pasos_get_player_stats_labels();  
  
        if ( ! is_array( $selected_players ) ) $selected_players = array();
        $selected_players = array_merge( array( 'lineup' => array(), 'subs' => array() ), $selected_players );
        $pasos_player_stats_labels = pasos_get_preset_labels();

        
        echo "<tr>";
        echo "<th>Jméno</th>";
        foreach( $pasos_player_stats_labels as $key => $val ) { 

              ?>
                <th><?php echo $val; ?></th>
              <?php
              
          }
        echo "</tr>";

        foreach( $players as $player ) {

          $played = (
                    is_array( $selected_players ) &&
                    array_key_exists( $type, $selected_players ) &&
                    is_array( $selected_players[$type] ) &&
                    array_key_exists( $player->ID, $selected_players[$type] )&&
                    is_array( $selected_players[$type][$player->ID] )
                  );


          if ( get_post_meta( $player->ID, 'first_name', true ) and get_post_meta( $player->ID, 'last_name', true ) )  {
            $cele_jmeno = get_post_meta( $player->ID, 'first_name', true ) . " ". get_post_meta( $player->ID, 'last_name', true );
          }else{
            $cele_jmeno = $player->post_title; 
          }
            


          $teams = get_the_terms( $player->ID, 'tym' );
          $seasons = get_the_terms( $player->ID, 'sezona' ); 

        ?>
          <tr id="<?php echo $player->ID; ?>" data-player="<?php echo $player->ID; ?>" class="player-stats-list <?php echo $player_teams; ?> <?php echo $seasonclass; ?> sortable sorted">
                    
            
                <td class="names">
                  
                  <label class="selectit">
                      <input type="checkbox" data-player="<?php echo $player->ID; ?>" name="pasos_players[<?php echo $type; ?>][<?php echo $player->ID; ?>][checked]" class="player-select" value="1" <?php checked( true, $played ); ?> />
                      
                      <span class="name">
                        <?php echo get_post_meta( $player->ID, 'cislo_dresu', true ); ?> <?php echo $cele_jmeno; ?>
                      </span>
                        
                  
                  </label> 
                  
                </td>
                
                <?php 
                foreach( $pasos_player_stats_labels as $key => $val ){
                ?>
                <td class="<?php echo $key; ?>">
                    <input type="number" data-player="<?php echo $player->ID; ?>" name="pasos_players[<?php echo $type; ?>][<?php echo $player->ID; ?>][<?php echo $key; ?>]" value="<?php pasos_stats_value( $selected_players[$type], $player->ID, $key ); ?>"<?php if ( !$played ) echo ' disabled'; ?>/>
                </td>
                <?php
                }
                ?>
                 
                
          </tr>        
        <?php
      }
    }

    ?>
      </table>  
    </div>
    
    <div class="stats_explain">
    
      <h3><?php _e("Vysvětlivky statistik","pasos"); ?></h3>
      <ul>
      
        <li>AB (Počet startů na pálce)</li>
        <li>H (Hits)</li>
        <li>HR (Počet homerunů)</li>
        <li>BA (Pálkařský průměr =H/AB x 1000)</li>
        <li>RBI (body stažené odpalem)</li>
        <li>ERA (průměr umožněných bodů na zápas )</li>
        <li>PAB (Celkový počet startů = AB+BB+HB+SH)</li>        
        <li>SO (Strike Out)</li> 
        <li>SOA (Průměrný počet Strike Out = SO/AB)</li> 

      </ul>

    </div>
    

    <?php
}

add_action( 'save_post', 'stats_zapas_save' );

function stats_zapas_save( $post_id ){
    
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post', $post_id ) ) return;    
    

    if( isset( $_POST[ 'zobrazit_statistiky' ] ) ) {
    
        update_post_meta( $post_id, 'zobrazit_statistiky', '1' );
    
    } else {
        
        update_post_meta( $post_id, 'zobrazit_statistiky', '0' );
        
    }
    
    if(isset($_POST['pasos_players'])){
        
        $players = (array)$_POST['pasos_players'];

        if ( is_array( $players ) ) {
            if ( array_key_exists( 'lineup', $players ) && is_array( $players['lineup'] ) )
              $players['lineup'] = array_filter( $players['lineup'], 'pasos_array_filter_checked' );
        }

        update_post_meta( $post_id, 'pasos_players', serialize( $players ) );      
		
    }

    do_action( 'delete_plugin_transients' ); 
      
} 


