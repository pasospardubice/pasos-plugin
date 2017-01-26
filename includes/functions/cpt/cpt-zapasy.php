<?php
// register custom post type to work with
function pasos_cpt_match_create() {
	   
    $labels = array(
		'name'                => _x( 'Zápas', 'Post Type General Name', 'pasos' ),
		'singular_name'       => _x( 'Vytvořit nový zápas', 'Post Type Singular Name', 'pasos' ),
		'menu_name'           => __( 'Zápasy', 'pasos' ),
		'parent_item_colon'   => __( 'Zápas', 'pasos' ),
		'all_items'           => __( 'Všechny Zápasy', 'pasos' ),
		'view_item'           => __( 'Zobrazit Zápas', 'pasos' ),
		'add_new_item'        => __( 'Vytvořit nového Zápasu', 'pasos' ),
		'add_new'             => __( 'Vytvořit Zápas', 'pasos' ),
		'edit_item'           => __( 'Upravit Zápas', 'pasos' ),
		'update_item'         => __( 'Aktualizovat Zápasy', 'pasos' ),
		'search_items'        => __( 'Najít Zápas', 'pasos' ),
		'not_found'           => __( 'Nenalezeno', 'pasos' ),
		'not_found_in_trash'  => __( 'Nenalezeno v koši', 'pasos' ),
	);
  
  
  $args = array(
		'label'               => __( 'Zápasy', 'pasos' ),
		'description'         => __( 'Zápasy', 'pasos' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title','editor', 'revisions', 'custom-fields' ),
		// You can associate this CPT with a taxonomy or custom taxonomy. 
		//'taxonomies'          => array( 'genres' ),
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/	
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_icon'           => 'dashicons-awards',
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
    'rewrite' => array('slug' => 'zapas'),
    //'rewrite' => true,
    'query_var' => true,
	);

  
	// Registering your Custom Post Type
	register_post_type( 'zapas', $args );
  
  
}
add_action( 'init', 'pasos_cpt_match_create' );
?>