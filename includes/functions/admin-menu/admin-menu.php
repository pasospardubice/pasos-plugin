<?php
function add_hraci_link(){
   global $wp_admin_bar;
 
   $wp_admin_bar->add_menu(array(
      'id'=>'hraci',
      'title' => 'Seznam hráčů',
      'href' => '/wp-admin/edit.php?post_type=hrac',
      'meta' => array( 'html' => '', 'class' => '', 'onclick' => '', 'target' => '_blank' )
   ));
   
   $wp_admin_bar->add_menu(array(
      'id'=>'seznam_zapasu',
      'title' => 'Seznam zápasů',
      'href' => '/wp-admin/edit.php?post_type=zapas',
      'meta' => array( 'html' => '', 'class' => '', 'onclick' => '', 'target' => '_blank' )
   ));
   
   $wp_admin_bar->add_menu(array(
      'id'=>'tabulky',
      'title' => 'Tabulky',
      'href' => '/wp-admin/edit.php?post_type=klub',
      'meta' => array( 'html' => '', 'class' => '', 'onclick' => '', 'target' => '_blank' )
   ));
}
add_action('wp_before_admin_bar_render', 'add_hraci_link');
?>