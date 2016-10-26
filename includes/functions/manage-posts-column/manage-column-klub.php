<?php

add_filter( 'manage_klub_posts_columns', 'set_custom_edit_klub_columns' );
add_action( 'manage_klub_posts_custom_column' , 'custom_klub_column', 10, 2 );

function set_custom_edit_klub_columns($columns) {
    
    unset( $columns['author'] );
    unset( $columns['date'] );

    $columns['klub_sezona'] = __( 'Sezóna', 'pasos' );
    $columns['home_klub_win'] = __( 'Výhry', 'pasos' );
    $columns['home_klub_lose'] = __( 'Prohry', 'pasos' );
    $columns['klub_points'] = __( 'Body', 'pasos' );
    $columns['klub_sezona'] = __( 'Sezóna', 'pasos' );
    $columns['skore_win'] = __( 'Skóre W', 'pasos' );
    $columns['skore_lose'] = __( 'Skóre L', 'pasos' );

    return $columns;
}


add_filter( 'manage_edit-klub_sortable_columns', 'my_sortable_klub_column' );
function my_sortable_klub_column( $columns ) {
    $columns['home_klub_win'] = 'home_klub_win';
    $columns['home_klub_lose'] = 'home_klub_lose';
    $columns['klub_points'] = 'klub_points';
    $columns['skore_win'] = 'skore_win';
    $columns['skore_lose'] = 'skore_lose';
    $columns['klub_sezona'] = 'klub_sezona';
    
    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);
 
    return $columns;
}


add_action( 'pre_get_posts', 'my_klub_orderby' );
function my_klub_orderby( $query ) {
    if( ! is_admin() )
        return;
 
    $orderby = $query->get( 'orderby');
 
    if( 'date_of_match' == $orderby ) {
        $query->set('meta_key','date_of_match');
        $query->set('orderby','meta_value');
    }
}

function custom_klub_column( $column, $post_id ) {
    switch ( $column ) {

        

        case 'home_klub_win' :
            echo get_post_meta( $post_id , 'home_klub_win' , true ); 
            break;
            
        case 'home_klub_lose' :
            echo get_post_meta( $post_id , 'home_klub_lose' , true ); 
            break;
            
        case 'klub_points' :
            echo get_post_meta( $post_id , 'klub_points' , true ); 
            break;
            
        case 'klub_sezona' :
            echo get_post_meta( $post_id , 'klub_sezona' , true ); 
            break;
                
        case 'vysledek' :
            
            if( (get_post_meta( $post_id , 'home_team_result' , true ) != "") and (get_post_meta( $post_id , 'away_team_result' , true ) != "") ){
            
                echo get_post_meta( $post_id , 'home_team_result' , true )." : ".get_post_meta( $post_id , 'away_team_result' , true ); 
            
            }else{
            
              echo "-";
            
            }
            
            
            break;

    }
}


/*
add_action('restrict_manage_posts', 'tsm_filter_post_type_by_taxonomy');
function tsm_filter_post_type_by_taxonomy() {
	global $typenow;
	$post_type = 'klub'; // change to your post type
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
	$post_type = 'klub'; // change to your post type
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




/*

prepsani slugu u klubu - aby byly jedinecne slugy - DODELAT
add_filter('post_type_link', 'wpse33551_post_type_link', 1, 3);

function wpse33551_post_type_link( $link, $post = 0 ){
    
    if ( $post->post_type == 'klub' ){
        
        $datum_klubu = get_post_meta( $post->ID , 'date_of_match' , true );
        $finalni_datum = date_i18n('Y-m-d-H-i', strtotime($datum_klubu)  );
         
        return home_url( 'klub/' . $post->post_name ."-". $finalni_datum );
        
    } else {
        return $link;
    }
}

add_action( 'init', 'wpse33551_rewrites_init' );

function wpse33551_rewrites_init(){
    add_rewrite_rule(
        'klub/([^/]*)/([^/]*)$',
        'index.php?post_type=klub&p=$matches[1]','top' );
} */
?>