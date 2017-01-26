<?php

function zapasy_list_function($atts, $content = null) {
   
    
   extract(shortcode_atts(array(
      
      'pocet_zapasu' => 10,
      'slug_souteze' => 'muzi-a', 
      'sezona' => '2016',
      'domaci_zapas' => 'false',
      'zapas_csa' => 'true'
      
   ), $atts));

   $return_string = '<table class="zapasy-tabulka">';
   
  
   $args = array( 
                      
                      
                      'post_type' => 'zapas',
                      'orderby' => 'meta_value',
                      'meta_key'  => 'date_of_match', 
                      'order' => 'ASC' , 
                      'showposts' => $pocet_zapasu, 
                      
                      
                       'meta_query' => array(
                      
                    		            
                                    
                                    		array(
                                    			'key'     => 'zapas_sezona',
                                    			'value'   => $sezona,
                                    			'compare' => '=',
                                    		),
                        	
                    	),
                      
                      'tax_query' => array(
                                      		
                                          
                                          array(
                                      			     'taxonomy' => 'soutez',
                                      			     'field'    => 'slug',
                                      			     'terms'    => explode( ',', $atts['slug_souteze'] ),
                                      		      ),
                                                
                                      	),
                    
                    
              ); 
            
              
   if($domaci_zapas == "true"){
          
            $args['meta_query'][] = array(
                      
                                  //'relation' => 'AND',
                                    
                                        array(
                                    			'key'     => 'home_match',
                                    			'value'   => 'yes',
                                    			'compare' => '=',
                                    		),
                                                                    
                                );
   
   }
   
    if($zapas_csa == "false"){
          
            $args['meta_query'][] = array(
                      
                                  //'relation' => 'AND',
                                    
                                    	
                                        
                                        
                                         array(
                                        			'key'     => 'zapas_csa',
                                        			'value'   => 'yes',
                                        			'compare' => '!=',
                                        		),
                                                                     
                                );
   
   }  
                                                                

   query_posts($args); 
   if (have_posts()) :
   
      while (have_posts()) : the_post();
   
        $tax = "soutez";
        $terms = get_the_terms( $post->ID, $tax );
             
              if ( !empty( $terms ) ) {

                  foreach ( $terms as $term ){
                      //$i++;
                      //if($i==1){$convertor .= " + "; }
                      
                      //$convertor .= $term->name;
                      
                      //if ($count != $i){ $convertor .=' | '; }
                      
                  }
              }
              
        
        if($term->slug == "muzi-a"){
        
            $background_tym = "blue";
        
        }elseif($term->slug == "muzi-b"){
        
            $background_tym = "grey";
        
        }elseif($term->slug == "zeny"){
        
            $background_tym = "red";
        
        }elseif($term->slug == "t-ball"){
        
            $background_tym = "lightest-blue";
        
        }elseif($term->slug == "zaci"){
        
            $background_tym = "light-blue";
        
        }
        
        if(get_post_meta( get_the_ID(), 'home_match', true ) == "yes"){
        
          $background_domaci_zapas = "svetle_modra";
        
        }else{
        
        $background_domaci_zapas = "";
        
        }

         $return_string .= '<tr>';
         
         $return_string .= '<td style="width: 100px;" class="vertical_text '.$background_tym.'">'.$term->name.'</td>';
         
         
         
                  
         $datum = get_post_meta( get_the_ID(), 'date_of_match', true );
         $finalni_datum = date_i18n('j. M - H:i', strtotime($datum));
         $finalni_datum_turnaj = date_i18n('j. M', strtotime($datum));                   
         
         if(get_post_meta( get_the_ID(), 'turnaj', true ) == "yes"){
            
            $return_string .= '<td style="width:180px;" class="'.$background_domaci_zapas.'">';
            $return_string .= $finalni_datum_turnaj.'</td>';
            $return_string .= '</td>';
            
         }else{
            $return_string .= '<td style="width:180px;" class="'.$background_domaci_zapas.'">';
            $return_string .= $finalni_datum;
            $return_string .= '</td>';
         }
         
         
         if(get_post_meta( get_the_ID(), 'turnaj', true ) == "yes"){
         
            $return_string .= '<td style="width: 190px; text-align: center;" class="'.$background_domaci_zapas.'"  colspan="3">';
            $return_string .= get_the_title();
            if(get_post_meta( get_the_ID(), 'turnaj_result', true )){$return_string .= "<br /><strong style='color: black; font-size: 16px;'>".get_post_meta( get_the_ID(), 'turnaj_result', true ).". místo</strong>";} 
            $return_string .= '</td>';
         
         }else{
         
             $return_string .= '<td style="width: 190px;text-align: right; padding: 0;" class="'.$background_domaci_zapas.'">';    
             $return_string .= get_post_meta( get_the_ID(), 'home_team', true );
             $return_string .= '</td>';
             
            
             if( (get_post_meta( get_the_ID(), 'home_team_result', true ) != "" ) ){             
               
              $return_string .= '<td style="width: 85px;text-align: center; padding: 0;" class="'.$background_domaci_zapas.'" >';
              $return_string .= "<strong style='color: black; font-size: 16px;'>";               
              if(get_post_meta( get_the_ID(), 'home_team_result', true ) != "" ){ $return_string .= "&nbsp;&nbsp;&nbsp;".get_post_meta( get_the_ID(), 'home_team_result', true )." : "; }
              if(get_post_meta( get_the_ID(), 'away_team_result', true ) != "" ){ $return_string .= get_post_meta( get_the_ID(), 'away_team_result', true ); }              
              $return_string .= "</strong>&nbsp;&nbsp;&nbsp;";
              $return_string .= '</td>';
              
             }else{
              
              $return_string .= '<td style="width: 85px;text-align: center; padding: 0;" class="'.$background_domaci_zapas.'" >';
              $return_string .= " <strong>&nbsp;&nbsp;&nbsp;VS&nbsp;&nbsp;&nbsp;</strong> ";
              $return_string .= '</td>';
             }
             
             $return_string .= '<td style="width: 190px;text-align: left; padding: 0;" class="'.$background_domaci_zapas.'">';
             $return_string .= get_post_meta( get_the_ID(), 'away_team', true ); 
             $return_string .= '</td>';
         }
         
                 
         //$return_string .= '</td>';
         
         
         
         $return_string .= '<td style="width: 200px;text-align: left;" class="'.$background_domaci_zapas.'">';
         
         if( (get_post_meta( get_the_ID(), 'hriste', true ) != "" ) ){
         
            
            $return_string .= get_post_meta( get_the_ID(), 'hriste', true );
             
         
         }
                 
         $return_string .= '</td>';
         
         
         
         $return_string .= '<td style="width: 150px;text-align: left;" class="'.$background_domaci_zapas.'">';
         
         if( (get_post_meta( get_the_ID(), 'iscore', true ) != "" ) ){
         
            $return_string .= "<a target='_blank' href='";
            $return_string .= get_post_meta( get_the_ID(), 'iscore', true );
            $return_string .= "'>iScore</a>"; 
         
         }
         
         if( (get_post_meta( get_the_ID(), 'pasos_odkaz', true ) != "" ) ){
         
            $return_string .= "<a target='_blank' href='";
            $return_string .= get_post_meta( get_the_ID(), 'pasos_odkaz', true );
            $return_string .= "'>Report</a>"; 
         
         }
                 
         $return_string .= '</td>';
         
         
         
         
         
         
         $return_string .= '</tr>';
   
      endwhile;
   
   else :
   
   $no_result = "Pro tuto sezónu nebyly zadány žádné zápasy.";
   
   endif;
   
   
   $return_string .= '</table>';
   
   $return_string .= $no_result;

   wp_reset_query();
   return $return_string;
   
}

