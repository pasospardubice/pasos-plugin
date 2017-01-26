<?php

function page_another_title_add()
{
    add_meta_box( 'page_another_title_id', __( 'Titulek stránky do submenu', 'pasos' ), 'page_another_title_box', 'page', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'page_another_title_add' );

function page_another_title_box(){

    global $post;
    wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
    $values = get_post_meta( $post->ID );

    ?>

    <table>

    <tr>
      
          <td>Titulek</td>
          <td><input style="width: 160px;" type="text" name="page_another_title" id="page_another_title" value="<?php if ( isset ( $values['page_another_title'] ) ) { echo $values['page_another_title'][0]; }else{echo "";}; ?>" /></td>
          
      </tr>
      

  
  	</table>  

  	<?php


 }

 add_action( 'save_post', 'page_another_title_save' );

function page_another_title_save( $post_id )
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
    
    if( isset( $_POST[ 'page_another_title' ] ) ) {
    
        update_post_meta( $post_id, 'page_another_title', $_POST[ 'page_another_title' ] );
    }
    
  
    
    
    
}