<?php
function my_login_css() {
    
    wp_register_style( 'login_wp_admin',  PASOS_CSS_PATH. 'style-login.css', '','1.0' );
    wp_enqueue_style( 'login_wp_admin' );

}
add_action( 'login_enqueue_scripts', 'my_login_css' );


function home_link() {
    return site_url();
}
add_filter('login_headerurl','home_link');

function custom_admin_logo() {
    echo '
        <style type="text/css">
            #header-logo {            
                background-image: url('.get_bloginfo('stylesheet_directory').'/images/logo.png) !important;                          
            } 
        </style>
    ';
}
add_action('admin_head', 'custom_admin_logo');
?>