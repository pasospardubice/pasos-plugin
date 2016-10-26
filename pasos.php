<?php
/**
* Plugin Name: Pasos  
* Plugin URI: https://www.pasos.cz/
* Description:  Pasos Pardubice
* Version: 0.1
* Author: Libor Matějka
* Author URI: https://www.libor-matejka.cz/
* License: GPL2
*/ 


// Exit if accessed directly
if (!defined('ABSPATH'))   
    exit;

// Define: Start;

define( 'PASOS_VERSION', '0.1' );
define( 'PASOS__MINIMUM_WP_VERSION', '3.2' );

define( 'PASOS__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PASOS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PASOS_PATH', plugin_dir_path(__FILE__));

define( 'PASOS_LANGUAGES_PATH', PASOS_PATH . '/languages/');
define( 'PASOS_FUNCTIONS_PATH', PASOS_PATH . '/includes/functions/');
define( 'PASOS_IMAGES_PATH', PASOS__PLUGIN_URL . '/images/');
define( 'PASOS_CSS_PATH', PASOS__PLUGIN_URL . '/assets/css/');
define( 'PASOS_JS_PATH', PASOS__PLUGIN_URL . 'assets/js/');

// Define: End;

// Custom CPT: Start;

  require_once( PASOS_FUNCTIONS_PATH . '/cpt/cpt-zapasy.php' );
  require_once( PASOS_FUNCTIONS_PATH . '/cpt/cpt-table.php' );
  require_once( PASOS_FUNCTIONS_PATH . '/cpt/cpt-hraci.php' );
  

// Custom CPT: End;

// Custom Taxonomy: Start;

  require_once( PASOS_FUNCTIONS_PATH . '/taxonomy/taxonomy-zapasy.php' );
  require_once( PASOS_FUNCTIONS_PATH . '/taxonomy/taxonomy-hraci.php' );
  require_once( PASOS_FUNCTIONS_PATH . '/taxonomy/taxonomy-liga.php' );
  require_once( PASOS_FUNCTIONS_PATH . '/taxonomy/taxonomy-news.php' );

// Custom Taxonomy: End;

// Custom login: Start;

  require_once( PASOS_FUNCTIONS_PATH . 'custom_login.php' );

// Custom login: End;

// Shortcode: Start;

  require_once( PASOS_FUNCTIONS_PATH . '/shortcode/shortcode-zapas.php' );
  require_once( PASOS_FUNCTIONS_PATH . '/shortcode/shortcode-table.php' );
  require_once( PASOS_FUNCTIONS_PATH . '/shortcode/shortcode-hraci.php' );
  require_once( PASOS_FUNCTIONS_PATH . '/shortcode/shortcode-news.php' );
  require_once( PASOS_FUNCTIONS_PATH . '/shortcode/shortcode-archive.php' );
  require_once( PASOS_FUNCTIONS_PATH . '/shortcode/shortcode-widget.php' );
 require_once( PASOS_FUNCTIONS_PATH . '/shortcode/shortcode-submenu.php' );  
  
// Shortcode: End;

// Quick edit: Start;

  require_once( PASOS_FUNCTIONS_PATH . '/quick_edit/quick_edit_klub.php' );
  require_once( PASOS_FUNCTIONS_PATH . '/quick_edit/quick_edit_hraci.php' );

// Quick edit: End;

// Admin menu: Start;

  require_once( PASOS_FUNCTIONS_PATH . '/admin-menu/admin-menu.php' );

// Admin menu: End;

// Metaboxy: Start;

  require_once( PASOS_FUNCTIONS_PATH . 'metaboxes/metaboxes-hrac.php' );
  require_once( PASOS_FUNCTIONS_PATH . 'metaboxes/metaboxes-zapas.php' );
  require_once( PASOS_FUNCTIONS_PATH . 'metaboxes/metaboxes-liga.php' );
  require_once( PASOS_FUNCTIONS_PATH . 'metaboxes/metaboxes-page.php' );

// Metaboxy: End;


// Manage Column: Start;

  require_once( PASOS_FUNCTIONS_PATH . 'manage-posts-column/manage-column-zapasy.php' );
  require_once( PASOS_FUNCTIONS_PATH . 'manage-posts-column/manage-column-hrac.php' );
  require_once( PASOS_FUNCTIONS_PATH . 'manage-posts-column/manage-column-klub.php' );


// Metaboxy: End;

// Members-Roles: Start;

  require_once( PASOS_FUNCTIONS_PATH . 'members/members-news.php' );
  require_once( PASOS_FUNCTIONS_PATH . 'members/members-dashboard.php' );

// Members-Roles: End;