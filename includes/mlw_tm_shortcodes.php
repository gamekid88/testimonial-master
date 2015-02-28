<?php

function mlw_tm_all_shortcode($atts)
{
	extract(shortcode_atts(array(
		'all' => 0
	), $atts));
	global $wpdb;
	$mlw_tm_display = "";
	
	
	$mlw_tm_all_data = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."mlw_tm_testimonials WHERE deleted=0 ORDER BY testimonial_id DESC" );
	foreach($mlw_tm_all_data as $mlw_tm_data) {
		$mlw_tm_display .= '"'.stripslashes(htmlspecialchars_decode($mlw_tm_data->testimonial, ENT_QUOTES)).'"';
		$mlw_tm_display .= "<br />";
		$mlw_tm_display .= "~ ".htmlspecialchars_decode($mlw_tm_data->name, ENT_QUOTES)."";
		if ( $mlw_tm_data->url != "" )
		{
			$mlw_tm_display .= ", <a style='color: blue;' href='".$mlw_tm_data->url."'>".$mlw_tm_data->url."</a>";
		}
		$mlw_tm_display .= "<br /><br /><hr /><br />";
	}
	
	
	
	return $mlw_tm_display;
}

function mlw_tm_random_shortcode($atts)
{
	extract(shortcode_atts(array(
		'amount' => 1
	), $atts));
	
	global $wpdb;
	$mlw_tm_display = "";
	
	
	$mlw_tm_all_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."mlw_tm_testimonials WHERE deleted=0 ORDER BY RAND() LIMIT %d", $amount ) );
	foreach($mlw_tm_all_data as $mlw_tm_data) {
		$mlw_tm_display .= '"'.stripslashes(htmlspecialchars_decode($mlw_tm_data->testimonial, ENT_QUOTES)).'"';
		$mlw_tm_display .= "<br />";
		$mlw_tm_display .= "~ ".htmlspecialchars_decode($mlw_tm_data->name, ENT_QUOTES)."";
		if ( $mlw_tm_data->url != "" )
		{
			$mlw_tm_display .= ", <a style='color: blue;' href='".$mlw_tm_data->url."'>".$mlw_tm_data->url."</a>";
		}
		$mlw_tm_display .= "<br /><br /><hr /><br />";
	}
	
	
	
	return $mlw_tm_display;
}
?>