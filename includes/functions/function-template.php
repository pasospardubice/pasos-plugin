<?php
function pasos_get_template_part( $slug, $name = '' ) {

	$template = '';

	// Look in yourtheme/slug-name.php and yourtheme/pasos/slug-name.php
	if ( $name )
		$template = locate_template( array ( "{$slug}-{$name}.php", WPCM()->template_path() . "{$slug}-{$name}.php" ) );

	// Get default slug-name.php
	if ( !$template && $name && file_exists( WPCM()->plugin_path() . "/templates/{$slug}-{$name}.php" ) )
		$template = WPCM()->plugin_path() . "/templates/{$slug}-{$name}.php";

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/pasos/slug.php
	if ( !$template )
		$template = locate_template( array ( "{$slug}.php", WPCM()->template_path() . "{$slug}.php" ) );

	// Allow 3rd party plugin filter template file from their plugin
	$template = apply_filters( 'pasos_get_template_part', $template, $slug, $name );

	if ( $template )
		load_template( $template, false );
}

function pasos_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

	if ( $args && is_array($args) )
		extract( $args );

	$located = pasos_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '1.3' );
		return;
	}

	// Allow 3rd party plugin filter template file from their plugin
	$located = apply_filters( 'pasos_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( 'pasos_before_template_part', $template_name, $template_path, $located, $args );

	include( $located );

	do_action( 'pasos_after_template_part', $template_name, $template_path, $located, $args );
}

function pasos_locate_template( $template_name, $template_path = '', $default_path = '' ) {

	if ( ! $template_path ) {
		//$template_path = WPCM_TEMPLATE_PATH;
		$template_path = PASOS__PLUGIN_DIR;
			
	}
	if ( ! $default_path ) {
		$default_path = PASOS__PLUGIN_DIR . 'templates/';
	}
	// Look within passed path within the theme - this is priority
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name
		)
	);

	// Get default template
	if ( ! $template || WPCM_TEMPLATE_DEBUG_MODE )
		$template = $default_path . $template_name;

	// Return what we found
	return apply_filters('pasos_locate_template', $template, $template_name, $template_path);
}

function player_profile_submenu_items($player_id, $submenu_item){ 

	// zjisti, jestli má zobrazit položku v submenu u hráčového profilu - články, fotogalerie, statistiky, video 
	// player id = id hráče
	// submenu_item - jestli má zjistit, jestli existují clánky, fotogalerie, statistiky, video atd...

	if ($player_id) {
		
		switch ($submenu_item) {
				case 'clanky':
						
						


					break;
				
				default:
					# code...
					break;
			}

			# code...
	}else{
		return false;
	}	


	return true;

}
?>