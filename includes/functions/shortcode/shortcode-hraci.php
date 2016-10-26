<?php


function seznam_hracu_function($atts, $content = null) {
   
    
   extract(shortcode_atts(array(
      
      'pocet_hracu' => false,
      'tym' => 'muzi-a', 
      'aktivni' => 'yes',
      'cislo_dresu' => 'true',
      'vek' => 'true',
      'palka' => 'true',
      'pozice' => 'false',
      
   ), $atts));

   $return_string = '<table class="seznam-hracu-tabulka">';
   
   
   
    
         $return_string .= '<tr>';
    
    if($cislo_dresu == "true"){        
         
         $return_string .= '<td style="width: 11%; text-align: center;" class="nadpis">';
         $return_string .= "Dres";              
         $return_string .= '</td>';
    
    }
         
         $return_string .= '<td class="nadpis">';
         $return_string .= "Jméno";              
         $return_string .= '</td>';
    
    
    if($vek == "true"){      
         
         $return_string .= '<td class="nadpis">';
         $return_string .= "Věk";              
         $return_string .= '</td>';
         
    }

   if($palka == "true"){
   
         $return_string .= '<td class="nadpis" style="text-align: center;">';
         $return_string .= "Pálka";              
         $return_string .= '</td>';
   
   }
   
   if($pozice == "true"){
   
         $return_string .= '<td class="nadpis" style="text-align: center;">';
         $return_string .= "Pozice";              
         $return_string .= '</td>';
   
   }

         

         $return_string .= '</tr>';
  
  
  query_posts(
               array( 
                      
                      
                      'post_type' => 'hrac',
                      'orderby' => 'meta_value',
                      'meta_key'  => 'last_name', 
                      'order' => 'ASC' , 
                      'showposts' => 99, 
                      
                      'meta_query' => array(
                      
                                        array(
                                    			'key'     => 'active_player',
                                    			'value'   => 'yes',
                                    			'compare' => '=',
                                    		),
                    		
                    	),
                                             
                      'tax_query' => array(
                                      		
                                          
                                          array(
                                      			     'taxonomy' => 'tym',
                                      			     'field'    => 'slug',
                                      			     'terms'    => explode( ',', $atts['tym'] ),
                                      		      ),
                                                
                                      	),
                      
                      
                      
                      
                    )
                    
              );
   
   
   if (have_posts()) :
   
      while (have_posts()) : the_post();
   
                    
               

         $return_string .= '<tr>';
        
        if($cislo_dresu == "true"){ 
        
         $return_string .= '<td style="text-align: center;" >';
         $return_string .= get_post_meta( get_the_ID(), 'cislo_dresu', true );              
         $return_string .= '</td>';    
        
        }
        
        
         
         $return_string .= '<td>';
         
         if(get_post_meta( get_the_ID(), 'active_player_link', true ) == "yes"){ $return_string .= "<a href='".get_permalink()."'>"; } 
         
         
         
         $return_string .= get_post_meta( get_the_ID(), 'first_name', true )."&nbsp;".get_post_meta( get_the_ID(), 'last_name', true );              
         $return_string .= '</td>';
        
        if(get_post_meta( get_the_ID(), 'active_player_link', true ) == "yes"){ $return_string .= "</a>"; }
        
         
         if($vek == "true"){
         
           $return_string .= '<td>';
           
           if(get_post_meta( get_the_ID(), 'age', true )){
           
           $return_string .= getAge(get_post_meta( get_the_ID(), 'age', true ));              
           
           }
           
           
           $return_string .= '</td>';
         
         
         }
         
         
         
        if($palka == "true"){
        
             $return_string .= '<td  style="text-align: center;">';
        
             if(get_post_meta( get_the_ID(), 'palka', true )){
             
             $return_string .= get_post_meta( get_the_ID(), 'palka', true );              
             
             }else{
             
             $return_string .= "-";              
             
              
            }     
            $return_string .= '</td>';
        
        }
        
        
        if($pozice == "true"){
        
             $return_string .= '<td  style="text-align: center;">';
        
             if(get_post_meta( get_the_ID(), 'pozice', true )){
             
             $return_string .= get_post_meta( get_the_ID(), 'pozice', true );              
             
             }else{
             
             $return_string .= "-";              
             
              
            }     
            $return_string .= '</td>';
        
        }
         
         
         
         

         $return_string .= '</tr>';
   
      endwhile;
   
   else :
   
   $no_result = "Pro tuto sezónu nebyly nalezeny žádní hráči";
   
   endif;
   
   
   $return_string .= '</table>';
   
   $return_string .= $no_result;

   wp_reset_query();
   
   
   //if( is_user_logged_in()  ){
  
   return $return_string;
   
   //}
   
}

function getAge($date) {
    
    return intval(date('Y', time() - strtotime($date))) - 1970;
    
}


function clanky_o_hraci_function($atts, $content = null) {

   extract(shortcode_atts(array(
      
      'hrac' => false,
      'pocet_clanku' => 6,
      
   ), $atts));


   $args = array(
   
              	'post_type' => 'post',
                'tag' => $hrac
              	
              );

   // The Query
    $the_query = new WP_Query( $args );
    
    // The Loop
    if ( $the_query->have_posts() ) {
    
    	$return_string .= '<ul>';
      $i=0;
    	while ( $the_query->have_posts() ) {
    		
        $i++;
        
        $the_query->the_post();
        
        $return_string .= '<li>';
        $return_string .=  '<a href="'.get_permalink().'">';
        $return_string .=  get_the_title();
        $return_string .=  '</a>';
        $return_string .= " &nbsp; ";
        $return_string .=  "(".get_the_date("j. n. Y").")";
        $return_string .=  '</li>';
    	}
    	$return_string .= '</ul>';       
    	/* Restore original Post Data */
    	wp_reset_postdata();
      
      
      if($i > $pocet_clanku){
      
      $return_string .= "<p><a href='/tag/".$hrac."'>Další články v archivu</a></p>";
      
      }
      
    
    } else {
    	
      // no posts found         
      $return_string = "<p>Žádné články<p>";
       
    }





   return $return_string;

}

function register_shortcodes_hraci(){

   add_shortcode('seznam_hracu', 'seznam_hracu_function');
   add_shortcode('clanky_o_hraci', 'clanky_o_hraci_function');
   
}


add_action( 'init', 'register_shortcodes_hraci');
?>