function zapasy_list_table_function($atts, $content = null) {
   
    
   extract(shortcode_atts(array(
      
      'pocet_zapasu' => 10,
      'slug_souteze' => 'muzi-a', 
      'sezona' => '2016',
      'domaci_zapas' => 'false',
      'zapas_csa' => 'true'
      
   ), $atts));

   $return_string .= '<table class="zapasy-tabulka-table">';
   $return_string .= '<tr>';
   $return_string .= '<td class="table-menu">Datum - čas</td>';
   $return_string .= '<td class="table-menu">Domácí</td>';
   $return_string .= '<td class="table-menu">Hosté</td>';
   $return_string .= '<td class="table-menu">Výsledek</td>';
   $return_string .= '<td class="table-menu">Report</td>';
   $return_string .= '</tr>';


   $args = array( 
                      
                      
                      'post_type' => 'zapas',
                      'orderby' => 'meta_value',
                      'meta_key'  => 'date_of_match', 
                      'order' => 'ASC' , 
                      'showposts' => $pocet_zapasu, 
                      
                      
                       'meta_query' => array(
                      
                                    
                                    
                                        array(
                                          'key'     => 'zapas_sezona',
                                          'value'   => $sezona,
                                          'compare' => '=',
                                        ),
                          
                      ),
                      
                      'tax_query' => array(
                                          
                                          
                                          array(
                                                 'taxonomy' => 'soutez',
                                                 'field'    => 'slug',
                                                 'terms'    => explode( ',', $atts['slug_souteze'] ),
                                                ),
                                                
                                        ),
                    
                    
              ); 
            
              
   if($domaci_zapas == "true"){
          
            $args['meta_query'][] = array(
                      
                                  //'relation' => 'AND',
                                    
                                        array(
                                          'key'     => 'home_match',
                                          'value'   => 'yes',
                                          'compare' => '=',
                                        ),
                                                                    
                                );
   
   }
   
    if($zapas_csa == "false"){
          
            $args['meta_query'][] = array(
                      
                                  //'relation' => 'AND',
                                    
                                      
                                        
                                        
                                         array(
                                              'key'     => 'zapas_csa',
                                              'value'   => 'yes',
                                              'compare' => '!=',
                                            ),
                                                                     
                                );
   
   }  
                                                                

   query_posts($args); 
   if (have_posts()) :
   
      while (have_posts()) : the_post();
   
        $tax = "soutez";
        $terms = get_the_terms( $post->ID, $tax );
             
              if ( !empty( $terms ) ) {

                  foreach ( $terms as $term ){
                      //$i++;
                      //if($i==1){$convertor .= " + "; }
                      
                      //$convertor .= $term->name;
                      
                      //if ($count != $i){ $convertor .=' | '; }
                      
                  }
              }
              
        
        if($term->slug == "muzi-a"){
        
            $background_tym = "blue";
        
        }elseif($term->slug == "muzi-b"){
        
            $background_tym = "grey";
        
        }elseif($term->slug == "zeny"){
        
            $background_tym = "red";
        
        }elseif($term->slug == "t-ball"){
        
            $background_tym = "lightest-blue";
        
        }elseif($term->slug == "zaci"){
        
            $background_tym = "light-blue";
        
        }
        
        if(get_post_meta( get_the_ID(), 'home_match', true ) == "yes"){
        
          $background_domaci_zapas = "svetle_modra";
        
        }else{
        
          $background_domaci_zapas = "";
        
        }

         $return_string .= '<tr>';        
         
                  
         $datum = get_post_meta( get_the_ID(), 'date_of_match', true );
         $finalni_datum = date_i18n('j. n. - H:i', strtotime($datum));
         $finalni_datum_turnaj = date_i18n('j. M', strtotime($datum));                   
         
         if(get_post_meta( get_the_ID(), 'turnaj', true ) == "yes"){
            
            $return_string .= '<td style="width:180px;" class="'.$background_domaci_zapas.'">';
            $return_string .= $finalni_datum_turnaj.'</td>';
            $return_string .= '</td>';
            
         }else{
            $return_string .= '<td style="width:180px;" class="'.$background_domaci_zapas.'">';
            $return_string .= $finalni_datum;
            $return_string .= '</td>';
         }
         
         //Pokud jde o turnaj
         if(get_post_meta( get_the_ID(), 'turnaj', true ) == "yes"){
         
            $return_string .= '<td style="width: 190px; text-align: center;" class="'.$background_domaci_zapas.'"  colspan="3">';
            $return_string .= get_the_title();
            if(get_post_meta( get_the_ID(), 'turnaj_result', true )){$return_string .= "<br /><strong style='color: black; font-size: 16px;'>".get_post_meta( get_the_ID(), 'turnaj_result', true ).". místo</strong>";} 
            $return_string .= '</td>';
         
         }else{ //pokud jde o klasicky zapas
         
             $return_string .= '<td style="width: 190px;text-align: left; padding: 0;" class="'.$background_domaci_zapas.'">';    
             $return_string .= get_post_meta( get_the_ID(), 'home_team', true );
             $return_string .= '</td>';
             

             $return_string .= '<td style="width: 190px;text-align: left; padding: 0;" class="'.$background_domaci_zapas.'">';
             $return_string .= get_post_meta( get_the_ID(), 'away_team', true ); 
             $return_string .= '</td>';
            
             if( (get_post_meta( get_the_ID(), 'home_team_result', true ) != "" ) ){             
               
              $return_string .= '<td style="width: 85px;text-align: center; padding: 0;" class="'.$background_domaci_zapas.'" >';
              $return_string .= "<strong style='color: black; font-size: 16px;'>";               
              
                if(get_post_meta( get_the_ID(), 'home_team_result', true ) != "" ){
                
                   $return_string .= "&nbsp;&nbsp;&nbsp;".get_post_meta( get_the_ID(), 'home_team_result', true )." : "; 
                
                }

                if(get_post_meta( get_the_ID(), 'away_team_result', true ) != "" ){ 

                  $return_string .= get_post_meta( get_the_ID(), 'away_team_result', true ); 
                }              
               
                $return_string .= "</strong>&nbsp;&nbsp;&nbsp;";
                $return_string .= '</td>';
              
             }else{
              
              $return_string .= '<td style="width: 85px;text-align: center; padding: 0;" class="'.$background_domaci_zapas.'" >';
              $return_string .= " <strong>&nbsp;&nbsp;&nbsp;VS&nbsp;&nbsp;&nbsp;</strong> ";
              $return_string .= '</td>';

             }
             
             
         }
         
                 
         //$return_string .= '</td>';         
       
         
         $return_string .= '<td style="width: 150px;text-align: left;" class="'.$background_domaci_zapas.'">';
         
         if( (get_post_meta( get_the_ID(), 'iscore', true ) != "" ) ){
         
            $return_string .= "<a target='_blank' href='";
            $return_string .= get_post_meta( get_the_ID(), 'iscore', true );
            $return_string .= "'>iScore</a>"; 
         
         }
         
         if( (get_post_meta( get_the_ID(), 'pasos_odkaz', true ) != "" ) ){
         
            $return_string .= "<a target='_blank' href='";
            $return_string .= get_post_meta( get_the_ID(), 'pasos_odkaz', true );
            $return_string .= "'>Report</a>"; 
         
         }
                 
         $return_string .= '</td>';
         
         
         
         
         
         
         $return_string .= '</tr>';
   
      endwhile;
   
   else :
   
   $no_result = "Pro tuto sezónu nebyly zadány žádné zápasy.";
   
   endif;
   
   
   $return_string .= '</table>';
   
   $return_string .= $no_result;

   wp_reset_query();
   return $return_string;
   
}

