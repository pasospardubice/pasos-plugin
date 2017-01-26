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

if ( ! class_exists( 'PASOS_Assets' ) ) :

class PASOS_Assets {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		
		add_action( 'wp_enqueue_scripts', array( $this, 'pasos_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'pasos_scripts' ) );
	}

	/**
	 * Loads the styles for the backend.
	 *
	 * @since  0.1
	 * @access public
	 * @return void
	 */
	public function pasos_styles() {

		// Sitewide menu CSS
		wp_enqueue_style( 'pasos_style', PASOS_CSS_PATH . 'style.css', array(), PASOS_VERSION );
		do_action( 'pasos_css' );
	}

	/**
	 * Loads the scripts for the backend.
	 *
	 * @since  0.1
	 * @access public
	 * @return void
	 */
	public function pasos_scripts( $hook ) {

		global $wp_query, $post;

		$suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Register scripts
		wp_register_script( 'pasos_js', PASOS_JS_PATH . 'script.js', array( 'jquery' ), PASOS_VERSION );
	    wp_enqueue_script( 'pasos_js' );

    
	}
}

endif;

return new PASOS_Assets();