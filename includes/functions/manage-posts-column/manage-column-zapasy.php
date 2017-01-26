<?php

add_filter( 'manage_zapas_posts_columns', 'set_custom_edit_zapas_columns' );
add_action( 'manage_zapas_posts_custom_column' , 'custom_zapas_column', 10, 2 );

function set_custom_edit_zapas_columns($columns) {
    
    unset( $columns['author'] );
    unset( $columns['date'] );

    $columns['zapas_sezona'] = __( 'Sezóna', 'pasos' );
    $columns['date_of_match'] = __( 'Datum zápasu', 'pasos' );
    $columns['vysledek'] = __( 'Výsledek', 'pasos' );
   


    return $columns;
}


add_filter( 'manage_edit-zapas_sortable_columns', 'my_sortable_zapas_column' );
function my_sortable_zapas_column( $columns ) {
    $columns['date_of_match'] = 'date_of_match';
    $columns['zapas_sezona'] = 'sezona';
    $columns['soutez'] = 'soutez';
 
    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);
 
    return $columns;
}


add_action( 'pre_get_posts', 'my_zapas_orderby' );
function my_zapas_orderby( $query ) {
    if( ! is_admin() )
        return;
 
    $orderby = $query->get( 'orderby');
 
    if( 'date_of_match' == $orderby ) {
        $query->set('meta_key','date_of_match');
        $query->set('orderby','meta_value');
    }
}

function custom_zapas_column( $column, $post_id ) {
    switch ( $column ) {

        

        case 'zapas_sezona' :
            $seasons = get_the_terms( $post_id, 'sezona' ); 
            if( $seasons ){
                foreach($seasons as $season){
                echo $season->name;
                }
            }
            //echo get_post_meta( $post_id , 'zapas_sezona' , true ); 
            break;
            
        case 'date_of_match' :
            echo get_post_meta( $post_id , 'date_of_match' , true ); 
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



add_action('restrict_manage_posts', 'tsm_filter_post_type_by_taxonomy');
function tsm_filter_post_type_by_taxonomy() {
	global $typenow;
	$post_type = 'zapas'; // change to your post type
	$taxonomy  = 'soutez'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
        $label_taxonomy = $info_taxonomy->label;
        $taxonomy_label = "Vybrat ".$label_taxonomy;
		wp_dropdown_categories(array(
			'show_option_all' => __($taxonomy_label),
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
	$post_type = 'zapas'; // change to your post type
	$taxonomy  = 'soutez'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}



function my_post_type_link_filter_function($post_link, $id = 0, $leavename = FALSE) {
  if (strpos('%author%', $post_link) === FALSE) {
    //$post = &get_post($id);
    $post = get_post($id);
    
    $author = get_userdata($post->post_author);
    return str_replace('%author%', $author->user_nicename, $post_link);
  }
}
add_filter('post_type_link', 'my_post_type_link_filter_function', 1, 3);




/*

prepsani slugu u zapasu - aby byly jedinecne slugy - DODELAT
add_filter('post_type_link', 'wpse33551_post_type_link', 1, 3);

function wpse33551_post_type_link( $link, $post = 0 ){
    
    if ( $post->post_type == 'zapas' ){
        
        $datum_zapasu = get_post_meta( $post->ID , 'date_of_match' , true );
        $finalni_datum = date_i18n('Y-m-d-H-i', strtotime($datum_zapasu)  );
         
        return home_url( 'zapas/' . $post->post_name ."-". $finalni_datum );
        
    } else {
        return $link;
    }
}

add_action( 'init', 'wpse33551_rewrites_init' );

function wpse33551_rewrites_init(){
    add_rewrite_rule(
        'zapas/([^/]*)/([^/]*)$',
        'index.php?post_type=zapas&p=$matches[1]','top' );
} */
?>