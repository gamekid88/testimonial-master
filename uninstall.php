<?php
//if uninstall not called from WordPress, then exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
{
	exit();
}
$my_query = new WP_Query( array('post_type' => 'testimonial') );
if( $my_query->have_posts() )
{
  while( $my_query->have_posts() )
  {
    $my_query->the_post();
    $my_post = array(
        'post_status' => 'trash'
    );
    wp_update_post( $my_post );
  }
}
wp_reset_postdata();
global $wpdb;
$table_name = $wpdb->prefix . "mlw_tm_testimonials";
$sql = "DROP TABLE IF EXISTS ".$table_name;
$results = $wpdb->query( $sql );
delete_option('mlw_tm_version');
delete_option('mlw_advert_shows');
?>
