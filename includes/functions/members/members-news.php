<?php
/* delete other cpt from menu: start; */ 
if (!function_exists('delete_other_cpt_news')) { 


	function delete_other_cpt_news() {
  
      if ( current_user_can('novinky') ){
      
      
         remove_menu_page( 'edit-comments.php' );
         remove_menu_page( 'tools.php' );       
         remove_menu_page( 'edit.php?post_type=project' );
         remove_menu_page( 'edit.php?post_type=hrac' );
         remove_menu_page( 'edit.php?post_type=klub' );
         remove_menu_page( 'edit.php?post_type=zapas' );
         remove_menu_page( 'wpcf7' );
         
      }
  
  }

}
add_action('admin_menu','delete_other_cpt_news');

/* delete other cpt from menu: konec; */

/* edit add post: start; */

function remove_post_custom_fields_news() {

  if ( current_user_can('novinky') ) {
  
   remove_meta_box('et_settings_meta_box', 'post', 'side'); 
   remove_meta_box('postcustom', 'post', 'normal');
   remove_meta_box('authordiv', 'post', 'normal');  
   remove_meta_box('slugdiv', 'post', 'normal');
   remove_meta_box('commentstatusdiv', 'post', 'normal');
   remove_meta_box('commentsdiv', 'post', 'normal');
   remove_meta_box('revisionsdiv', 'post', 'normal');
   remove_meta_box('trackbacksdiv', 'post', 'normal');
  
  }
  
}

add_action( 'add_meta_boxes' , 'remove_post_custom_fields_news', 999 );

/* edit add post: konec; */  

/* edit columns: start; */

function my_columns_filter_news( $columns ) {
  
  if ( current_user_can('novinky') ) {

     unset($columns['wpseo-score']);
     unset($columns['wpseo-score-readability']);
     unset($columns['comments']);
     unset($columns['wpseo-title']);
     unset($columns['wpseo-metadesc']);
     unset($columns['wpseo-focuskw']);


     return $columns;
  }else{


    return $columns;

  }

}
       
// Filter pages
//add_filter( 'manage_edit-page_columns', 'my_columns_filter_news',10, 1 );  

// Filter Posts
add_filter( 'manage_edit-post_columns', 'my_columns_filter_news',10, 1 );



/* edit columns: end; */
?>