/* Výpis nejbližšího zápasu na homepage v modrém pruhu: start; */

function nejblizsi_zapas_homepage_function($atts, $content = null) {
   
   extract(shortcode_atts(array(
      'pocet_zapasu' => 1,
      'turnaj' => false
   ), $atts));

   $return_string = '<p>';
   
  
        
        $args = array( 
                      
                      
                      'post_type' => 'zapas',
                      'orderby' => 'meta_value',
                      'meta_key'  => 'date_of_match', 
                      'order' => 'ASC' , 
                      'showposts' => $pocet_zapasu, 
                      
                    
                    
              ); 
           
           if($turnaj == "true"){
          
                  $args['meta_query'][] = array(
                      
                                            		'relation' => 'AND',
                                                                		
                                                                    array(
                                                                			'key'     => 'date_of_match',
                                                                			'value'   => date( "Y-m-d H:i"),
                                                                			'compare' => '>',
                                                                		),                        
                                                                    
                                                                   /* array(
                                                                			'key'     => 'zapas_csa',
                                                                			'value'   => 'yes',
                                                                			'compare' => '=',
                                                                		),*/

                                                                    array(
                                                                  			'key'     => 'turnaj',
                                                                  			'value'   => 'yes',
                                                                  			'compare' => '=',
                                                                  		),
                                                                    
                                                                  
                                                                    
                                                 ); 
                                                                                  
                    
                }else{
                
                    $args['meta_query'][] = array(
                      
                                            			'relation' => 'AND',
                                        		
                                                                    array(
                                                                			'key'     => 'date_of_match',
                                                                			'value'   => date( "Y-m-d H:i"),
                                                                			'compare' => '>',
                                                                		),                        
                                                                    
                                                                    array(
                                                                			'key'     => 'zapas_csa',
                                                                			'value'   => 'yes',
                                                                			'compare' => '=',
                                                                		),
                                                                    
                                                                   array(
                      
                                                                      			'relation' => 'or',
                                                                  		
                                                                                              
                                                                                             
                                                                                              
                                                                                             array(
                                                                                            			'key'     => 'turnaj',
                                                                                            			'value'   => 'no',
                                                                                            			'compare' => '=',
                                                                                            		),
                                                                                                
                                                                                              array(
                                                                                            			'key'     => 'turnaj',
                                                                                            			'value'   => 'null',
                                                                                            			'compare' => 'not exists',
                                                                                            		),
                                                                                                
                                                                                                
                                                                          
                                                                
                                                                   ),
                                                                
                                                  );
                
                
                }
                
                query_posts($args);  
  
   
   
   if (have_posts()) :
   
      while (have_posts()) : the_post();
   
         
      if( get_post_meta( get_the_ID(), 'turnaj', true ) == "yes" ){
      
                  
        //$return_string .= get_the_title();

         $return_string .= ' <div class="nejblizsi-zapasy-homepage-row">
                                  <div class="nejblizsi-zapasy-homepage et_pb_section  et_section_regular">
                                        <div class=" et_pb_row et_pb_row_0">
              
                                                <div class="et_pb_column et_pb_column_1_3  et_pb_column_0">
              
                                                        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_0 right"><p>';
              
                                                                      $return_string .= get_post_meta( get_the_ID(), 'home_team', true );
      
                                      $return_string .='</p></div> <!-- .et_pb_text -->
                                               </div> <!-- .et_pb_column -->
                                               
                                               <div class="et_pb_column et_pb_column_1_3  et_pb_column_1" style="width: 100% !important;">
              
                                                        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_1 center"><p>';
              
                                                                        //$return_string .= " <strong>VS</strong> ";
                                                                        $return_string .= get_the_title();
      
                                      $return_string .='</p> </div> <!-- .et_pb_text -->
                                               </div> <!-- .et_pb_column -->
                                               <div class="et_pb_column et_pb_column_1_3  et_pb_column_2">
              
                                                        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_2 left"><p>';
              
                                                                        $return_string .= get_post_meta( get_the_ID(), 'away_team', true );
      
                                      $return_string .='</p></div> <!-- .et_pb_text -->
                                               </div> <!-- .et_pb_column -->
                
                                        </div> <!-- .et_pb_row -->
                                        
                                  </div>';

         
      
      }else{
      
        $return_string .= ' <div class="nejblizsi-zapasy-homepage-row">
                                  <div class="nejblizsi-zapasy-homepage et_pb_section  et_section_regular">
                                        <div class=" et_pb_row et_pb_row_0">
      				
                                                <div class="et_pb_column et_pb_column_1_3  et_pb_column_0">
      				
      				                                          <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_0 right"><p>';
      				
                                                                      $return_string .= get_post_meta( get_the_ID(), 'home_team', true );
      
      		                          	$return_string .='</p></div> <!-- .et_pb_text -->
      			                                   </div> <!-- .et_pb_column -->
                                               
                                               <div class="et_pb_column et_pb_column_1_3  et_pb_column_1">
      				
      				                                          <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_1 center"><p>';
      				
                                                                        $return_string .= " <strong>VS</strong> ";
      
      			                          $return_string .='</p> </div> <!-- .et_pb_text -->
      			                                   </div> <!-- .et_pb_column -->
                                               <div class="et_pb_column et_pb_column_1_3  et_pb_column_2">
      				
      				                                          <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_2 left"><p>';
      				
                                                                        $return_string .= get_post_meta( get_the_ID(), 'away_team', true );
      
      			                          $return_string .='</p></div> <!-- .et_pb_text -->
      			                                   </div> <!-- .et_pb_column -->
      					
      			                            </div> <!-- .et_pb_row -->
      				                          
      			                      </div>';
         
         //$return_string .= get_post_meta( get_the_ID(), 'home_team', true );
         //$return_string .= " <strong>&nbsp;&nbsp;&nbsp;VS&nbsp;&nbsp;&nbsp;</strong> ";
         //$return_string .= get_post_meta( get_the_ID(), 'away_team', true );
      
      }   
         
         
   
      endwhile;
   
   
   else:
   
   $return_string .= "Nebyly zadány žádné zápasy";
   
   endif;
   
   
   $return_string .= '</p>';

   wp_reset_query();
   return $return_string;
   
}

