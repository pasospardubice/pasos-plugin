<?php
function register_shortcodes_submenu(){

   add_shortcode('submenu', 'submenu_function');
     
}
function submenu_function() {

   extract(shortcode_atts(array(
   
      'type' => 'yearly',
      'show_post_count' => '12', 
      'post_type' => 'post',
      'order' => 'DESC',
      
   ), $atts));
   
   
    if(!is_front_page()){

      $args = array(
                    'post_type' => 'page',
                    'post_parent' => get_the_ID(),
                    'posts_per_page' => -1,
                    'orderby' => 'menu_order',
					'order'   => 'ASC',
                  );
      
      $children_submenus = new WP_Query($args);
      
      
      $parent_id = wp_get_post_parent_id( $post_ID );
	  $current_id = get_the_id();
      
      
      $args2 = array(
                    'post_type' => 'page',
                    'post_parent' => $parent_id,
                    'posts_per_page' => -1,

                    'orderby' => 'menu_order',
					'order'   => 'ASC',
                  );
     
      $parent_menus = new WP_Query($args2);      



		if($children_submenus->have_posts() ){
		

		 $return_string .= '<div id="header-submenu-text">

	    	<div class="container clearfix et_menu_container">
	    	

				    	<div id="et-top-sub-navigation">

				    		<nav id="top-menu-nav">
				    			 <ul id="top-menu" class="nav">

				    			 <li id="menu-item-11108" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-11108">';

				    			 		
				    			 		$return_string .= "<a href='".get_the_permalink($parent_id)."' class='parent-item'>".get_the_title($current_id).":</a> ";


				    			 		
				    			 $return_string .= "</li>"; 


				    			 
                   
                   if ( $children_submenus->have_posts() ) :

                       while ( $children_submenus->have_posts() ) : $children_submenus->the_post();
                       
                              $return_string .= "<li><a href='".get_permalink()."'>";
                              
                              if( get_post_meta( $post->ID, 'page_another_title', true ) ){
                              
                                 $return_string .= get_post_meta( $post->ID, 'page_another_title', true );
                              
                              }else{
                              
                                $return_string .= get_the_title();
                              
                              }
                              
                              
                              $return_string .=  "</a></li>";
                       
                       endwhile;
            
                	     wp_reset_postdata(); 
                
                  endif;
				    			 
				    			 



				    			 	

				    			 	

				  $return_string .=  '</ul>
				    		</nav>

				    		<a href="/nabor" class="menu-button">Nábor hráčů</a>

				    	</div>

			</div>

	    
	    </div>';


		    			  	
		}else{

			 if ($parent_id !=0){
			
				 
				$return_string .= '<div id="header-submenu-text">

						    	<div class="container clearfix et_menu_container">
						    	

									    	<div id="et-top-sub-navigation">

									    		<nav id="top-menu-nav">
									    			 <ul id="top-menu" class="nav">

									    			 <li id="menu-item-11108" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-11108">';

									    			 		
									    			 		$return_string .=  "<a href='".get_the_permalink($parent_id)."' class='parent-item'>".get_the_title($parent_id).":</a> ";
									    			 		
									    			 $return_string .= "</li>";


                          
                   
                               if ( $parent_menus->have_posts() ) :
            
                                   while ( $parent_menus->have_posts() ) : $parent_menus->the_post();
                                   
                                          $return_string .=  "<li><a href='".get_permalink()."'>";
                                          
                                          if( get_post_meta( $post->ID, 'page_another_title', true ) ){
                                          
                                             $return_string .=  get_post_meta( $post->ID, 'page_another_title', true );
                                          
                                          }else{
                                          
                                            $return_string .=  get_the_title();
                                          
                                          }
                                          
                                          
                                          $return_string .=  "</a></li>";
                                   
                                   endwhile;
                        
                            	     wp_reset_postdata(); 
                            
                              endif;
				    			 
				    			 
									    			 
									    			 



									    			 	

									    			 	

								$return_string .= '</ul>
									    		</nav>

									    		<a href="/nabor" class="menu-button">Nábor hráčů</a>

									    	</div>

								</div>

						    
						    </div>';
			
			}
		}

	}
	
   
  
  	return $return_string;
			

}





add_action( 'init', 'register_shortcodes_submenu');
?>