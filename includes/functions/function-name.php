<?php
function show_prijmeni($player_id = NULL){

	// zobrazí prijmeni hrace na základe ID hráče
	// pokud nemá hráč vyplněné přijmení, zobrazí celé jméno z nadpisu
	$output = NULL;
	if( get_post_meta( $player_id, 'last_name', true ) ){

		$output .= get_post_meta( $player_id, 'last_name', true );

	}else{

		$output .= get_the_title($player_id);
	}

	return $output;
}
?>