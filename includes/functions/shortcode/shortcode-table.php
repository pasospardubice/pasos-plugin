<?php
// table list: start

function table_list_function($atts, $content = null) {
   
   extract(shortcode_atts(array(
      'pocet_tymu' => '',
      'klub_sezona' => date("Y"),
      'id_ligy' => '',
      'slug_ligy' => '',
      'skore' => false,
      'vyhry' => true,
      'prohry' => true,
      
   ), $atts));

   $return_string = '<table class="homepage-tabulka">
                          
                          <tbody>
                          <tr>
                          <td style="width: 3%" class="table-menu">#</td>
                          <td class="table-menu">Tým</td>
                          ';
                          
   if ($vyhry == "true") {
    
      $return_string .= '<td style="width: 6%" class="table-menu">V</td>';
    
   }
   
   if ($prohry == "true") {
    
      $return_string .= '<td style="width: 6%" class="table-menu">P</td>';
    
   }
   
      $return_string .= '<td style="width: 6%" class="table-menu">B</td>';
   
   if ($skore == "true") {
    
      $return_string .= '<td style="width: 8%" class="table-menu">Skóre</td>';
    
   }
   
   $return_string .= "</tr>";
   
   
   $poradi = 0;
   query_posts(
                
                array( 
   
                      'post_type' => 'klub',
                      'orderby' => 'meta_value_num',
                      'meta_key' => 'klub_points' , 
                      'order' => 'DESC' , 
                      'showposts' => $pocet_tymu,
                      
                      
                      'meta_query' => array(
                      
                    		
                                          
                                      		array(
                                          			'key'     => 'klub_sezona',
                                          			'value'   => $klub_sezona,
                                          			'compare' => '=',
                                      		      ),
                                          
                     
                                          ),
                      
                      
                      'tax_query' => array(
                                      		
                                          
                                          array(
                                      			     'taxonomy' => 'liga',
                                      			     'field'    => 'slug',
                                      			     'terms'    => $slug_ligy,
                                      		      ),
                                                
                                      	),
                      
                      )
              );
   
   
   if (have_posts()) :
   
      while (have_posts()) : the_post();
         $poradi++;
         
         $return_string .= '<tr>';
         
         $return_string .= '<td>';
         
         $return_string .= $poradi.".";         
         $return_string .= '</td>';
         
         $return_string .= '<td>';
         
         if( get_post_meta( get_the_ID(), 'klub_pasos', true ) == "yes"){
           $return_string.="<strong>"; 
         }
         
         $return_string .= get_the_title();
         
          if( get_post_meta( get_the_ID(), 'klub_pasos', true ) == "yes"){
           $return_string.="</strong>"; 
         }
                  
         $return_string .= '</td>';
         
         if ($vyhry == "true") {
         
         $return_string .= '<td>';
         $return_string .= get_post_meta( get_the_ID(), 'home_klub_win', true );
         $return_string .= '</td>';
         
         }
         
         if ($prohry == "true") {
         
         $return_string .= '<td>';
         $return_string .= get_post_meta( get_the_ID(), 'home_klub_lose', true );
         $return_string .= '</td>';
         
         }
         
         
         $return_string .= '<td>';
         $return_string .= get_post_meta( get_the_ID(), 'klub_points', true );
         $return_string .= '</td>';
         
         if ($skore == "true") {
         
         $return_string .= '<td>';
         $return_string .= get_post_meta( get_the_ID(), 'skore_win', true ) ." : ". get_post_meta( get_the_ID(), 'skore_lose', true );
         $return_string .= '</td>';
         
         }
         
         
         $return_string .= '</tr>';
   
      endwhile;
   
   else:
   
   $no_result = "<tr><td></td><td>Nejsou zadané žádné týmy pro tuto sezónu.</td><td></td><td></td><td></td></tr>";
   
   endif;
   
   
   $return_string .= $no_result.'</table>';

   wp_reset_query();
   return $return_string;
   
}

function register_shortcodes_table(){

   add_shortcode('table_list', 'table_list_function');

   
   
}


add_action( 'init', 'register_shortcodes_table');

?>