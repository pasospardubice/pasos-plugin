<?php
// Taxonomy - news: Start;

add_action( 'init', 'create_post_taxonomy_team', 0 );

//create a custom taxonomy name it topics for your posts

function create_post_taxonomy_team() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

  $labels = array(
    'name' => _x( 'Týmy', 'pasos' ),
    'singular_name' => _x( 'Tým', 'pasos' ),
    'search_items' =>  __( 'Najít Tým' ),
    'all_items' => __( 'Všechny Týmy' ),
    'parent_item' => __( 'Parent Topic' ),
    'parent_item_colon' => __( 'Parent Topic:' ),
    'edit_item' => __( 'Upravit Tým' ), 
    'update_item' => __( 'Aktualizovat Tým' ),
    'add_new_item' => __( 'Přidat novou Tým' ),
    'new_item_name' => __( 'Název nové Tým' ),
    'menu_name' => __( 'Týmy' ),
  ); 	

// Now register the taxonomy

  register_taxonomy('pasos-tym',array('post'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'pasos-tym' ),
  ));

}

// Taxonomy - news: End;
?>