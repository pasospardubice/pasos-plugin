<?php
function news_function($atts, $content = null) {
   
    
   extract(shortcode_atts(array(
      
      'pocet_news' => '10',
      'kategorie' => '',
      'tymy' => ''

      
   ), $atts));    

   global $paged;
   global $post;
   global $wp_query;
   

   $page = (get_query_var('paged')) ? get_query_var('paged') : 1; 
   
   $args = array(
   
              	'post_type' => 'post',
                'paged' => $paged,
                'posts_per_page' => $pocet_news,
                'tax_query' => array(
                                      array(
                                        'taxonomy' => 'pasos-tym',
                                        'field'    => 'id',
                                        'terms'    => $tymy,
                                      ),
                                    ),                              
              	
              );
   

    // The Query
    $the_query = new WP_Query( $args );

    $total_found_posts = $the_query->found_posts;
    $total_page = ceil($total_found_posts / $pocet_news);

    $return_string = "";

   
    // The Loop
    if ( $the_query->have_posts() ) {
    
      $i=0;
    	while ( $the_query->have_posts() ) {
      $i++;
        $the_query->the_post();
        

        $return_string .= '<div class="news-shortcode">';

        $return_string .=  "<h2>";
        $return_string .=  "<a href=".get_the_permalink().">";
        $return_string .=  get_the_title();
        $return_string .=  "</a>";
        $return_string .=  "</h2>";
        

        $return_string .= "<div class='news-shortcode-featured-image'>";
        $return_string .=  "<a href=".get_the_permalink().">";
        $return_string .= get_the_post_thumbnail();
        $return_string .=  "</a>";
        $return_string .= "</div>";

        $return_string .= "<div class='news-shortcode-featured-text'>";
        $return_string .= "<div class='date'>".get_the_date("j. n. Y")."</div>";
        $return_string .= "<p>".wp_trim_words( get_the_content(), 20, ' ... ' )."</p>";
        $return_string .= "</div>";

        $return_string .= '</div>';

    	}
            
    	/* Restore original Post Data */
    	wp_reset_query();
      wp_reset_postdata();
      
      
    
    } else {
    	
      // no posts found         
      $return_string = "<p>Žádné články<p>";
       
    }
    //$return_string .= wp_pagenavi( array( 'query' => $the_query )  );

   //return $return_string;
   //return wp_pagenavi( array( 'query' => $the_query )  );
   //
  if(function_exists('wp_pagenavi')) {
       $return_string .='<div class="page-navigation">'.wp_pagenavi(array('query' => $the_query, 'echo' => false)).'</div>';
    } else {
        $return_string.='
        <span class="next-posts-links">'.get_next_posts_link('Next page', $total_page).'</span>
        <span class="prev-posts-links">'.get_previous_posts_link('Previous page').'</span>
        ';
    }
   return $return_string;

}

function register_shortcodes_news(){

   add_shortcode('news', 'news_function');
   add_shortcode('news_homepage', 'news_homepage_function');
   
}


add_action( 'init', 'register_shortcodes_news');
?>