<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/*
This is the update function for the plugin. When the plugin gets updated, the database changes are done here. This function is placed in the init of wordpress.
*/
function tm_update()
{
	global $mlwTestimonialMaster;
	$data = $mlwTestimonialMaster->version;
	if ( ! get_option('mlw_tm_version'))
	{
		add_option('mlw_tm_version' , $data);
	}
	elseif (get_option('mlw_tm_version') != $data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "mlw_tm_testimonials";
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name)
		{
			global $current_user;
			get_currentuserinfo();
			$all_data = $wpdb->get_results( "SELECT * FROM $table_name WHERE deleted=0 ORDER BY testimonial_id DESC" );
			foreach($all_data as $testimonial)
			{
  			$new_testimonial_args = array(
  			  'post_title'    => $testimonial->name,
  			  'post_content'  => $testimonial->testimonial,
  			  'post_status'   => 'publish',
  			  'post_author'   => $current_user->ID,
  			  'post_type' => 'testimonial'
  			);
  			$new_testimonial_id = wp_insert_post( $new_testimonial_args );
  			add_post_meta( $new_testimonial_id, 'link', esc_url_raw($testimonial->url), true );
			}
			$results = $wpdb->query( "DROP TABLE IF EXISTS ".$table_name );
		}
		update_option('mlw_tm_version' , $data);
		if(!isset($_GET['activate-multi']))
    {
			wp_safe_redirect( admin_url( 'index.php?page=tm_about' ) );
			exit;
    }
	}
	if ( ! get_option('mlw_advert_shows'))
	{
		add_option('mlw_advert_shows' , 'true');
	}
}
?>
