<?php
/**
 * Single Match - Lineup Row
 *
 * @author 		Pasos
 * @package 	Pasos/Templates
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post; ?>

<tr>
	
		<!--<th class="shirt-number"><?php echo $count; ?></th>-->
	
	
	<th class="name">		
			
			<a href="<?php echo get_permalink( $key ); ?>"><?php echo get_the_title( $key ); ?></a>
		
	</th>
	<?php
	foreach( $value as $key => $stat ) {

		if( $stat == '0' || $stat == null ) {
			$stat = '&mdash;';
		}


		if( ! in_array( $key, pasos_exclude_keys() ) and array_key_exists( $key, pasos_get_preset_labels() )  ) { ?>

			<td class="stats <?php echo $key; ?>"><?php echo $stat; ?></td>
		
		<?php
			if($key == "ab"){
				$stats_ab = $stat;
			}

			if($key == "h"){
				$stats_h = $stat;
			}

		}


	}

	if($stats_h > 0){
		$ba_nezaokrouhlene = ($stats_h/$stats_ab)*1000;
		$ba = round($ba_nezaokrouhlene,3);
	}else{
		$ba = 0;

	}
	
	echo "<td>$ba</td>";
?>

</tr>