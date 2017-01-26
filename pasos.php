<?php
/**
* Plugin Name: Pasos  
* Plugin URI: https://www.pasos.cz/
* Description:  Pasos Pardubice
* Version: 0.1
* Author: Libor Matějka
* Author URI: https://www.libor-matejka.cz/
* License: GPL2
* Tested up to: 4.7
* Requires at least: 3.2
*
* Text Domain: pasos
* Domain Path: /langugages/
*/ 


// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Define: Start;

define( 'PASOS_VERSION', '0.1' );
define( 'PASOS__MINIMUM_WP_VERSION', '3.2' );

define( 'PASOS__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PASOS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PASOS_PATH', plugin_dir_path(__FILE__));
define( 'PASOS_PLUGIN_FILE', __FILE__ );


define( 'PASOS_CLASS_PATH', PASOS_PATH . 'includes/class/');
define( 'PASOS_TEMPLATE_PATH', PASOS_PATH . 'template/');
define( 'PASOS_LANGUAGES_PATH', PASOS_PATH . 'languages/');
define( 'PASOS_FUNCTIONS_PATH', PASOS_PATH . '/includes/functions/');
define( 'PASOS_METABOXES_PATH', PASOS_PATH . '/includes/metaboxes/');
define( 'PASOS_TAXONOMY_PATH', PASOS_PATH . '/includes/taxonomy/');
define( 'PASOS_CPT_PATH', PASOS_PATH . '/includes/cpt/');
define( 'PASOS_SHORTCODE_PATH', PASOS_PATH . '/includes/shortcode/');
define( 'PASOS_IMAGES_PATH', PASOS__PLUGIN_URL . '/images/');
define( 'PASOS_CSS_PATH', PASOS__PLUGIN_URL . 'assets/css/');
define( 'PASOS_JS_PATH', PASOS__PLUGIN_URL . 'assets/js/');

// Define: End;

// Custom CPT: Start;

  require_once( PASOS_CPT_PATH . 'cpt-zapasy.php' );
  require_once( PASOS_CPT_PATH . 'cpt-table.php' );
  require_once( PASOS_CPT_PATH . 'cpt-hraci.php' );
  

// Custom CPT: End;

// Custom Taxonomy: Start;

  require_once( PASOS_TAXONOMY_PATH . 'taxonomy-zapasy.php' );
  require_once( PASOS_TAXONOMY_PATH . 'taxonomy-hraci.php' );
  require_once( PASOS_TAXONOMY_PATH . 'taxonomy-liga.php' );
  require_once( PASOS_TAXONOMY_PATH . 'taxonomy-news.php' );

// Custom Taxonomy: End;

// Function - stats : start;

  require_once( PASOS_FUNCTIONS_PATH . 'function-stats.php' );
  require_once( PASOS_FUNCTIONS_PATH . 'function-name.php' );
  require_once( PASOS_FUNCTIONS_PATH . 'function-metaboxes-player-stats.php' );
  require_once( PASOS_FUNCTIONS_PATH . 'function-rewrite-rules.php' );
  require_once( PASOS_FUNCTIONS_PATH . 'function-template.php' );
  require_once( PASOS_FUNCTIONS_PATH . 'function-date.php' );
  require_once( PASOS_FUNCTIONS_PATH . 'function-cpt.php' );
  require_once( PASOS_FUNCTIONS_PATH . 'function-get.php' );
  require_once( PASOS_FUNCTIONS_PATH . 'function-text.php' );
 
// Function - stats : start;

// Login: Start;

  require_once( PASOS_FUNCTIONS_PATH . '/login/login.php' );

// Login: End;

// Shortcode: Start;

  require_once( PASOS_SHORTCODE_PATH . 'shortcode-zapas.php' );
  require_once( PASOS_SHORTCODE_PATH . 'shortcode-table.php' );
  require_once( PASOS_SHORTCODE_PATH . 'shortcode-hraci.php' );
  require_once( PASOS_SHORTCODE_PATH . 'shortcode-news.php' );
  require_once( PASOS_SHORTCODE_PATH . 'shortcode-archive.php' );
  require_once( PASOS_SHORTCODE_PATH . 'shortcode-widget.php' );
  require_once( PASOS_SHORTCODE_PATH . 'shortcode-submenu.php' );  
  require_once( PASOS_SHORTCODE_PATH . 'shortcode-stats.php' );  
  
// Shortcode: End;

// Quick edit: Start;

  require_once( PASOS_FUNCTIONS_PATH . '/quick_edit/quick_edit_klub.php' );
  require_once( PASOS_FUNCTIONS_PATH . '/quick_edit/quick_edit_hraci.php' );

// Quick edit: End;

// Admin menu: Start;

  require_once( PASOS_FUNCTIONS_PATH . '/admin-menu/admin-menu.php' );

// Admin menu: End;

// Metaboxy: Start;

  require_once( PASOS_METABOXES_PATH . 'metaboxes-hrac.php' );
  require_once( PASOS_METABOXES_PATH . 'metaboxes-hrac-stats.php' );
  require_once( PASOS_METABOXES_PATH . 'metaboxes-zapas.php' );
  require_once( PASOS_METABOXES_PATH . 'metaboxes-zapas-stats.php' );
  require_once( PASOS_METABOXES_PATH . 'metaboxes-liga.php' );
  require_once( PASOS_METABOXES_PATH . 'metaboxes-page.php' );

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
 
// Class: Start;

  require_once( PASOS_CLASS_PATH . 'class-pasos-admin-dashboard.php' );
  require_once( PASOS_CLASS_PATH . 'class-admin-assets.php' );
  require_once( PASOS_CLASS_PATH . 'class-assets.php' );
  //require_once( PASOS_CLASS_PATH . 'class-pasos-autoloader.php' );

// Class: End;