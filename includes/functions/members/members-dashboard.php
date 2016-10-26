<?php

/**
 * Clean dashboards: start;
 */


// Members-news:start;

function pasos_remove_dashboard_widgets() {

    $user = wp_get_current_user();

        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );       

        remove_meta_box( 'tribe_dashboard_widget', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'icl_dashboard_widget', 'dashboard', 'normal' );
        remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'normal' );
        remove_action( 'welcome_panel', 'wp_welcome_panel' );    

}

add_action( 'wp_dashboard_setup', 'pasos_remove_dashboard_widgets' );


// Members-news:end;

/**
 * Clean dashboards: end;
 */


/**
 * Add custom widget to dashboard: start;
 */


// Welcome widget: start;
 
function general_custom_dashboard_widgets(){

  global $wp_meta_boxes;

  wp_add_dashboard_widget('general_custom_dashboard_widget', 'Vítejte v administraci Pasos.cz', 'general_custom_dashboard_widgets_function');

}
add_action('wp_dashboard_setup', 'general_custom_dashboard_widgets');
function general_custom_dashboard_widgets_function() {
?>
 	<div class="welcome-panel-column-container" style="margin-top: 30px;">
		   
		   <div style="text-align: center;"><img src="https://www.pasos.cz/wp-content/uploads/2016/07/logo-oriznute-121.png" ></div>

		   <p><strong><?php _e( 'Pokud by něco nefungovalo, dejte vědět:' ); ?></strong></p>    


		   <ul>

		      <li><strong>Libor Matějka</strong></li>
		      <li>libor.matejka@pasos.cz</li>

		   </ul>   

	</div>

  <?php
}

// Welcome widget: end;
// Members-news: start;

function news_custom_dashboard_widgets(){

  global $wp_meta_boxes;

  wp_add_dashboard_widget('news_custom_dashboard_widget', 'Jak napsat novinku na web Pasos.cz?', 'news_custom_dashboard_widgets_function');

}
add_action('wp_dashboard_setup', 'news_custom_dashboard_widgets');
function news_custom_dashboard_widgets_function() {
?>
 	<div class="welcome-panel-column-container" style="margin-top: 30px;">		  

		   <p>Musíte mít pro svůj účet přidělená práva, ne každý může přidávat novinky. O práva napište administrátorovi webu.</p>   


		   <ol>

		      <li>V sekci Příspěvky klikněte na <a href="/wp-admin/post-new.php">Vytvořit příspěvěk</a>.</li>
		      <li>Vyplňte název, perex (stručný výpis příspěvku - cca 100 znaků) a hlavně text.</li>
		      <li>V hlavním obsahu můžete formátovat jako ve Wordu - tučně, podtrženě, odkazy atd. Hlavní text strukturujte do odstavců pro přehlednost.</li>
		      <li>V pravém postraním menu zařaďte novinku do Rubriky. Pokud ji necháte v rubrice Nezařazené - nezobrazí se na webu v kategorii Novinky.</li>
		      <li>V pravém postraním menu zařaďte novinku do sekce Týmy. Pro pozdější filtr novinek dle týmu.</li>
		      <li>Přidejte náhledový obrázek - velikost je 320*140px.</li>
		      <li>Poté publikujte.</li>

		   </ol>   

	</div>

  <?php
}
 
// Members-news: end;

/**
 * Add custom widget to dashboard: end;
 */

?>