<?php
// Taxonomy - Hráč: Start;
add_action( 'init', 'create_player_taxonomy_team', 0 );

//create a custom taxonomy name it topics for your posts

function create_player_taxonomy_team() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

  $labels = array(
    'name' => _x( 'Tým', 'pasos' ),
    'singular_name' => _x( 'Tým', 'pasos' ),
    'search_items' =>  __( 'Najít tým' ),
    'all_items' => __( 'Všechny týmy' ),
    'parent_item' => __( 'Parent Topic' ),
    'parent_item_colon' => __( 'Parent Topic:' ),
    'edit_item' => __( 'Upravit tým' ), 
    'update_item' => __( 'Aktualizovat tým' ),
    'add_new_item' => __( 'Přidat nový tým' ),
    'new_item_name' => __( 'Název nového týmu' ),
    'menu_name' => __( 'Týmy' ),
  ); 	

// Now register the taxonomy

  register_taxonomy('tym',array('hrac'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'tym' ),
  ));

}

// Taxonomy - Hráč: End;
// 
// // Taxonomy - Hráč (Sezona): Start;
add_action( 'init', 'create_player_taxonomy_sezona', 0 );

//create a custom taxonomy name it topics for your posts

function create_player_taxonomy_sezona() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

  $labels = array(
    'name' => _x( 'Sezóna', 'pasos' ),
    'singular_name' => _x( 'Sezóna', 'pasos' ),
    'search_items' =>  __( 'Najít tým' ),
    'all_items' => __( 'Všechny sezóny' ),
    'parent_item' => __( 'Parent Topic' ),
    'parent_item_colon' => __( 'Parent Topic:' ),
    'edit_item' => __( 'Upravit tým' ), 
    'update_item' => __( 'Aktualizovat tým' ),
    'add_new_item' => __( 'Přidat novou sezóna' ),
    'new_item_name' => __( 'Název nové sezóny' ),
    'menu_name' => __( 'Sezóna' ),
  );    

// Now register the taxonomy

  register_taxonomy('sezona',array('hrac'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'sezona' ),
  ));
  
}

// Taxonomy - Hráč (Sezona): End;
?>