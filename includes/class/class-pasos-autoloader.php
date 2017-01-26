<?php
/**
 * WP Club Manager Autoloader
 *
 * @class 		PASOS_Autoloader
 * @version		0.1
 * @package		PASOS/Classes/
 * @category	Class
 * @author 		LiborMatějka
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pasos_Autoloader{

	/**
	 * Path to the includes directory
	 * @var string
	 */
	private $include_path = '';

	/**
	 * The Constructor
	 */
	public function __construct() {
		
		if ( function_exists( "__autoload" ) ) {
			spl_autoload_register( "__autoload" );
		}

		spl_autoload_register( array( $this, 'autoload' ) );

		$this->include_path = untrailingslashit( plugin_dir_path( PASOS_PLUGIN_FILE ) ) . '/includes/class/';	
	}

	/**
	 * Take a class name and turn it into a file name
	 * @param  string $class
	 * @return string
	 */
	private function get_file_name_from_class( $class ) {
		return 'class-' . str_replace( '_', '-', strtolower( $class ) ) . '.php';
	}

	/**
	 * Include a class file
	 * @param  string $path
	 * @return bool successful or not
	 */
	private function load_file( $path ) {
		if ( $path && is_readable( $path ) ) {
			include_once( $path );
			return true;
		}
		return false;
	}

	/**
	 * Auto-load WC classes on demand to reduce memory consumption.
	 *
	 * @param string $class
	 */
	public function autoload( $class ) {
		$class = strtolower( $class );
		$file  = $this->get_file_name_from_class( $class );
		$path  = '';

		if ( strpos( $class, 'wpcm_shortcode_' ) === 0 ) {
			$path = $this->include_path . 'shortcodes/';
		} elseif ( strpos( $class, 'wpcm_meta_box' ) === 0 ) {
			$path = $this->include_path . 'admin/post-types/meta-boxes/';
		} elseif ( strpos( $class, 'wpcm_admin' ) === 0 ) {
			$path = $this->include_path . 'admin/';
		}


		if ( empty( $path ) || ( ! $this->load_file( $path . $file ) && strpos( $class, 'pasos_' ) === 0 ) ) {
			$this->load_file( $this->include_path . $file );
			//echo $this->include_path . $file . "<br>";
		}

	}
}

new Pasos_Autoloader();
