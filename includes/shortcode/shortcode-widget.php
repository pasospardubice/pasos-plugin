
<?php
function register_shortcodes_widget_news_pasos_tymy(){

   add_shortcode('widget_news_pasos_tymy', 'widget_news_pasos_tymy_function');
     
}
function widget_news_pasos_tymy_function() {

   extract(shortcode_atts(array(
   
      'post_type' => 'post',      
      
   ), $atts));
  

  $taxonomy = 'pasos-tym';

  $term_args=array(
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC'
  );

  $tax_terms = get_terms($taxonomy,$term_args);

  $return_string .= "<ul id='archive'>";

  foreach ($tax_terms as $tax_term) {
  $return_string .= '<li>' . '<a href="' . esc_attr(get_term_link($tax_term, $taxonomy)) . '" title="' . sprintf( __( "View all posts in %s" ), $tax_term->name ) . '" ' . '>' . $tax_term->name.'</a></li>';
  }

  $return_string .= "</ul>";

  return $return_string;


}
add_action( 'init', 'register_shortcodes_widget_news_pasos_tymy');

add_action( 'init', 'register_shortcodes_widget_video_banner');

function register_shortcodes_widget_video_banner(){

   add_shortcode('widget_video_banner', 'widget_video_banner_function');
     
}
function widget_video_banner_function($atts) {

   extract(shortcode_atts(array(
   
      'src' => '',
      'url' => '',
      'top_text' => '', 
      'button_text' => '',
      'poster' => '', 
      'mobile_version' => '',    
      
   ), $atts));
   
  $return_string = "";
  $return_string .= '<div class="only_mobile"><a href="/nabor/"><img src="'.$mobile_version.'"></a></div>';
  
  $return_string .= '<div class="video-box">';

  $return_string .= '<a class="video-box" href="'.$url.'">';  

  if( $poster ){ $return_string .= '<video loop autoplay poster='.$poster.'>'; }


  $return_string .= '<source src="'.$src.'" type="video/mp4">';
  $return_string .= '</video>';
  
  if( $top_text ){ $return_string .= '<div class="video-box__title">'.$top_text.'</div>'; }
  if( $button_text ){ $return_string .= '<div class="video-box__button">'.$button_text.'</div>'; }
  
  $return_string .= '</a>';
  $return_string .= '</div>';
  
  return $return_string;  

}
?>