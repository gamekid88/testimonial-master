<?php
/**
* This class is the main class of the plugin
*
* When loaded, it loads the included plugin files and add functions to hooks or filters.
*
* @since 0.1.0
*/
class TMAdminPage
{
    /**
  	  * Main Construct Function
  	  *
  	  * Call functions within class
  	  *
  	  * @since 0.1.0
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
  	  * @since 0.1.0
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
  	  * @since 0.1.0
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
        echo __("You do not have proper authority to access this page",'wordpress-developer-toolkit');
        return '';
      }
			wp_enqueue_style( 'tm_admin_style', plugins_url( '../css/admin.css' , __FILE__ ) );
      wp_enqueue_script( 'tm_admin_script', plugins_url( '../js/admin.js' , __FILE__ ) );

			if (isset($_POST["new_testimonial"]) && wp_verify_nonce( $_POST['add_testimonial_nonce'], 'add_testimonial'))
      {
        $new_testimonial = sanitize_text_field($_POST["new_testimonial"]);
				$who = sanitize_text_field($_POST["who"]);
				$where = sanitize_text_field($_POST["where"]);
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
        do_action('tm_new_testimonial', $plugin_info);
      }

      if (isset($_POST["edit_testimonial"]) && wp_verify_nonce( $_POST['edit_testimonial_nonce'], 'edit_testimonial'))
      {
        $testimonial = sanitize_text_field($_POST["edit_testimonial"]);
				$who = sanitize_text_field($_POST["who"]);
				$where = sanitize_text_field($_POST["where"]);
        $my_query = new WP_Query( array('post_type' => 'plugin', 'p' => $testimonial_id) );
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
        $my_query = new WP_Query( array('post_type' => 'plugin', 'p' => $testimonial_id) );
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
        do_action('tm_delete_tesimonial', $plugin_id);
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
          <h3>Available Shortcodes</h3>
          <div class="templates">
      			<div class="templates_shortcode">
      				<span class="templates_name">[testimonials_all]</span> - <?php _e("Outputs the plugin's description where ? is the id of the plugin below", 'wordpress-developer-toolkit'); ?>
      			</div>
            <div class="templates_shortcode">
      				<span class="templates_name">[plugin_link id=? link=?]</span> - <?php _e("Outputs the link to download the plugin where ? is the id of the plugin below and the text for the link", 'wordpress-developer-toolkit'); ?>
      			</div>
            <?php do_action('wpdt_extra_shortcodes'); ?>
          </div>
          <div style="clear:both;"></div>
          <br />
          <h3>Your Testimonials<a id="new_quiz_button" href="#new_testimonial_form" class="add-new-h2">Add New Testimonial</a></h3>
          <table class="widefat">
            <thead>
              <tr>
                <th><?php _e('Name','wordpress-developer-toolkit'); ?></th>
                <th><?php _e('Url','wordpress-developer-toolkit'); ?></th>
                <th><?php _e('Testimonial','wordpress-developer-toolkit'); ?></th>
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
                  echo $testimonial["name"];
                  echo "<div class=\"row-actions\">
                        <a class='linkOptions' onclick=\"jQuery('#edit_testimonial_form').show();jQuery('#new_testimonial_form').hide();\" href='#edit_testimonial_form'>".__('Edit', 'wordpress-developer-toolkit')."</a>
      						      <a class='linkOptions linkDelete' onclick=\"jQuery('#want_to_delete_".$testimonial["id"]."').show();\" href='#'>".__('Delete', 'wordpress-developer-toolkit')."</a>
                        <div id='want_to_delete_".$testimonial["id"]."' style='display:none;'>
                          <span class='table_text'>".__('Are you sure?','wordpress-developer-toolkit')."</span> <a href='#' onclick=\"tm_delete_testimonial(".$testimonial["id"].");\">".__('Yes','wordpress-developer-toolkit')."</a> | <a href='#' onclick=\"jQuery('#want_to_delete_".$testimonial["id"]."').hide();\">".__('No','wordpress-developer-toolkit')."</a>
                        </div>
      						</div>";
                echo "</td>";
                echo "<td>".$testimonial["link"]."</td>";
                echo "<td>".$testimonial["content"]."</td>";
                echo "</tr>";
              }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <th><?php _e('Name','wordpress-developer-toolkit'); ?></th>
                <th><?php _e('Url','wordpress-developer-toolkit'); ?></th>
                <th><?php _e('Testimonial','wordpress-developer-toolkit'); ?></th>
              </tr>
            </tfoot>
          </table>
          <form action="" method="post" class="testimonial_form" id="new_testimonial_form">
            <h3><?php _e('Add New Testimonial','wordpress-developer-toolkit'); ?></h3>
            <label class="testimonial_form_label"><?php _e("Testimonial",'wordpress-developer-toolkit'); ?></label>
						<textarea class="testimonial_form_input" name="new_testimonial"></textarea><br />
						<label class="testimonial_form_label"><?php _e("From Who",'wordpress-developer-toolkit'); ?></label>
            <input type="text" name="who" class="testimonial_form_input"/><br />
						<label class="testimonial_form_label"><?php _e("From URL",'wordpress-developer-toolkit'); ?></label>
            <input type="text" name="where" class="testimonial_form_input"/><br />
            <input type="submit" value="<?php _e('Add Testimonial','wordpress-developer-toolkit'); ?>" class="button-primary testimonial_form_button"/>
            <?php wp_nonce_field('add_testimonial','add_testimonial_nonce'); ?>
          </form>
          <form action="" method="post" class="testimonial_form" id="edit_testimonial_form" style="display:none;">
            <h3><?php _e('Edit Testimonial','wordpress-developer-toolkit'); ?></h3>
            <label class="testimonial_form_label"><?php _e("Testimonial",'wordpress-developer-toolkit'); ?></label>
						<textarea class="testimonial_form_input" id="edit_testimonial" name="edit_testimonial"></textarea><br />
						<label class="testimonial_form_label"><?php _e("From Who",'wordpress-developer-toolkit'); ?></label>
            <input type="text" name="who" id="who" class="testimonial_form_input"/><br />
						<label class="testimonial_form_label"><?php _e("From URL",'wordpress-developer-toolkit'); ?></label>
            <input type="text" name="where" id="where" class="testimonial_form_input"/><br />
            <input type="submit" value="<?php _e('Edit Testimonial','wordpress-developer-toolkit'); ?>" class="button-primary testimonial_form_button"/>
            <?php wp_nonce_field('edit_testimonial','edit_testimonial_nonce'); ?>
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
