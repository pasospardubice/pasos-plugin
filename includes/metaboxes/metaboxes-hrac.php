<?php

function info_add()
{
    add_meta_box( 'info_id', __( 'Infobox o hráči', 'pasos' ), 'info_box', 'hrac', 'normal', 'high' );
}

add_action( 'add_meta_boxes', 'info_add' );


function info_box(){

    global $post;
    wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
    $values = get_post_meta( $post->ID );

?>

 <table>
  
       <tr>
      
          <td>Číslo dresu: </td>
          <td><input style="width: 35px;" type="text" name="cislo_dresu" id="cislo_dresu" value="<?php if ( isset ( $values['cislo_dresu'] ) ) { echo $values['cislo_dresu'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>Křestní jméno: </td>
          <td><input style="width: 160px;" type="text" name="first_name" id="first_name" value="<?php if ( isset ( $values['first_name'] ) ) { echo $values['first_name'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>Příjmení</td>
          <td><input style="width: 160px;" type="text" name="last_name" id="last_name" value="<?php if ( isset ( $values['last_name'] ) ) { echo $values['last_name'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>Rok narození (formát YYYY-mm-dd)</td>
          <td><input style="width: 160px;" type="text" name="age" id="age" value="<?php if ( isset ( $values['age'] ) ) { echo $values['age'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>Váha (v Kg)</td>
          <td><input style="width: 160px;" type="text" name="weight" id="weight" value="<?php if ( isset ( $values['weight'] ) ) { echo $values['weight'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>Výška (v cm)</td>
          <td><input style="width: 160px;" type="text" name="height" id="height" value="<?php if ( isset ( $values['height'] ) ) { echo $values['height'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
       
      <tr>
      
          <td>Pozice</td>
          <td><input style="width: 160px;" type="text" name="position" id="position" value="<?php if ( isset ( $values['pozice'] ) ) { echo $values['pozice'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>Pálka (L/P)</td>
          <td><input style="width: 40px;" type="text" name="palka" id="palka" value="<?php if ( isset ( $values['palka'] ) ) { echo $values['palka'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>Aktivní hráč</td>
          <td><input type="checkbox" id="active_player" name="active_player" value="yes" <?php if ( isset ( $values['active_player'] ) ) checked( $values['active_player'][0], 'yes' ); ?> />
        </td>
          
      </tr>
      
      <tr>
      
          <td>Zobrazit aktivní link na seznamu hráčů?</td>
          <td><input type="checkbox" id="active_player" name="active_player_link" value="yes" <?php if ( isset ( $values['active_player_link'] ) ) checked( $values['active_player_link'][0], 'yes' ); ?> />
        </td>
          
      </tr>
            
      <tr>
      
          <td style="padding-top: 20px; padding-left: 0px;"><h2><strong>Kontakt na hráče</h2></strong></td>
          
      </tr>
      
      <tr>
      
          <td>Telefon (bez mezer včetně mezinárodní předvolby)</td>
          <td><input style="width: 160px;" type="text" name="telefon" id="telefon" value="<?php if ( isset ( $values['telefon'] ) ) { echo $values['telefon'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      
      <tr>
      
          <td>E-mail</td>
          <td><input style="width: 160px;" type="text" name="email" id="email" value="<?php if ( isset ( $values['email'] ) ) { echo $values['email'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      

  
  </table>  
            

<?php
}

add_action( 'save_post', 'info_box_save' );

function info_box_save( $post_id )
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
    
    if( isset( $_POST[ 'cislo_dresu' ] ) ) {
    
        update_post_meta( $post_id, 'cislo_dresu', $_POST[ 'cislo_dresu' ] );
    }
    
    if( isset( $_POST[ 'first_name' ] ) ) {
    
        update_post_meta( $post_id, 'first_name', $_POST[ 'first_name' ] );
    }
    
    if( isset( $_POST[ 'last_name' ] ) ) {
    
        update_post_meta( $post_id, 'last_name', $_POST[ 'last_name' ] );
    }
    
    if( isset( $_POST[ 'age' ] ) ) {
    
        update_post_meta( $post_id, 'age', $_POST[ 'age' ] );
    }
    
    if( isset( $_POST[ 'weight' ] ) ) {
    
        update_post_meta( $post_id, 'weight', $_POST[ 'weight' ] );
    }
    
    if( isset( $_POST[ 'height' ] ) ) {
    
        update_post_meta( $post_id, 'height', $_POST[ 'height' ] );
    }
    
    if( isset( $_POST[ 'pozice' ] ) ) {
    
        update_post_meta( $post_id, 'pozice', $_POST[ 'pozice' ] );
    }
    
    if( isset( $_POST[ 'palka' ] ) ) {
    
        update_post_meta( $post_id, 'palka', $_POST[ 'palka' ] );
    }
    
    if( isset( $_POST[ 'active_player' ] ) ) {
    
        update_post_meta( $post_id, 'active_player', 'yes' );
    
    } else {
        
        update_post_meta( $post_id, 'active_player', 'no' );
        
    }
    
    if( isset( $_POST[ 'active_player_link' ] ) ) {
    
        update_post_meta( $post_id, 'active_player_link', 'yes' );
    
    } else {
        
        update_post_meta( $post_id, 'active_player_link', 'no' );
        
    }
    
    if( isset( $_POST[ 'number_of_sezona' ] ) ) {
    
        update_post_meta( $post_id, 'number_of_sezona', $_POST[ 'number_of_sezona' ] );
    }
    
    if( isset( $_POST[ 'telefon' ] ) ) {
    
        update_post_meta( $post_id, 'telefon', $_POST[ 'telefon' ] );
    }
    
    if( isset( $_POST[ 'email' ] ) ) {
    
        update_post_meta( $post_id, 'email', $_POST[ 'email' ] );
    }
    
}

//META boxes - NAME: start;