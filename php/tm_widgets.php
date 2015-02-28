<?php

class TMRandomWidget extends WP_Widget {

   	// constructor
    function TMRandomWidget() {
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

   		$shortcode = '';
 			$testimonial_array = array();
       $my_query = new WP_Query( array('post_type' => 'testimonial') );
     	if( $my_query->have_posts() )
     	{
     	  while( $my_query->have_posts() )
     		{
     	    $my_query->the_post();
 					$testimonial_array[] = array(
             'id' => get_the_ID(),
             'name' => get_the_title(),
             'link' => get_post_meta( get_the_ID(), 'link', true ),
 						'content' => get_the_content()
           );
     	  }
     	}
     	wp_reset_postdata();

 			$rand_testimonial = array_rand($testimonial_array);
 			$shortcode .= '"'.$rand_testimonial["content"].'"<br />';
 			$shortcode .= '~'.$rand_testimonial["name"];
 			$link = $rand_testimonial["link"];
 			if ($link && $link != '')
 			{
 				$shortcode .= ", <a href='$link'>$link</a>";
 			}

 			echo $shortcode;
    }
}
?>
