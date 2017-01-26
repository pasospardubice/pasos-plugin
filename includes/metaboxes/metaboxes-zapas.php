<?php

function info_zapas_add()
{
    add_meta_box( 'shrnuti_id', __( 'Shrnutí zápasu', 'pasos' ), 'shrnuti_zapasu_box', 'zapas', 'normal', 'high' );
    add_meta_box( 'info_id', __( 'Infobox o zápasu', 'pasos' ), 'info_zapas_box', 'zapas', 'normal', 'high' );
}

add_action( 'add_meta_boxes', 'info_zapas_add' );
add_action( 'add_meta_boxes', 'info_zapas_add' );


function info_zapas_box(){

    global $post;
    wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
    $values = get_post_meta( $post->ID );

    $teams = get_the_terms( $post->ID, 'tym' );

    if ( is_array( $teams ) ) {
      $team = $teams[0]->term_id;
    } else {
      $team = 19;
    }

    $seasons = get_the_terms( $post->ID, 'sezona' );

    if ( is_array( $seasons ) ) {
      $season = $seasons[0]->term_id;
    } else {
      $season = "200";
    }

?>

 <table>

       <tr>
      
          <td>Tým: </td>
          <td>
          <?php
            wp_dropdown_categories(
                                    array(
                                    'show_option_all' => __( 'All' ),
                                    'orderby' => 'title',
                                    'hide_empty' => false,
                                    'taxonomy' => 'tym',
                                    'selected' => $team,
                                    'name' => 'pasos_match_team',
                                    'class' => 'chosen_select'
                                  )
              ); 
          ?>
        </td>
          
      </tr>

      <tr>
      
          <td>Sezóna: </td>
          <td>
          <?php
              wp_dropdown_categories(array(
                'show_option_none' => __( 'None' ),
                'orderby' => 'title',
                'hide_empty' => false,
                'taxonomy' => 'sezona',
                'selected' => $season,
                'name' => 'pasos_sezona',
                'class' => 'chosen_select'
              ));
            ?>
        </td>
          
      </tr>
  
      <tr>
      
          <td>Domácí tým: </td>
          <td><input style="width: 160px;" type="text" name="home_team" id="home_team" value="<?php if ( isset ( $values['home_team'] ) ) { echo $values['home_team'][0]; }else{echo "Pasos";}; ?>" /></td>
          <td><input style="width: 40px;" type="text" name="home_team_result" id="home_team_result" value="<?php if ( isset ( $values['home_team_result'] ) ) { echo $values['home_team_result'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>Hosté</td>
          <td><input style="width: 160px;" type="text" name="away_team" id="away_team" value="<?php if ( isset ( $values['away_team'] ) ) { echo $values['away_team'][0]; }else{echo "Pasos";}; ?>" /></td>
          <td><input style="width: 40px;" type="text" name="away_team_result" id="away_team_result" value="<?php if ( isset ( $values['away_team_result'] ) ) { echo $values['away_team_result'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
            
      <tr>
      
          <td>Datum zápasu<br>Formát: YYYY-MM-DD H:M</td>
          <td><input style="width: 160px;" type="text" name="date_of_match" id="date_of_match" value="<?php if ( isset ( $values['date_of_match'] ) ) { echo $values['date_of_match'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>Hřiště</td>
          <td><input style="width: 160px;" type="text" name="hriste" id="hriste" value="<?php if ( isset ( $values['hriste'] ) ) { echo $values['hriste'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>Je to domácí zápas/turnaj?</td>
          <td><input type="checkbox" id="home_match" name="home_match" value="yes" <?php if ( isset ( $values['home_match'] ) ) checked( $values['home_match'][0], 'yes' ); ?> />
        </td>
          
      </tr>
      
      
      <tr>
      
        <td>Jedná se o zápas ČSA?</td>
          <td><input type="checkbox" id="zapas_csa" name="zapas_csa" value="yes" <?php if ( isset ( $values['zapas_csa'] ) ) checked( $values['zapas_csa'][0], 'yes' ); ?> />
        </td>
          
      </tr>
                 
      <tr>
      
        <td>Je to turnaj?</td>
          <td><input type="checkbox" id="turnaj" name="turnaj" value="yes" <?php if ( isset ( $values['turnaj'] ) ) checked( $values['turnaj'][0], 'yes' ); ?> />
        </td>
          
      </tr>
      
      <tr>
      
          <td>Název turnaje?</td>
          <td><input style="width: 160px;" type="text" name="turnaj_name" id="turnaj_name" value="<?php if ( isset ( $values['turnaj_name'] ) ) { echo $values['turnaj_name'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>

      
      <tr>
      
          <td>Výsledek turnaje</td>
          <td><input style="width: 160px;" type="text" name="turnaj_result" id="turnaj_result" value="<?php if ( isset ( $values['turnaj_result'] ) ) { echo $values['turnaj_result'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>iScore odkaz</td>
          <td><input style="width: 160px;" type="text" name="iscore" id="iscore" value="<?php if ( isset ( $values['iscore'] ) ) { echo $values['iscore'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>

  
  </table>  
            

<?php
}

