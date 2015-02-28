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
          <h3>Your Testimonials</h3>
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
          <form action="" method="post" class="new_testimonial_form">
            <h3><?php _e('Add New Testimonial','wordpress-developer-toolkit'); ?></h3>
            <label class="new_testimonial_form_label"><?php _e("Testimonial",'wordpress-developer-toolkit'); ?></label>
						<textarea class="new_testimonial_form_input" name="new_testimonial"></textarea><br />
						<label class="new_testimonial_form_label"><?php _e("From Who",'wordpress-developer-toolkit'); ?></label>
            <input type="text" name="who" class="new_testimonial_form_input"/><br />
						<label class="new_testimonial_form_label"><?php _e("From URL",'wordpress-developer-toolkit'); ?></label>
            <input type="text" name="where" class="new_testimonial_form_input"/><br />
            <input type="submit" value="<?php _e('Add Testimonial','wordpress-developer-toolkit'); ?>" class="button-primary new_testimonial_form_button"/>
            <?php wp_nonce_field('add_testimonial','add_testimonial_nonce'); ?>
          </form>
          <form action="" method="post" name="delete_testimonial_form" style="display:none;">
            <input type="hidden" name="delete_testimonial" id="delete_testimonial" value="" />
            <?php wp_nonce_field('delete_testimonial','delete_testimonial_nonce'); ?>
          </form>
      </div>
			<?php
		}
}

function mlw_tm_generate_admin_page()
{
	//Edit testimonial
	if ( isset($_POST["edit_testimonial_edit"]) && $_POST["edit_testimonial_edit"] == "confirmation")
	{
		if ( isset($_POST["edit_testimonial_id"] ) ) { $mlw_tm_testimonial_id = $_POST["edit_testimonial_id"]; }
		if ( isset($_POST["edit_testimonial"] ) ) { $mlw_tm_testimonial = stripslashes(htmlspecialchars($_POST["edit_testimonial"], ENT_QUOTES)); }
		if ( isset($_POST["edit_name"] ) ) { $mlw_tm_name = stripslashes(htmlspecialchars($_POST["edit_name"], ENT_QUOTES)); }
		if ( isset($_POST["edit_url"] ) ) { $mlw_tm_url = stripslashes(htmlspecialchars($_POST["edit_url"], ENT_QUOTES)); }
		$mlw_tm_results = $wpdb->query( $wpdb->prepare( "UPDATE ".$wpdb->prefix."mlw_tm_testimonials SET testimonial='%s', url='%s', name='%s' WHERE testimonial_id=%d", $mlw_tm_testimonial, $mlw_tm_url, $mlw_tm_name, $mlw_tm_testimonial_id ) );
		if ($mlw_tm_results != false)
		{
			$hasEditedTestimonial = true;
		}
		else
		{
			$mlw_tm_error_code = "0003";
			$hasError = true;
		}
	}
	?>
	<!-- css -->
	<link type="text/css" href="<?php echo plugin_dir_url( __FILE__ ); ?>css/redmond/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
	<!-- jquery scripts -->
	<?php
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'jquery-ui-button' );
	wp_enqueue_script( 'jquery-ui-tooltip' );
	wp_enqueue_script( 'jquery-effects-blind' );
	wp_enqueue_script( 'jquery-effects-explode' );
	?>
	<script type="text/javascript">
		var $j = jQuery.noConflict();
		// increase the default animation speed to exaggerate the effect
		$j.fx.speeds._default = 1000;
		function editTestimonial(id, name, url, testimonial){
			$j("#edit_testimonial_dialog").dialog({
				autoOpen: false,
				show: 'blind',
				width:700,
				hide: 'explode',
				buttons: {
				Cancel: function() {
					$j(this).dialog('close');
					}
				}
			});
			$j("#edit_testimonial_dialog").dialog('open');
			var idHidden = document.getElementById("edit_testimonial_id");
			var nameText = document.getElementById("edit_name");
			var urlText = document.getElementById("edit_url");
			var testimonialText = document.getElementById("edit_testimonial");
			idHidden.value = id;
			nameText.value = name;
			urlText.value = url;
			testimonialText.value = testimonial;
		};
	</script>

		<div id="edit_testimonial_dialog" title="Edit Testimonial" style="display:none;">
			<h3><b>Edit Testimonial</b></h3>
			<form action='' method='post'>
				<input type='hidden' name='edit_testimonial_edit' value='confirmation' />
				<input type='hidden' name='edit_testimonial_id' id="edit_testimonial_id" value='confirmation' />
				<table class="wide" style="text-align: left; white-space: nowrap;">
					<tr>
						<td><span style='font-weight:bold;'>Testimonial:</span></td>
						<td><textarea name="edit_testimonial" id="edit_testimonial" style="border-color:#000000;color:#3300CC;width: 500px; height: 150px;"></textarea></td>
					</tr>
					<tr>
						<td><span style='font-weight:bold;'>From Who:</span></td>
						<td><input type="text" name="edit_name" id="edit_name" style="border-color:#000000;color:#3300CC;width: 500px;"/></td>
					</tr>
					<tr>
						<td><span style='font-weight:bold;'>From URL:</span></td>
						<td><input type="text" name="edit_url" id="edit_url" style="border-color:#000000;color:#3300CC;width: 500px;"/></td>
					</tr>
				</table>
				<p class='submit'><input type='submit' class='button-primary' value='Edit Testimonial' /></p>
			</form>
		</div>
	</div>
<?php
}
?>
