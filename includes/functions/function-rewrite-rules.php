<?php
function prefix_hrac_rewrite_rule() {
    add_rewrite_rule( 'hrac/([^/]+)/fotografie', 'index.php?hrac=$matches[1]&fotografie=yes', 'top' );
    add_rewrite_rule( 'hrac/([^/]+)/video', 'index.php?hrac=$matches[1]&video=yes', 'top' );
    add_rewrite_rule( 'hrac/([^/]+)/statistiky', 'index.php?hrac=$matches[1]&statistiky=yes', 'top' );
    add_rewrite_rule( 'hrac/([^/]+)/clanky', 'index.php?hrac=$matches[1]&clanky=yes', 'top' );
}
 
add_action( 'init', 'prefix_hrac_rewrite_rule' );

function prefix_register_query_var( $vars ) {
    $vars[] = 'fotografie';
    $vars[] = 'video';
    $vars[] = 'statistiky';
    $vars[] = 'clanky';
 
    return $vars;
}
 
add_filter( 'query_vars', 'prefix_register_query_var' );

function prefix_url_rewrite_templates() {
 
    if ( get_query_var( 'fotografie' ) && is_singular( 'hrac' ) ) {
        add_filter( 'template_include', function() {
            return get_stylesheet_directory() . '/single-hrac.php';
        });
    }
 
    if ( get_query_var( 'video' ) && is_singular( 'hrac' ) ) {
        add_filter( 'template_include', function() {
            return get_stylesheet_directory() . '/single-hrac.php';
        });
    }

    if ( get_query_var( 'statistiky' ) && is_singular( 'hrac' ) ) {
        add_filter( 'template_include', function() {
            return get_stylesheet_directory() . '/single-hrac.php';
        });
    }

    if ( get_query_var( 'clanky' ) && is_singular( 'hrac' ) ) {
        add_filter( 'template_include', function() {
            return get_stylesheet_directory() . '/single-hrac.php';
        });
    }

}
 
add_action( 'template_redirect', 'prefix_url_rewrite_templates' );
?>