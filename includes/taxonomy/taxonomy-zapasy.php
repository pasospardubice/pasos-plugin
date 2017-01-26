<?php
// Taxonomy - zápas: Start;

//add_action( 'init', 'create_zapas_taxonomy_team', 0 );

//create a custom taxonomy name it topics for your posts

function create_zapas_taxonomy_team() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

  $labels = array(
    'name' => _x( 'Soutěž', 'pasos' ),
    'singular_name' => _x( 'Soutěž', 'pasos' ),
    'search_items' =>  __( 'Najít Soutěž' ),
    'all_items' => __( 'Všechny soutěže' ),
    'parent_item' => __( 'Parent Topic' ),
    'parent_item_colon' => __( 'Parent Topic:' ),
    'edit_item' => __( 'Upravit Soutěž' ), 
    'update_item' => __( 'Aktualizovat soutěž' ),
    'add_new_item' => __( 'Přidat novou soutěž' ),
    'new_item_name' => __( 'Název nové soutěže' ),
    'menu_name' => __( 'Týmy' ),
  ); 	

// Now register the taxonomy

register_taxonomy('soutez',array('zapas'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'soutez' ),
));

}

// Taxonomy - zapas: End;
?>