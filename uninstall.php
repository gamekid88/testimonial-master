<?php
global $wpdb;
$table_name = $wpdb->prefix . "mlw_tm_testimonials";
$sql = "DROP TABLE IF EXISTS ".$table_name;
$results = $wpdb->query( $sql );
delete_option('mlw_tm_version');
delete_option('mlw_advert_shows');
?>