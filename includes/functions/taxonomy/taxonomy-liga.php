<?php
// Taxonomy - nazev ligy: Start;

add_action( 'init', 'create_nazev_ligy_taxonomy_klub', 0 );

//create a custom taxonomy name it topics for your posts

function create_nazev_ligy_taxonomy_klub() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

  $labels = array(
    'name' => _x( 'Liga', 'pasos' ),
    'singular_name' => _x( 'Liga', 'pasos' ),
    'search_items' =>  __( 'Najít ligu' ),
    'all_items' => __( 'Všechny ligy' ),
    'parent_item' => __( 'Parent Topic' ),
    'parent_item_colon' => __( 'Parent Topic:' ),
    'edit_item' => __( 'Upravit ligu' ), 
    'update_item' => __( 'Aktualizovat ligu' ),
    'add_new_item' => __( 'Přidat novou ligu' ),
    'new_item_name' => __( 'Název nové ligu' ),
    'menu_name' => __( 'Ligy' ),
  ); 	

// Now register the taxonomy

  register_taxonomy('liga',array('klub'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'liga' ),
  ));

}

// Taxonomy - zapas: End;
?>