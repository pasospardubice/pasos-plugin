<?php
/**
 * Single Player - Stats Table
 *
 * @author 		Pasos
 * @package 	PAsos/Templates
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

if ( array_key_exists( $team, $stats ) ):
	if ( array_key_exists( $season, $stats[$team] ) ):
		$stats = $stats[$team][$season];
	endif;
endif;

$stats_labels = pasos_get_player_stats_labels(); 
?>
<table class="seznam-hracu-tabulka">
	<thead>
		<tr>
			<?php
			foreach( $stats_labels as $key => $val ) { 

			?>

					<td class="nadpis"><?php echo $val; ?></td>

				<?php }

			?>
		</tr>
	</thead>
	<tbody>
		<tr>
			<?php
			foreach( $stats_labels as $key => $val ) {

				if( $key == 'appearances' ) {

					

							$subs = "";
							if( $subs > 0 ) {
								$sub = ' <span class="sub-appearances">(' . $subs . ')</span>';
							}else{
								$sub = '';
							}
						 ?>
				
						<td><span data-index="appearances"><?php pasos_stats_value( $stats, 'total', 'appearances' ); ?><?php echo ( get_option( 'pasos_show_stats_subs' ) == 'yes' ? $sub : '' ); ?></span></td>

					<?php
					

				} elseif( $key == 'rating' ) {

					$rating = get_pasos_stats_value( $stats, 'total', 'rating' );
					$apps = get_pasos_stats_value( $stats, 'total', 'appearances' );
					$avrating = pasos_divide( $rating, $apps );

					if( get_option( 'pasos_show_stats_rating' ) == 'yes' ) { ?>
				
						<td><span data-index="rating"><?php echo sprintf( "%01.2f", round($avrating, 2) ); ?></span></td>

					<?php
					}

				} else { 

					?>

						<td><span data-index="<?php echo $key; ?>"><?php pasos_stats_value( $stats, 'total', $key ); ?></span></td>
						
					<?php
				}
			} ?>
			
		</tr>
	</tbody>
</table>