/* Výpis nejbližšího zápasu na homepage v modrém pruhu: end; */

/* Výpis nejbližšího zápasu (datumu) na homepage v modrém pruhu: start; */

function nejblizsi_zapas_homepage_datumu_function($atts, $content = null) {
   
   extract(shortcode_atts(array(
      'pocet_zapasu' => 1,
      'turnaj' => false,
   ), $atts));

   $return_string = '<p>';

   

   if($turnaj == "true"){

        query_posts(
               array( 
                      
                      
                      'post_type' => 'zapas',
                      'orderby' => 'meta_value',
                      'meta_key'  => 'date_of_match', 
                      'order' => 'ASC' , 
                      'showposts' => $pocet_zapasu, 
                      
                      
                      'meta_query' => array(
                      
                        'relation' => 'AND',
                                            
                                            array(
                                              'key'     => 'date_of_match',
                                              'value'   => date( "Y-m-d H:i"),
                                              'compare' => '>',
                                            ),                        
                                            
                                           /* array(
                                              'key'     => 'zapas_csa',
                                              'value'   => 'yes',
                                              'compare' => '=',
                                            ),*/
                                            
                                            array(
                      
                                                                            'relation' => 'or',
                                                                      
                                                                                              
                                                                                             
                                                                                              
                                                                                             array(
                                                                                                  'key'     => 'turnaj',
                                                                                                  'value'   => 'yes',
                                                                                                  'compare' => '=',
                                                                                                ),
                                                                                                
                                                                                              array(
                                                                                                  'key'     => 'turnaj',
                                                                                                  'value'   => 'null',
                                                                                                  'compare' => 'not exists',
                                                                                                ),
                                                                                                
                                                                                                
                                                                          
                                                                
                                                                   ),
                                            
                                            
                        
                      ),
                      
                    )
                    
              );




   }else{

            query_posts(
               array( 
                      
                      
                      'post_type' => 'zapas',
                      'orderby' => 'meta_value',
                      'meta_key'  => 'date_of_match', 
                      'order' => 'ASC' , 
                      'showposts' => $pocet_zapasu, 
                      
                      
                      'meta_query' => array(
                      
                        'relation' => 'AND',
                                            
                                            array(
                                              'key'     => 'date_of_match',
                                              'value'   => date( "Y-m-d H:i"),
                                              'compare' => '>',
                                            ),                        
                                            
                                           /* array(
                                              'key'     => 'zapas_csa',
                                              'value'   => 'yes',
                                              'compare' => '=',
                                            ),*/
                                            
                                            array(
                      
                                                                            'relation' => 'or',
                                                                      
                                                                                              
                                                                                             
                                                                                              
                                                                                             array(
                                                                                                  'key'     => 'turnaj',
                                                                                                  'value'   => 'no',
                                                                                                  'compare' => '=',
                                                                                                ),
                                                                                                
                                                                                              array(
                                                                                                  'key'     => 'turnaj',
                                                                                                  'value'   => 'null',
                                                                                                  'compare' => 'not exists',
                                                                                                ),
                                                                                                
                                                                                                
                                                                          
                                                                
                                                                   ),
                                            
                                            
                        
                      ),
                      
                    )
                    
              );

   }
   



    

   
   
   if (have_posts()) :
   
      while (have_posts()) : the_post();
   
         //$return_string .= get_the_title();
         $datum = get_post_meta( get_the_ID(), 'date_of_match', true );
         $finalni_datum = date_i18n('j. M (D) - H:i', strtotime($datum)  );
         
                 
         $return_string .= $finalni_datum;
         $return_string .= " | ";
         $return_string .= get_post_meta( get_the_ID(), 'hriste', true )."<br />";
   
      endwhile;
   
   endif;
   
   
   $return_string .= '</p>';

   wp_reset_query();
  
   return $return_string;
   
   
}

