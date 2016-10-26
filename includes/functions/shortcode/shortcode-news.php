<?php


function news_function($atts, $content = null) {
   
    
   extract(shortcode_atts(array(
      
      'pocet_news' => 4,

      
   ), $atts));
   
   $return_string .= ' <div class="et_pb_section nase-projekty-div et_pb_section_1 et_pb_with_background et_section_regular">';
   $return_string .= ' <div class="et_pb_row et_pb_row_1 et_pb_gutters1">';
   
   
   
   $args = array(
   
              	'post_type' => 'post',
                'showposts' => '4',
                'nopaging' => false,
                'category__not_in' => '1'
               
              	
              );
   
    // The Query
    $the_query = new WP_Query( $args );
    
    // The Loop
    if ( $the_query->have_posts() ) {
    
      $i=1;
    	while ( $the_query->have_posts() ) {
      $i++;
        $the_query->the_post();
        

        $return_string .= '';
        //$return_string .= "<div class='fusion-custom-shortcode-image'>";
        //$return_string .=  "<a href=".get_the_permalink().">";
        //$return_string .= get_the_post_thumbnail();
        //$return_string .=  "</a>";
        //$return_string .= "</div>"; 
        
        $return_string .=  "<h2 class='entry-title'>";
        $return_string .=  "<a href=".get_the_permalink().">";
        $return_string .=  get_the_title();
        $return_string .=  "</a>";
        $return_string .=  "</h2>";
        
        
        
        $return_string .= "</div>";
    	}

            
    	/* Restore original Post Data */
    	wp_reset_postdata();
      
      
    
    } else {
    	
      // no posts found         
      $return_string = "<p>Žádné články<p>";
       
    }



   $return_string .= "</div></div>";



   return $return_string;

}

function register_shortcodes_news(){

   add_shortcode('news', 'news_function');
   add_shortcode('news_homepage', 'news_homepage_function');
   
}


add_action( 'init', 'register_shortcodes_news');
?>