<?php

function klub_home_wins_add()
{
    add_meta_box( 'info_id', __( 'Infobox o klubu', 'pasos' ), 'klub_home_wins_box', 'klub', 'normal', 'high' );
}

add_action( 'add_meta_boxes', 'klub_home_wins_add' );


function klub_home_wins_box(){

    global $post;
    wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
    $values = get_post_meta( $post->ID );

?>

 <table>
  
      <tr>
      
          <td>Vítězství:</td>
          <td><input style="width: 40px;" type="text" name="home_klub_win" id="home_klub_win" value="<?php if ( isset ( $values['home_klub_win'] ) ) { echo $values['home_klub_win'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>Prohry:</td>
          <td><input style="width: 40px;" type="text" name="home_klub_lose" id="home_klub_lose" value="<?php if ( isset ( $values['home_klub_lose'] ) ) { echo $values['home_klub_lose'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
            
      <tr>
      
          <td>Body:</td>
          <td><input style="width: 40px;" type="text" name="klub_points" id="klub_points" value="<?php if ( isset ( $values['klub_points'] ) ) { echo $values['klub_points'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>Skore</td>
          <td><input style="width: 40px;" type="text" name="skore_win" id="skore_win" value="<?php if ( isset ( $values['skore_win'] ) ) { echo $values['skore_win'][0]; }else{echo "";}; ?>" /> :</td>
          <td><input style="width: 40px;" type="text" name="skore_lose" id="skore_lose" value="<?php if ( isset ( $values['skore_lose'] ) ) { echo $values['skore_lose'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>Sezóna:</td>
          <td><input style="width: 80px;" type="text" name="klub_sezona" id="klub_sezona" value="<?php if ( isset ( $values['klub_sezona'] ) ) { echo $values['klub_sezona'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>Klub Pasos?</td>
          <td><input type="checkbox" id="klub_pasos" name="klub_pasos" value="yes" <?php if ( isset ( $values['klub_pasos'] ) ) checked( $values['klub_pasos'][0], 'yes' ); ?> />
        </td>
          
      </tr>
      
      
      

  
  </table>  
            

<?php
}

add_action( 'save_post', 'klub_home_wins_save' );

function klub_home_wins_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;

     
   
    // Checks save status - overcome autosave, etc.
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'prfx_nonce' ] ) && wp_verify_nonce( $_POST[ 'prfx_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    } 
    
    if( isset( $_POST[ 'home_klub_win' ] ) ) {
    
        update_post_meta( $post_id, 'home_klub_win', $_POST[ 'home_klub_win' ] );
    }
    
    if( isset( $_POST[ 'home_klub_lose' ] ) ) {
    
        update_post_meta( $post_id, 'home_klub_lose', $_POST[ 'home_klub_lose' ] );
    }
    
    if( isset( $_POST[ 'away_team' ] ) ) {
    
        update_post_meta( $post_id, 'away_team', $_POST[ 'away_team' ] );
    }
    
    if( isset( $_POST[ 'away_team_result' ] ) ) {
    
        update_post_meta( $post_id, 'away_team_result', $_POST[ 'away_team_result' ] );
    }
    
        
    if( isset( $_POST[ 'klub_points' ] ) ) {
    
        update_post_meta( $post_id, 'klub_points', $_POST[ 'klub_points' ] );
    }
    
    if( isset( $_POST[ 'skore_win' ] ) ) {
    
        update_post_meta( $post_id, 'skore_win', $_POST[ 'skore_win' ] );
    }
    
    if( isset( $_POST[ 'skore_lose' ] ) ) {
    
        update_post_meta( $post_id, 'skore_lose', $_POST[ 'skore_lose' ] );
    
    } 
    
    if( isset( $_POST[ 'klub_pasos' ] ) ) {
    
        update_post_meta( $post_id, 'klub_pasos', $_POST[ 'klub_pasos' ] );
    }
    
    if( isset( $_POST[ 'klub_sezona' ] ) ) {
    
        update_post_meta( $post_id, 'klub_sezona', $_POST[ 'klub_sezona' ] );
    
    } 
    
    if( isset( $_POST[ 'klub_pasos' ] ) ) {
    
        update_post_meta( $post_id, 'klub_pasos', 'yes' );
    
    } else {
        
        update_post_meta( $post_id, 'klub_pasos', 'no' );
        
    }
    
    
    
}

//META boxes - NAME: start;