<?php
/**
 * Admin Dashboard
 *
 * @author      LiborMatějka
 * @category    Admin
 * @package     Pasos /Admin
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'PASOS_Admin_Dashboard' ) ) :

/**
 * pasos_Admin_Dashboard Class
 */
class PASOS_Admin_Dashboard {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		// Only hook in admin parts if the user has admin access
		//if ( current_user_can( 'manage_wpclubmanager' ) ) {
			add_action( 'wp_dashboard_setup', array( $this, 'init' ) );
		//}
	}

	/**
	 * Init dashboard widgets
	 */
	public function init() {

		wp_add_dashboard_widget( 'pasos_dashboard_upcoming', __( 'Nejbližší zápas', 'pasos' ), array( $this, 'upcoming_matches_widget' ) );
		add_filter( 'dashboard_glance_items', array( $this, 'glance_items' ), 10, 1 );
	}


	/**
	 * Show status widget
	 */
	public function upcoming_matches_widget() {

  			query_posts(

	             array(
                      
                      'post_type' => 'zapas',
                      'orderby' => 'meta_value',
                      'meta_key'  => 'date_of_match', 
                      'order' => 'ASC' , 
                      'showposts' => 1,                      
                      
                      'meta_query' => array(
                      
                    		
                                        		array(
                                        			'key'     => 'date_of_match',
                                        			'value'   => date( "Y-m-d H:i"),
                                        			'compare' => '>',
                                        		),
                        
                        
                    		
                    	),                      
                    )                    
             );    
    ?>
    

		<ul class="pasos-admin-matches-list">
		
		<?php
		if ( have_posts() ){
   
      while ( have_posts() ) : the_post();
    

     
    $datum = get_post_meta( get_the_ID(), 'date_of_match', true );
    $finalni_datum = date_i18n('j. M (D)', strtotime($datum)  );
    $finalni_cas = date_i18n('H:i', strtotime($datum)  );
    
    ?>
    
    <li class="pasos-matches-list-item">

					<div class="pasos-matches-list-link">
				
						<span class="pasos-matches-list-col pasos-matches-list-club1">
							<?php echo '<strong>'; 
              echo get_post_meta( get_the_ID(), 'home_team', true );
							echo '</strong>'; ?>
						</span>

						<span class="pasos-matches-list-col pasos-matches-list-status">
							<span class="pasos-matches-list-sep">
								<?php echo " VS "; ?>
							</span>
						</span>

						<span class="pasos-matches-list-col pasos-matches-list-club2">
							<?php echo get_post_meta( get_the_ID(), 'away_team', true ); 
              ?>
						</span>

					</div>

					<a href="<?php echo get_edit_post_link( get_the_ID() ); ?>" class="pasos-matches-list-additional">

						<span class="pasos-matches-list-additional-col pasos-matches-list-date">
							<?php echo $finalni_datum; ?>
						</span>

						<span class="pasos-matches-list-additional-col pasos-matches-list-status">
							<span class="pasos-matches-list-time">
								<?php echo $finalni_cas; ?>
							</span>
						</span>

						<span class="pasos-matches-list-additional-col pasos-matches-list-info">
							<?php //echo $competition; ?>
						</span>

					</a>
				
		</li>
    
    
    <?php
    endwhile;
        
		}else{ ?>

			<li><?php _e('Žádné zápasy', 'pasos'); ?></li>

		<?php 
    
    }		
    wp_reset_postdata(); 
    ?>

		</ul>

		<div class="add-new-match-link">
			<a class="button btn" href="post-new.php?post_type=zapas"><?php _e( 'Přidat nový zápas', 'pasos' ); ?></a>
		</div>

	<?php 
  }
}

endif;

return new PASOS_Admin_Dashboard();