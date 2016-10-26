<?php

add_filter( 'manage_hrac_posts_columns', 'set_custom_edit_hrac_columns' );
add_action( 'manage_hrac_posts_custom_column' , 'custom_hrac_column', 10, 2 );

function set_custom_edit_hrac_columns($columns) {
    
    unset( $columns['author'] );
    unset( $columns['date'] );

    $columns['first_name'] = __( 'Jméno', 'pasos' );
    $columns['last_name'] = __( 'Příjmení', 'pasos' );
    $columns['active_player'] = __( 'Aktivní', 'pasos' );
    $columns['age'] = __( 'Věk', 'pasos' );
    $columns['cislo_dresu'] = __( 'Číslo dresu', 'pasos' );
    $columns['pozice'] = __( 'Pozice', 'pasos' );
    $columns['palka'] = __( 'Pálka', 'pasos' );

    return $columns;
}


add_filter( 'manage_edit-hrac_sortable_columns', 'my_sortable_hrac_column' );
function my_sortable_hrac_column( $columns ) {
    
    $columns['first_name'] = 'first_name';
    $columns['last_name'] = 'last_name';
    $columns['active_player'] = 'active_player';
    $columns['age'] = 'age';
    $columns['cislo_dresu'] = 'cislo_dresu';
    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);
 
    return $columns;
}


add_action( 'pre_get_posts', 'my_hrac_orderby' );
function my_hrac_orderby( $query ) {
    
    if( ! is_admin() )
        return;
 
    $orderby = $query->get( 'orderby');
 
    if( 'age' == $orderby ) {
        $query->set('meta_key','age');
        $query->set('orderby','meta_value');
    }
    
     if( 'last_name' == $orderby ) {
        $query->set('meta_key','last_name');
        $query->set('orderby','meta_value');
    }
    
    if( 'first_name' == $orderby ) {
        $query->set('meta_key','first_name');
        $query->set('orderby','meta_value');
    }
    
    if( 'active_player' == $orderby ) {
        $query->set('meta_key','active_player');
        $query->set('orderby','meta_value');
    }
    if( 'cislo_dresu' == $orderby ) {
        $query->set('meta_key','cislo_dresu');
        $query->set('orderby','meta_value');
    }
    
    
}

function custom_hrac_column( $column, $post_id ) {
    switch ( $column ) {

        

        case 'first_name' :
            echo get_post_meta( $post_id , 'first_name' , true ); 
            break;
            
        case 'last_name' :
            echo get_post_meta( $post_id , 'last_name' , true ); 
            break;
            
        case 'cislo_dresu' :
            echo get_post_meta( $post_id , 'cislo_dresu' , true ); 
            break;    
            
        case 'age' :
            
            if( get_post_meta( $post_id , 'age' , true) ) {
                        
            echo getAge(get_post_meta( $post_id , 'age' , true )) ." let";
            
            }
                         
            break;
            
        case 'active_player' :
            
            if( (get_post_meta( $post_id , 'active_player' , true ) == "yes")){
            
            echo "Ano";
            
            }else{
            
            echo "Neaktivní";
            
            }
            
            
            break;

    }
}


/*
add_action('restrict_manage_posts', 'tsm_filter_post_type_by_taxonomy');
function tsm_filter_post_type_by_taxonomy() {
	global $typenow;
	$post_type = 'hrac'; // change to your post type
	$taxonomy  = 'soutez'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => __("Vybrat {$info_taxonomy->label}"),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};
}


add_filter('parse_query', 'tsm_convert_id_to_term_in_query');
function tsm_convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'hrac'; // change to your post type
	$taxonomy  = 'soutez'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}



function my_post_type_link_filter_function($post_link, $id = 0, $leavename = FALSE) {
  if (strpos('%author%', $post_link) === FALSE) {
    $post = &get_post($id);
    $author = get_userdata($post->post_author);
    return str_replace('%author%', $author->user_nicename, $post_link);
  }
}
add_filter('post_type_link', 'my_post_type_link_filter_function', 1, 3);
*/



/*

prepsani slugu u hracu - aby byly jedinecne slugy - DODELAT
add_filter('post_type_link', 'wpse33551_post_type_link', 1, 3);

function wpse33551_post_type_link( $link, $post = 0 ){
    
    if ( $post->post_type == 'hrac' ){
        
        $datum_hracu = get_post_meta( $post->ID , 'date_of_match' , true );
        $finalni_datum = date_i18n('Y-m-d-H-i', strtotime($datum_hracu)  );
         
        return home_url( 'hrac/' . $post->post_name ."-". $finalni_datum );
        
    } else {
        return $link;
    }
}

add_action( 'init', 'wpse33551_rewrites_init' );

function wpse33551_rewrites_init(){
    add_rewrite_rule(
        'hrac/([^/]*)/([^/]*)$',
        'index.php?post_type=hrac&p=$matches[1]','top' );
} */
?>