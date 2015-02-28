<?php

if ( ! defined( 'ABSPATH' ) ) exit;


/**
* This class generates the shortcodes
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
      add_shortcode('testimonials_submit', array($this, 'user_submission'));
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
          $shortcode_each = "<div class='testimonial'>";
    	    $my_query->the_post();
					$shortcode_each .= '"'.esc_html(get_the_content()).'"<br />';
					$shortcode_each .= '~'.esc_html(get_the_title());
					$link = get_post_meta( get_the_ID(), 'link', true );
					if ($link && $link != '')
					{
						$shortcode_each .= ", <a href='".esc_url($link)."'>".esc_html($link)."</a>";
					}
          $shortcode_each .= "</div>";

          $shortcode .= apply_filters('tm_display_testimonial', $shortcode_each, get_the_ID());
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
      if (count($testimonial_array) > 0)
      {
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
      }
      return apply_filters('tm_display_testimonial', $shortcode, $testimonial_array[$rand_testimonial]["id"]);
		}

    /**
     * Shortcode To Allow Users To Leave Testimonials
     *
     * @since 0.2.0
     */
    public function user_submission()
    {
      $shortcode = '';
      wp_enqueue_style( 'tm_front_style', plugins_url( '../css/front-end.css' , __FILE__ ) );
      if (isset($_POST["tm_new_testimonial"]) && wp_verify_nonce( $_POST['add_testimonial_nonce'], 'add_testimonial'))
      {
        $new_testimonial = sanitize_text_field($_POST["tm_new_testimonial"]);
				$who = sanitize_text_field($_POST["tm_who"]);
				$where = esc_url_raw($_POST["tm_where"]);
        global $current_user;
  			get_currentuserinfo();
  			$new_testimonial_args = array(
  			  'post_title'    => $who,
  			  'post_content'  => $new_testimonial,
  			  'post_status'   => 'publish',
  			  'post_author'   => $current_user->ID,
  			  'post_type' => 'testimonial'
  			);
  			$new_testimonial_id = wp_insert_post( $new_testimonial_args );
  			add_post_meta( $new_testimonial_id, 'link', $where, true );
        do_action('tm_new_testimonial', $new_testimonial_id);
      }
      ob_start();
      ?>
      <form action='' method='post' class='testimonial_form' id='new_testimonial_form'>
        <h3><?php _e('Add New Testimonial','testmonial-master'); ?></h3>
        <div class='testimonial_form_row'>
          <label class='testimonial_form_label'><?php _e("Your Testimonial",'testmonial-master'); ?></label>
          <textarea class='testimonial_form_textarea' name='tm_new_testimonial'></textarea>
        </div>
        <div class='testimonial_form_row'>
          <label class='testimonial_form_label'><?php _e("Your Name",'testmonial-master'); ?></label>
          <input type='text' name='tm_who' class='testimonial_form_input'/>
        </div>
        <div class='testimonial_form_row'>
          <label class='testimonial_form_label'><?php _e("Your Website",'testmonial-master'); ?></label>
          <input type='text' name='tm_where' class='testimonial_form_input'/>
        </div>
        <?php do_action('tm_new_testimonial_form'); ?>
        <div class='testimonial_form_row'>
          <input type='submit' value='<?php _e('Add Testimonial','testmonial-master'); ?>' class='button-primary testimonial_form_button'/>
          <?php wp_nonce_field('add_testimonial','add_testimonial_nonce'); ?>
        </div>
      </form>
      <?php
      $shortcode = ob_get_contents();
      ob_end_clean();
      return $shortcode;
    }
}
$tmShortcodes = new TMShortcodes();
?>
