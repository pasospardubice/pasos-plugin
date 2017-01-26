<?php

function stats_player_add()
{
    add_meta_box( 'pasos-player-stats', __( 'Statistiky hráče', 'pasos' ), 'stats_player_box', 'hrac', 'normal', 'high' );
}

add_action( 'add_meta_boxes', 'stats_player_add' );


function stats_player_box(){

global $post;

$teams = pasos_get_ordered_post_terms( $post->ID, 'tym' ); // vrátí seznam týmů, ve kterých hráč hraje
$seasons = pasos_get_ordered_post_terms( $post->ID, 'sezona' ); // vrátí seznam sezón, ve kterém hráč hraje
$stats = get_pasos_player_stats( $post->ID );
$stats_labels = array_merge( array( 'appearances' => __( 'Apps', 'pasos' ) ), pasos_get_preset_labels() );

if( is_array( $teams ) && count($teams) > 1 ) { // pokud je hráč v sezone ve více týmech
echo "2 týmy";

    foreach( $teams as $team ) {

      $rand = rand(1,99999);
      $name = $team->name;

      if ( $team->parent ) {
          $parent_team = get_term( $team->parent, 'tym');
          $name .= ' (' . $parent_team->name . ')';
      }

      echo '<div class="pasos-profile-stats-block">';

          echo '<h4>'.$name.'</h4>';
          ?>
          <ul class="stats-tabs-<?php echo $rand; ?> stats-tabs-multi">
                
            <li class="tabs-multi">
              <a href="#wpcm_team-0_season-0-<?php echo $rand; ?>"><?php printf( __( 'Všechny %s', 'pasos' ), __( 'sezóny', 'pasos' ) ); ?></a>
            </li>

            <?php if( is_array( $seasons ) ): foreach( $seasons as $season ): ?>

              <li><a href="#wpcm_team-<?php echo $team->term_id; ?>_season-<?php echo $season->term_id; ?>"><?php echo $season->name; ?></a></li>

            <?php endforeach; endif; ?>
            
          </ul>
          <div id="wpcm_team-0_season-0-<?php echo $rand; ?>" class="tabs-panel-<?php echo $rand; ?> tabs-panel-multi">

              <?php
              pasos_player_stats_table( $stats, $team->term_id, 0 ); // tabulka pro všechny sezóny
              ?>

          </div>
          <?php 
          if( is_array( $seasons ) ): 
              foreach( $seasons as $season ): ?>
                  
                    <div id="wpcm_team-<?php echo $team->term_id; ?>_season-<?php echo $season->term_id; ?>" class="tabs-panel-<?php echo $rand; ?> tabs-panel-multi stats-table-season-<?php echo $rand; ?>" style="display: none;">
                            
                      <?php                      
                      pasos_player_stats_table( $stats, $team->term_id, $season->term_id ); // tabulka pro jednotlivé sezony 
                      ?>

                      <script type="text/javascript">

                        (function($) {

                          $('#pasos-player-stats input').change(function() {

                            index = $(this).attr('data-index');
                            value = 0;

                            $(this).closest('table').find('tbody tr').each(function() {         
                              value += parseInt($(this).find('input[data-index="' + index + '"]').val());
                            });       
                            $(this).closest('table').find('tfoot tr input[data-index="' + index + '"]').val(value);

                            <?php foreach( $stats_labels as $key => $val ) { ?>

                              var sum = 0;
                              $('.stats-table-season-<?php echo $rand; ?> .player-stats-manual-<?php echo $key; ?>').each(function(){
                                sum += Number($(this).val());
                              });
                              $('#wpcm_team-0_season-0-<?php echo $rand; ?> .player-stats-manual-<?php echo $key; ?>').val(sum);

                              var sum = 0;
                              $('.stats-table-season-<?php echo $rand; ?> .player-stats-auto-<?php echo $key; ?>').each(function(){
                                sum += Number($(this).val());
                              });
                              $('#wpcm_team-0_season-0-<?php echo $rand; ?> .player-stats-auto-<?php echo $key; ?>').val(sum);

                              var a = +$('#wpcm_team-0_season-0-<?php echo $rand; ?> .player-stats-auto-<?php echo $key; ?>').val();
                              var b = +$('#wpcm_team-0_season-0-<?php echo $rand; ?> .player-stats-manual-<?php echo $key; ?>').val();
                              var total = a+b;
                              $('#wpcm_team-0_season-0-<?php echo $rand; ?> .player-stats-total-<?php echo $key; ?>').val(total);

                            <?php } ?>
                            
                          });

                        })(jQuery);
                        
                      </script>

                    </div>
            
              <?php 

              endforeach; 
            endif; 

            echo '</div>';
          ?>

          <script type="text/javascript">
          (function($) {
            $('.stats-tabs-<?php echo $rand; ?> a').click(function(){
              var t = $(this).attr('href');
              
              $(this).parent().addClass('tabs-multi <?php echo $rand; ?>').siblings('li').removeClass('tabs-multi <?php echo $rand; ?>');
              $(this).parent().parent().parent().find('.tabs-panel-<?php echo $rand; ?>').hide();
              $(t).show();

              return false;
            });
          })(jQuery);
        
        </script>
          <?php

    }

}else{ // pokud je hrac pouze v jednom tymu
?>

Jeden tým
<div class="statsdiv">

        <ul class="pasos_stats-tabs">
            <li class="tabs"><a href="#wpcm_team-0_season-0" tabindex="3"><?php printf( __( 'Všechny %s', 'pasos' ), __( 'sezóny', 'pasos' ) ); ?></a></li>
            <?php 
            if( is_array( $seasons ) ): 
              foreach( $seasons as $season ): ?>
            <li class="hide-if-no-js22"><a href="#wpcm_team-0_season-<?php echo $season->term_id; ?>" tabindex="3"><?php echo $season->name; ?></a></li>
          <?php endforeach; endif; ?>
        </ul>
        <?php 
        if( is_array( $seasons ) ): 
          foreach( $seasons as $season ): 

        ?>
          <div id="wpcm_team-0_season-<?php echo $season->term_id; ?>" class="tabs-panel stats-table-season" style="display: none;">
            <?php          
            pasos_player_stats_table( $stats, 0, $season->term_id ); // tabulka pro jednotlivé sezony            
            ?>
            

          </div>
        <?php 
          endforeach; 
        endif; ?>
          <div id="wpcm_team-0_season-0" class="tabs-panel">
            <?php 
            pasos_player_stats_table( $stats, 0, 0 ); // tabulka pro všechny sezóny
            ?>

            <script type="text/javascript">

            (function($) {

              $('#pasos-player-stats input').change(function() {

                index = $(this).attr('data-index');
                value = 0;

                $(this).closest('table').find('tbody tr').each(function() {         
                  value += parseInt($(this).find('input[data-index="' + index + '"]').val());
                });       
                $(this).closest('table').find('tfoot tr input[data-index="' + index + '"]').val(value);

                <?php foreach( $stats_labels as $key => $val ) { ?>

                  var sum = 0;
                  $('.stats-table-season .player-stats-manual-<?php echo $key; ?>').each(function(){
                    sum += Number($(this).val());
                  });
                  $('#wpcm_team-0_season-0 .player-stats-manual-<?php echo $key; ?>').val(sum);

                  var sum = 0;
                  $('.stats-table-season .player-stats-auto-<?php echo $key; ?>').each(function(){
                    sum += Number($(this).val());
                  });
                  $('#wpcm_team-0_season-0 .player-stats-auto-<?php echo $key; ?>').val(sum);

                  var a = +$('#wpcm_team-0_season-0 .player-stats-auto-<?php echo $key; ?>').val();
                  var b = +$('#wpcm_team-0_season-0 .player-stats-manual-<?php echo $key; ?>').val();
                  var total = a+b;
                  $('#wpcm_team-0_season-0 .player-stats-total-<?php echo $key; ?>').val(total);

                <?php } ?>

              });

            })(jQuery);
            
          </script>


         </div>

</div>
<div class="clear"></div>



<?php
} 
?>

<?php    
}

add_action( 'save_post', 'stats_hrac_save' );