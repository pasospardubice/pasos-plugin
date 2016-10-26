<?php

// CPT PLAYERS

// register custom post type to work with
function pasos_cpt_table_create() {
	   
    $labels = array(
		'name'                => _x( 'Tabulka', 'Post Type General Name', 'pasos' ),
		'singular_name'       => _x( 'Vytvořit nový klub', 'Post Type Singular Name', 'pasos' ),
		'menu_name'           => __( 'Tabulky', 'pasos' ),
		'parent_item_colon'   => __( 'Klub', 'pasos' ),
		'all_items'           => __( 'Všechny kluby', 'pasos' ),
		'view_item'           => __( 'Zobrazit klub', 'pasos' ),
		'add_new_item'        => __( 'Vytvořit nový klub', 'pasos' ),
		'add_new'             => __( 'Vytvořit klub', 'pasos' ),
		'edit_item'           => __( 'Upravit klub', 'pasos' ),
		'update_item'         => __( 'Aktualizovat klub', 'pasos' ),
		'search_items'        => __( 'Najít klub', 'pasos' ),
		'not_found'           => __( 'Nenalezeno', 'pasos' ),
		'not_found_in_trash'  => __( 'Nenalezeno v koši', 'pasos' ),
	);
  
  
  $args = array(
		'label'               => __( 'Tabulky', 'pasos' ),
		'description'         => __( 'Tabulky', 'pasos' ),
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
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
    'rewrite' => array('slug' => 'klub'),
    //'rewrite' => true,
    'query_var' => true,
	);

  
	// Registering your Custom Post Type
	register_post_type( 'klub', $args );
  
  
}
add_action( 'init', 'pasos_cpt_table_create' );

// CPT GAMES

?>