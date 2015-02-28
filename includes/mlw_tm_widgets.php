<?php
/*
This is the file that contains all the widgets for the plugin
*/

class Mlw_Tm_Random_Widget extends WP_Widget {
   	
   	// constructor
    function Mlw_Tm_Random_Widget() {
        parent::WP_Widget(false, $name = __('Testimonial Master Widget', 'mlw_tm_text_domain'));
    }
    
    // widget form creation
    function form($instance) { 
	    // Check values
		if( $instance) {
	     	$title = esc_attr($instance['title']);
		} else {
			$title = '';
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'mlw_tm_text_domain'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<?php
	}
	
    // widget update
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
      	// Fields
      	$instance['title'] = strip_tags($new_instance['title']);
     	return $instance;
    }
    
    // widget display
    function widget($args, $instance) {
        extract( $args );
   		// these are the widget options
   		$title = apply_filters('widget_title', $instance['title']);
    	echo $before_widget;
   		// Display the widget
   		echo '<div class="widget-text wp_widget_plugin_box">';
   		// Check if title is set
   		if ( $title ) {
      		echo $before_title . $title . $after_title;
   		}
   		
   		global $wpdb;
		$mlw_tm_all_data = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."mlw_tm_testimonials WHERE deleted=0 ORDER BY RAND() LIMIT 1" );
		foreach($mlw_tm_all_data as $mlw_tm_data) {
			$mlw_tm_all_data = $mlw_tm_data;
			break;
		}
		$mlw_tm_widget_display = "";
		$mlw_tm_widget_display .= '"'.stripslashes(htmlspecialchars_decode($mlw_tm_all_data->testimonial, ENT_QUOTES)).'"';
		$mlw_tm_widget_display .= "<br />";
		$mlw_tm_widget_display .= "~ ".htmlspecialchars_decode($mlw_tm_all_data->name, ENT_QUOTES)."";
		if ( $mlw_tm_all_data->url != "" )
		{
			$mlw_tm_widget_display .= ", <a style='color: blue;' href='".$mlw_tm_all_data->url."'>".$mlw_tm_all_data->url."</a>";
		}
		
		echo $mlw_tm_widget_display;
   		echo '</div>';
   		echo $after_widget;
    }
}
?>