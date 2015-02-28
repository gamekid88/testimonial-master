<?php

if ( ! defined( 'ABSPATH' ) ) exit;


/**
* This class is the main class of the plugin
*
* When loaded, it loads the included plugin files and add functions to hooks or filters.
*
* @since 0.2.0
*/
class TMShortcodes
{
    /**
  	  * Main Construct Function
  	  *
  	  * Call functions within class
  	  *
  	  * @since 0.2.0
  	  * @uses TMShortcodes::load_dependencies() Loads required filed
  	  * @uses TMShortcodes::add_hooks() Adds actions to hooks and filters
  	  * @return void
  	  */
    function __construct()
    {
      $this->load_dependencies();
      $this->add_hooks();
    }

    /**
  	  * Load File Dependencies
  	  *
  	  * @since 0.2.0
  	  * @return void
  	  */
    public function load_dependencies()
    {

    }

    /**
  	  * Add Hooks
  	  *
  	  * Adds functions to relavent hooks and filters
  	  *
  	  * @since 0.2.0
  	  * @return void
  	  */
    public function add_hooks()
    {
			add_shortcode('testimonials_all', array($this, 'all_testimonials'));
			add_shortcode('testimonials_random', array($this, 'random_testimonials'));


			//Older Shortcodes Left For Legacy
			add_shortcode('mlw_tm_all', array($this, 'all_testimonials'));
			add_shortcode('mlw_tm_random', array($this, 'random_testimonials'));
    }

		/**
		 * Shortcode To Display All Testimonials
		 *
		 * @since 0.2.0
		 */
		public function all_testimonials($atts)
		{
      wp_enqueue_style( 'tm_front_style', plugins_url( '../css/front-end.css' , __FILE__ ) );
			$shortcode = '';
			$testimonial_array = array();
      $my_query = new WP_Query( array('post_type' => 'testimonial') );
    	if( $my_query->have_posts() )
    	{
    	  while( $my_query->have_posts() )
    		{
          $shortcode .= "<div class='testimonial'>";
    	    $my_query->the_post();
					$shortcode .= '"'.esc_html(get_the_content()).'"<br />';
					$shortcode .= '~'.esc_html(get_the_title());
					$link = get_post_meta( get_the_ID(), 'link', true );
					if ($link && $link != '')
					{
						$shortcode .= ", <a href='".esc_url($link)."'>".esc_html($link)."</a>";
					}
          $shortcode .= "</div>";
    	  }
    	}
    	wp_reset_postdata();
			return $shortcode;
		}

		/**
		 * Shortcode To One Random Testimonials
		 *
		 * @since 0.2.0
		 */
		public function random_testimonials($atts)
		{
      wp_enqueue_style( 'tm_front_style', plugins_url( '../css/front-end.css' , __FILE__ ) );
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
      $shortcode .= "<div class='testimonial'>";
			$shortcode .= '"'.esc_html($testimonial_array[$rand_testimonial]["content"]).'"<br />';
			$shortcode .= '~'.esc_html($testimonial_array[$rand_testimonial]["name"]);
			$link = $testimonial_array[$rand_testimonial]["link"];
			if ($link && $link != '')
			{
				$shortcode .= ", <a href='".esc_url($link)."'>".esc_html($link)."</a>";
			}
      $shortcode .= "</div>";

			return $shortcode;
		}
}
$tmShortcodes = new TMShortcodes();
?>
