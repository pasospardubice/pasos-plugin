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
?>