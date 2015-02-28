<?php
/*
These functions are used for installing and uninstalling all necessary databases, options, page, etc.. for the plugin to work properly.
*/
function mlw_tm_activate()
{
	global $wpdb;

	$table_name = $wpdb->prefix . "mlw_tm_testimonials";

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 

	{
		//Creating the table ... fresh!

		$sql = "CREATE TABLE " . $table_name . " (

			testimonial_id mediumint(9) NOT NULL AUTO_INCREMENT,

			testimonial TEXT NOT NULL,
			
			name TEXT NOT NULL,
			
			url TEXT NOT NULL,
			
			deleted INT NOT NULL,

			PRIMARY KEY  (testimonial_id)

		);";

		$results = $wpdb->query( $sql );

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
}
function mlw_tm_deactivate()
{
	
}
?>