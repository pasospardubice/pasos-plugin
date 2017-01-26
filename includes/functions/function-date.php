<?php
function get_match_date($match_id){


	$datum = get_post_meta( $match_id, 'date_of_match', true );
    $finalni_datum = date_i18n('l j. M - H:i', strtotime($datum));
    $finalni_datum_turnaj = date_i18n('j. M', strtotime($datum));

	return  $finalni_datum;

}

function get_tournament_date($match_id){


	$datum = get_post_meta( $match_id, 'date_of_match', true );
    $finalni_datum_turnaj = date_i18n('l j. F Y', strtotime($datum));

	return  $finalni_datum_turnaj;

}
?>