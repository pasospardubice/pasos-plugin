
<?php
function register_shortcodes_archive(){

   add_shortcode('archive_by_year', 'archive_by_year_function');
     
}
function archive_by_year_function($atts, $content = null) {

   extract(shortcode_atts(array(
   
      'type' => 'yearly',
      'show_post_count' => '12', 
      'post_type' => 'post',
      'order' => 'DESC',
      
   ), $atts));
   
   
   $args = array(
                 'type' => $type, 
                 //'limit' => $limit, 
                 'format' => 'html', 
                 //'show_post_count' => $showcount,
                 'post_type' => $post_type, 
                 'order' => $order, 
                 'echo' => 0,
                );
   
   $return_string  = "<ul id='archive'>";
   $return_string .= wp_get_archives( $args );
	 $return_string .= "</ul>";
   
   
   
   return $return_string;

}
add_action( 'init', 'register_shortcodes_archive');
?>