/* Výpis nejbližšího zápasu (datumu) na homepage v modrém pruhu: end; */


/* Výpis nejbližšího domácího zápasu na homepage - tabulka: start; */

function nejblizsi_domaci_zapas_table_homepage_function($atts, $content = null) {
   
   extract(shortcode_atts(array(
      'pocet_zapasu' => 1,
   ), $atts));

   $return_string = '<p>';
         
    query_posts(
               array( 
                      
                      
                      'post_type' => 'zapas',
                      'orderby' => 'meta_value',
                      'meta_key'  => 'date_of_match', 
                      'order' => 'ASC' , 
                      'showposts' => $pocet_zapasu, 
                      
                      
                      'meta_query' => array(
                      
                    		'relation' => 'AND',
                    		array(
                    			'key'     => 'date_of_match',
                    			'value'   => date( "Y-m-d H:i"),
                    			'compare' => '>',
                    		),
                        
                        array(
                    			'key'     => 'home_match',
                    			'value'   => 'yes',
                    			'compare' => '=',
                    		),
                    		
                    	),
                      
                    )
                    
              );
   
              
   
   $return_string = '<table class="homepage-table-nejblizsi-zapasy"><tbody>'; 
   
   if (have_posts()) :
   
      while (have_posts()) : the_post();
   
         $datum = get_post_meta( get_the_ID(), 'date_of_match', true );
         $finalni_datum = date_i18n('j. M (D) - H:i', strtotime($datum)  );
         
                
         
         $return_string .= '<tr>';
         $return_string .= '<td class="homepage-table-nejblizsi-zapasy">';
         
         if( get_post_meta( get_the_ID(), 'turnaj', true ) == "yes"){
         
         $return_string .= get_the_title();
         
         }else{
         
         $return_string .= get_post_meta( get_the_ID(), 'home_team', true );
         $return_string .= " vs ";
         $return_string .= get_post_meta( get_the_ID(), 'away_team', true );
         
         }
         
         $return_string .= "<br> $finalni_datum ";
         $return_string .= '</td>';
         $return_string .= '</tr>';
        
   
      endwhile;
   
   else:
   
   $no_result = "<tr><td>Nejsou zadány žádné domácí zápasy pro tuto sezónu.</td></tr>";
   
   endif;
   
   $return_string .= $no_result;
   $return_string .= '<tr>';
   $return_string .= '<td class="homepage-table-nejblizsi-zapasy-zobrazit-vsechny-zapasy"><a href="/zapasy/">Zobrazit všechny zápasy</a></td>';
   $return_string .= '</tr>';  
   $return_string .= '<tbody></table>';
   
   

   wp_reset_query();
   return $return_string;
   
}

