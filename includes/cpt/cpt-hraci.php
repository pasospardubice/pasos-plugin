<?php
function pasos_cpt_players_create() {
	   
    $labels = array(
		'name'                => _x( 'Hráč', 'Post Type General Name', 'pasos' ),
		'singular_name'       => _x( 'Vytvořit nového hráče', 'Post Type Singular Name', 'pasos' ),
		'menu_name'           => __( 'Hráči', 'pasos' ),
		'parent_item_colon'   => __( 'Hráč', 'pasos' ),
		'all_items'           => __( 'Všechny Hráči', 'pasos' ),
		'view_item'           => __( 'Zobrazit hráče', 'pasos' ),
		'add_new_item'        => __( 'Vytvořit nového hráče', 'pasos' ),
		'add_new'             => __( 'Vytvořit hráče', 'pasos' ),
		'edit_item'           => __( 'Upravit hráče', 'pasos' ),
		'update_item'         => __( 'Aktualizovat hráče', 'pasos' ),
		'search_items'        => __( 'Najít hráče', 'pasos' ),
		'not_found'           => __( 'Nenalezeno', 'pasos' ),
		'not_found_in_trash'  => __( 'Nenalezeno v koši', 'pasos' ),
	);
  
  
  $args = array(
		'label'               => __( 'Hráči', 'pasos' ),
		'description'         => __( 'Hráči', 'pasos' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title','editor', 'revisions', 'custom-fields', 'thumbnail' ),
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
		'menu_icon'            => 'dashicons-groups',
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,	
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
    'rewrite' => array('slug' => 'hrac'),
    'rewrite' => true,
    'query_var' => true,
	'publicly_queryable' => true,

	);

  
	// Registering your Custom Post Type
	register_post_type( 'hrac', $args );
  
  
}
add_action( 'init', 'pasos_cpt_players_create' );

?>