function shrnuti_zapasu_box(){

    global $post;
    wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
    $values = get_post_meta( $post->ID );


    if ( isset ( $values['shrnuti_zapasu'] ) ) { $shrnuti_zapasu = $values['shrnuti_zapasu'][0]; }else{ $shrnuti_zapasu = ""; }

    $settings = array(
      'textarea_name' => 'shrnuti_zapasu',
      'media_buttons' => false,
      'quicktags'     => array( 'buttons' => 'em,strong,link' ),
      'tinymce'       => array(
        'theme_advanced_buttons1' => 'bold,italic,strikethrough,separator,bullist,numlist,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,undo,redo,separator',
        'theme_advanced_buttons2' => '',
      ),
      'editor_css'    => '<style>#wp-excerpt-editor-container .wp-editor-area{height:175px; width:100%;}</style>'
    );

wp_editor( htmlspecialchars_decode( $shrnuti_zapasu ), 'excerpt', apply_filters( 'pasos_match_preview_editor_settings', $settings ) );

}

add_action( 'save_post', 'shrnuti_zapasu_box_save' );
add_action( 'save_post', 'info_zapas_save' );

function shrnuti_zapasu_box_save ( $post_id ){

     if( isset( $_POST[ 'shrnuti_zapasu' ] ) ) {
        
        update_post_meta( $post_id, 'shrnuti_zapasu', $_POST[ 'shrnuti_zapasu' ] );

    }


}

function info_zapas_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post', $post_id ) )  return;

     
   
    // Checks save status - overcome autosave, etc.
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'prfx_nonce' ] ) && wp_verify_nonce( $_POST[ 'prfx_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    } 


    
    if( isset( $_POST[ 'home_team' ] ) ) {
    
        update_post_meta( $post_id, 'home_team', $_POST[ 'home_team' ] );
    }
    
    if( isset( $_POST[ 'home_team_result' ] ) ) {
    
        update_post_meta( $post_id, 'home_team_result', $_POST[ 'home_team_result' ] );
    }
    
    if( isset( $_POST[ 'away_team' ] ) ) {
    
        update_post_meta( $post_id, 'away_team', $_POST[ 'away_team' ] );
    }
    
    if( isset( $_POST[ 'away_team_result' ] ) ) {
    
        update_post_meta( $post_id, 'away_team_result', $_POST[ 'away_team_result' ] );
    }
    
        
    if( isset( $_POST[ 'date_of_match' ] ) ) {
    
        update_post_meta( $post_id, 'date_of_match', $_POST[ 'date_of_match' ] );
    }
    
    if( isset( $_POST[ 'hriste' ] ) ) {
    
        update_post_meta( $post_id, 'hriste', $_POST[ 'hriste' ] );
    }
    
    if( isset( $_POST[ 'zapas_sezona' ] ) ) {
    
        update_post_meta( $post_id, 'zapas_sezona', $_POST[ 'zapas_sezona' ] );
    }
    
    if( isset( $_POST[ 'turnaj_result' ] ) ) {
    
        update_post_meta( $post_id, 'turnaj_result', $_POST[ 'turnaj_result' ] );
    }

    if( isset( $_POST[ 'turnaj_name' ] ) ) {
    
        update_post_meta( $post_id, 'turnaj_name', $_POST[ 'turnaj_name' ] );
    }
    
    if( isset( $_POST[ 'iscore' ] ) ) {
    
        update_post_meta( $post_id, 'iscore', $_POST[ 'iscore' ] );
    }
    
    
    if( isset( $_POST[ 'zapas_csa' ] ) ) {
    
        update_post_meta( $post_id, 'zapas_csa', 'yes' );
    
    } else {
        
        update_post_meta( $post_id, 'zapas_csa', 'no' );
        
    }
    
    if( isset( $_POST[ 'home_match' ] ) ) {
    
        update_post_meta( $post_id, 'home_match', 'yes' );
    
    } else {
        
        update_post_meta( $post_id, 'home_match', 'no' );
        
    }
    
    if( isset( $_POST[ 'turnaj' ] ) ) {
    
        update_post_meta( $post_id, 'turnaj', 'yes' );
    
    } else {
        
        update_post_meta( $post_id, 'turnaj', 'no' );
        
    }

    if( isset( $_POST['pasos_match_team'] ) ){
      wp_set_post_terms( $post_id, $_POST['pasos_match_team'], 'tym' );
    }

    if( isset( $_POST['pasos_sezona'] ) ){
      wp_set_post_terms( $post_id, $_POST['pasos_sezona'], 'sezona' );
    }
    
    
    
}

//META boxes - NAME: start;