/* Výpis nejbližšího domácího zápasu na homepage - tabulka: end; */






function register_shortcodes(){

   add_shortcode('seznam_zapasu', 'zapasy_list_function');
   add_shortcode('seznam_zapasu_table', 'zapasy_list_table_function');
   add_shortcode('nejblizsi_zapas_homepage', 'nejblizsi_zapas_homepage_function'); /* Výpis nejbližšího zápasu na homepage v modrém pruhu; */
   add_shortcode('nejblizsi_zapas_datum_homepage', 'nejblizsi_zapas_homepage_datumu_function'); /* Výpis nejbližšího zápasu (datumu) na homepage v modrém pruhu; */
   add_shortcode('nejblizsi_domaci_zapas_table_homepage', 'nejblizsi_domaci_zapas_table_homepage_function'); /* Výpis nejbližšího zápasu (datumu) na homepage v modrém pruhu; */
   add_shortcode('nejblizsi_zapas_homepage2', 'nejblizsi_zapas_homepage_function2'); /* Výpis nejbližšího zápasu na homepage v modrém pruhu; */
   
   
   
}


add_action( 'init', 'register_shortcodes');


function nejblizsi_zapas_homepage_function2($atts, $content = null) {
   
   extract(shortcode_atts(array(
      'pocet_zapasu' => 1,
      'turnaj' => false
   ), $atts));

   $return_string = '<p>';
   
  
        
        $args = array( 
                      
                      
                      'post_type' => 'zapas',
                      'orderby' => 'meta_value',
                      'meta_key'  => 'date_of_match', 
                      'order' => 'ASC' , 
                      'showposts' => $pocet_zapasu, 
                      
                    
                    
              ); 
           
           if($turnaj == "true"){
          
                  $args['meta_query'][] = array(
                      
                                            		'relation' => 'AND',
                                                                		
                                                                    array(
                                                                			'key'     => 'date_of_match',
                                                                			'value'   => date( "Y-m-d H:i"),
                                                                			'compare' => '>',
                                                                		),                        
                                                                    
                                                                    array(
                                                                			'key'     => 'zapas_csa',
                                                                			'value'   => 'yes',
                                                                			'compare' => '=',
                                                                		),array(
                                                                  			'key'     => 'turnaj',
                                                                  			'value'   => 'yes',
                                                                  			'compare' => '=',
                                                                  		),
                                                                    
                                                                  
                                                                    
                                                 ); 
                                                                                  
                    
                }else{
                
                    $args['meta_query'][] = array(
                      
                                            			'relation' => 'AND',
                                        		
                                                                    array(
                                                                			'key'     => 'date_of_match',
                                                                			'value'   => date( "Y-m-d H:i"),
                                                                			'compare' => '>',
                                                                		),                        
                                                                    
                                                                    array(
                                                                			'key'     => 'zapas_csa',
                                                                			'value'   => 'yes',
                                                                			'compare' => '=',
                                                                		),
                                                                   
                                                                   
                                                                    
                                                                   array(
                      
                                                                      			'relation' => 'or',
                                                                  		
                                                                                              
                                                                                             
                                                                                              
                                                                                             array(
                                                                                            			'key'     => 'turnaj',
                                                                                            			'value'   => 'no',
                                                                                            			'compare' => '=',
                                                                                            		),
                                                                                                
                                                                                              array(
                                                                                            			'key'     => 'turnaj',
                                                                                            			'value'   => 'null',
                                                                                            			'compare' => 'not exists',
                                                                                            		),
                                                                                                
                                                                                                
                                                                          
                                                                
                                                                   ),
                                                                      
                                                                
                                                  );
                
                
                }
                
                query_posts($args);  
  
   
   
   if (have_posts()) :
   
      while (have_posts()) : the_post();
   
         
      if( get_post_meta( get_the_ID(), 'turnaj', true ) == "yes" ){
      
                  
        $return_string .= get_the_title();
         
      
      }else{
      
         $return_string .= get_post_meta( get_the_ID(), 'home_team', true );
         $return_string .= " <strong>&nbsp;&nbsp;&nbsp;VS&nbsp;&nbsp;&nbsp;</strong> ";
         $return_string .= get_post_meta( get_the_ID(), 'away_team', true );
      
      }   
         
         
   
      endwhile;
   
   
   else:
   
   $return_string .= "Nebyly zadány žádné zápasy";
   
   endif;
   
   
   $return_string .= '</p>';

   wp_reset_query();
   return $return_string;
   
}

?>