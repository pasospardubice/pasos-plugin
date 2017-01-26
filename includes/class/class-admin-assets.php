<?php
/**
 * Load assets.
 *
 * @author 		LiborMatějka
 * @category 	Admin
 * @package 	Pasos/Admin
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'PASOS_Admin_Assets' ) ) :

class PASOS_Admin_Assets {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Loads the styles for the backend.
	 *
	 * @since  0.1
	 * @access public
	 * @return void
	 */
	public function admin_styles() {

		// Sitewide menu CSS
		wp_enqueue_style( 'pasos_admin_menu_styles', PASOS_CSS_PATH . 'admin.css', array(), PASOS_VERSION );
		do_action( 'pasos_admin_css' );
	}

	/**
	 * Loads the scripts for the backend.
	 *
	 * @since  0.1
	 * @access public
	 * @return void
	 */
	public function admin_scripts( $hook ) {

		global $wp_query, $post;

		$suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Register scripts
		wp_register_script( 'pasos_admin_js', PASOS_JS_PATH . 'admin.js', array( 'jquery' ), PASOS_VERSION );		
		wp_register_script( 'pasos_ajax_chosen', PASOS_JS_PATH . 'ajax-chosen.jquery.js', array('jquery', 'chosen'), PASOS_VERSION );
		wp_register_script( 'pasos_chosen', PASOS_JS_PATH . 'chosen.jquery.js', array('jquery'), PASOS_VERSION );

	    wp_enqueue_script( 'pasos_admin_js' );
		wp_enqueue_script( 'pasos_ajax_chosen' );
		wp_enqueue_script( 'pasos_chosen' );
    
	}
}

endif;

return new PASOS_Admin_Assets();