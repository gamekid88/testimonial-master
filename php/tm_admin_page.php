<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
* This class generates the main admin page
*
* When loaded, it loads the included plugin files and add functions to hooks or filters.
*
* @since 0.2.0
*/
class TMAdminPage
{
    /**
  	  * Main Construct Function
  	  *
  	  * Call functions within class
  	  *
  	  * @since 0.2.0
  	  * @uses TMAdminPage::load_dependencies() Loads required filed
  	  * @uses TMAdminPage::add_hooks() Adds actions to hooks and filters
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

    }

		/**
		 * Generates Admin Page
		 *
		 * @since 0.2.0
		 */
		public static function generate_page()
		{
			if ( !current_user_can('moderate_comments') ) {
        echo __("You do not have proper authority to access this page",'testmonial-master');
        return '';
      }
			wp_enqueue_style( 'tm_admin_style', plugins_url( '../css/admin.css' , __FILE__ ) );
      wp_enqueue_script( 'tm_admin_script', plugins_url( '../js/admin.js' , __FILE__ ) );

			if (isset($_POST["new_testimonial"]) && wp_verify_nonce( $_POST['add_testimonial_nonce'], 'add_testimonial'))
      {
        $new_testimonial = sanitize_text_field($_POST["new_testimonial"]);
				$who = sanitize_text_field($_POST["who"]);
				$where = esc_url_raw($_POST["where"]);
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

      if (isset($_POST["edit_testimonial"]) && wp_verify_nonce( $_POST['edit_testimonial_nonce'], 'edit_testimonial'))
      {
        $testimonial_id = intval($_POST["edit_testimonial_id"]);
        $testimonial = sanitize_text_field($_POST["edit_testimonial"]);
				$who = sanitize_text_field($_POST["edit_who"]);
				$where = esc_url_raw($_POST["edit_where"]);
        $my_query = new WP_Query( array('post_type' => 'testimonial', 'p' => $testimonial_id) );
  			if( $my_query->have_posts() )
  			{
  			  while( $my_query->have_posts() )
  				{
  			    $my_query->the_post();
  					$my_post = array(
  				      'post_title'    => $who,
        			  'post_content'  => $testimonial,
  				  );
  					wp_update_post( $my_post );
            update_post_meta( get_the_ID(), 'link', $where);
  			  }
  			}
        wp_reset_postdata();
        do_action('tm_delete_tesimonial', $plugin_id);
      }

			if (isset($_POST["delete_testimonial"]) && wp_verify_nonce( $_POST['delete_testimonial_nonce'], 'delete_testimonial'))
      {
        $testimonial_id = intval($_POST["delete_testimonial"]);
        $my_query = new WP_Query( array('post_type' => 'testimonial', 'p' => $testimonial_id) );
  			if( $my_query->have_posts() )
  			{
  			  while( $my_query->have_posts() )
  				{
  			    $my_query->the_post();
  					$my_post = array(
  				      'ID'           => get_the_ID(),
  				      'post_status' => 'trash'
  				  );
  					wp_update_post( $my_post );
  			  }
  			}
        wp_reset_postdata();
        do_action('tm_delete_tesimonial', $testimonial_id);
      }

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
			?>
			<div class="wrap">
          <h2>Testimonial Master</h2>
					<?php echo tm_adverts(); ?>
          <h3><?php _e('Available Shortcodes','testmonial-master'); ?></h3>
          <div class="templates">
      			<div class="templates_shortcode">
      				<span class="templates_name">[testimonials_all]</span> - <?php _e("Outputs all of the testimonials", 'testmonial-master'); ?>
      			</div>
            <div class="templates_shortcode">
      				<span class="templates_name">[testimonials_random]</span> - <?php _e("Outputs one testimonial chosen at random", 'testmonial-master'); ?>
      			</div>
            <?php do_action('tm_extra_shortcodes'); ?>
          </div>
          <div style="clear:both;"></div>
          <br />
          <h3><?php _e('Your Testimonials','testmonial-master'); ?><a id="new_quiz_button" onclick="jQuery('#edit_testimonial_form').hide();jQuery('#new_testimonial_form').show();" href="#new_testimonial_form" class="add-new-h2">Add New Testimonial</a></h3>
          <table class="widefat">
            <thead>
              <tr>
                <th><?php _e('Name','testmonial-master'); ?></th>
                <th><?php _e('Url','testmonial-master'); ?></th>
                <th><?php _e('Testimonial','testmonial-master'); ?></th>
              </tr>
            </thead>
            <tbody id="the-list">
              <?php
              $alternate = "";
              foreach($testimonial_array as $testimonial)
              {
                if($alternate) $alternate = "";
    						else $alternate = " class=\"alternate\"";
                echo "<tr{$alternate}>";
                echo "<td>";
                  echo "<span id='name_".$testimonial["id"]."'>".esc_html($testimonial["name"])."</span>";
                  echo "<div class=\"row-actions testimonial_actions\">
                        <a class='linkOptions' onclick=\"tm_edit_testimonial(".$testimonial["id"].");\" href='#edit_testimonial_form'>".__('Edit', 'testmonial-master')."</a> |
      						      <a class='linkOptions linkDelete' onclick=\"jQuery('#want_to_delete_".$testimonial["id"]."').show();\" href='#'>".__('Delete', 'testmonial-master')."</a>
                        <div id='want_to_delete_".$testimonial["id"]."' style='display:none;'>
                          <span class='table_text'>".__('Are you sure?','testmonial-master')."</span> <a href='#' onclick=\"tm_delete_testimonial(".$testimonial["id"].");\">".__('Yes','testmonial-master')."</a> | <a href='#' onclick=\"jQuery('#want_to_delete_".$testimonial["id"]."').hide();\">".__('No','testmonial-master')."</a>
                        </div>
      						</div>";
                echo "</td>";
                echo "<td><span id='link_".$testimonial["id"]."'>".esc_url($testimonial["link"])."</span></td>";
                echo "<td><span id='content_".$testimonial["id"]."'>".esc_html($testimonial["content"])."</span></td>";
                echo "</tr>";
              }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <th><?php _e('Name','testmonial-master'); ?></th>
                <th><?php _e('Url','testmonial-master'); ?></th>
                <th><?php _e('Testimonial','testmonial-master'); ?></th>
              </tr>
            </tfoot>
          </table>
          <form action="" method="post" class="testimonial_form" id="new_testimonial_form">
            <h3><?php _e('Add New Testimonial','testmonial-master'); ?></h3>
            <div class="testimonial_form_row">
              <label class="testimonial_form_label"><?php _e("Testimonial",'testmonial-master'); ?></label>
  						<textarea class="testimonial_form_textarea" name="new_testimonial"></textarea>
            </div>
            <div class="testimonial_form_row">
  						<label class="testimonial_form_label"><?php _e("From Who",'testmonial-master'); ?></label>
              <input type="text" name="who" class="testimonial_form_input"/>
            </div>
            <div class="testimonial_form_row">
  						<label class="testimonial_form_label"><?php _e("From URL",'testmonial-master'); ?></label>
              <input type="text" name="where" class="testimonial_form_input"/>
            </div>
            <div class="testimonial_form_row">
              <input type="submit" value="<?php _e('Add Testimonial','testmonial-master'); ?>" class="button-primary testimonial_form_button"/>
              <?php wp_nonce_field('add_testimonial','add_testimonial_nonce'); ?>
            </div>
          </form>

          <form action="" method="post" class="testimonial_form" id="edit_testimonial_form" style="display:none;">
            <h3><?php _e('Edit Testimonial','testmonial-master'); ?></h3>
            <div class="testimonial_form_row">
              <label class="testimonial_form_label"><?php _e("Testimonial",'testmonial-master'); ?></label>
  						<textarea class="testimonial_form_textarea" id="edit_testimonial" name="edit_testimonial"></textarea>
            </div>
            <div class="testimonial_form_row">
  						<label class="testimonial_form_label"><?php _e("From Who",'testmonial-master'); ?></label>
              <input type="text" name="edit_who" id="edit_who" class="testimonial_form_input"/>
            </div>
            <div class="testimonial_form_row">
  						<label class="testimonial_form_label"><?php _e("From URL",'testmonial-master'); ?></label>
              <input type="text" name="edit_where" id="edit_where" class="testimonial_form_input"/>
            </div>
            <div class="testimonial_form_row">
              <input type="hidden" name="edit_testimonial_id" id="edit_testimonial_id" value="" />
              <input type="submit" value="<?php _e('Edit Testimonial','testmonial-master'); ?>" class="button-primary testimonial_form_button"/>
              <?php wp_nonce_field('edit_testimonial','edit_testimonial_nonce'); ?>
            </div>
          </form>

          <form action="" method="post" name="delete_testimonial_form" style="display:none;">
            <input type="hidden" name="delete_testimonial" id="delete_testimonial" value="" />
            <?php wp_nonce_field('delete_testimonial','delete_testimonial_nonce'); ?>
          </form>
      </div>
			<?php
		}
}
?>
