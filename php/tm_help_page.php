<?php

if ( ! defined( 'ABSPATH' ) ) exit;


/**
* This class generates the help page
*
* When loaded, it loads the included plugin files and add functions to hooks or filters.
*
* @since 0.2.0
*/
class TMHelpPage
{
    /**
  	  * Main Construct Function
  	  *
  	  * Call functions within class
  	  *
  	  * @since 0.2.0
  	  * @uses TMHelpPage::load_dependencies() Loads required filed
  	  * @uses TMHelpPage::add_hooks() Adds actions to hooks and filters
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
      //Nothing yet
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
      //
    }

		/**
		 * Generates Help Page
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

			///Creates the widgets
			$mlw_tm_version = get_option('mlw_tm_version');
      add_meta_box("wpss_mrts", __('Need Help?','testmonial-master'), array('TMHelpPage', 'help_box'), "mlw_tm_wpss7");
			add_meta_box("wpss_mrts", __('Support','testmonial-master'), array('TMHelpPage', 'email_box'), "mlw_tm_wpss3");
			add_meta_box("wpss_mrts", __('My Local Webstop Services','testmonial-master'), array('TMHelpPage', 'services_box'), "mlw_tm_wpss6");
			add_meta_box("wpss_mrts", __('Contribution','testmonial-master'), array('TMHelpPage', 'donation_box'), "mlw_tm_wpss4");
			add_meta_box("wpss_mrts", __('News From My Local Webstop','testmonial-master'), array('TMHelpPage', 'news_box'), "mlw_tm_wpss5");
			?>
			<div class="wrap">
				<h2><?php _e('Testimonial Master Help And Support','testmonial-master'); ?></h2>

				<h3><?php _e('Version:','testmonial-master'); ?> <?php echo $mlw_tm_version; ?></h3>
				<?php echo tm_adverts(); ?>

				<div style="float:left; width:50%;" class="inner-sidebar1">
					<?php do_meta_boxes('mlw_tm_wpss7','advanced','');  ?>
				</div>

        <div style="float:right; width:50%; " class="inner-sidebar1">
					<?php if ( get_option('mlw_advert_shows') == 'true' ) {do_meta_boxes('mlw_tm_wpss4','advanced','');} ?>
				</div>

        <div style="float:left; width:50%;" class="inner-sidebar1">
					<?php do_meta_boxes('mlw_tm_wpss3','advanced','');  ?>
				</div>

        <div style="float:right; width:50%; " class="inner-sidebar1">
					<?php if ( get_option('mlw_advert_shows') == 'true' ) {do_meta_boxes('mlw_tm_wpss6','advanced','');} ?>
				</div>

				<div style="float:right; width:50%; " class="inner-sidebar1">
					<?php do_meta_boxes('mlw_tm_wpss5','advanced',''); ?>
				</div>
			</div>
			<?php
		}

    /**
     * Generate Help Meta Box
     *
     * @since 0.2.0
     */
    public static function help_box()
    {
      ?>
    	<p><?php _e('Need help with the plugin? Try any of the following:', 'testmonial-master'); ?></p>
    	<ul>
    		<li><?php _e('Fill out the form in the Support widget to send us an email','testmonial-master'); ?></li>
    		<li>Fill out the form on our <a href="http://mylocalwebstop.com/contact-us/">Contact Us Page</a></li>
    		<li>Create a topic in the <a href="https://wordpress.org/support/plugin/testimonial-master">WordPress Support Forums</a></li>
    	</ul>
    	<?php
    }

    /**
     * Generate Services Meta Box
     *
     * @since 0.2.0
     */
    public static function services_box()
    {
      ?>
    	<div>
    		<h2>Plugin Premium Support</h2>
    		<p>Get access to premium support and always be a priority in our support. We will provide technical support and even access your site to solve your problems.
          With premium support, we will answer your responses as quickly as possible and your feature requests will be priorities in our future updates.</p>
    		<p>For details, visit our <a href="http://mylocalwebstop.com/downloads/plugin-premium-support/" target="_blank" style="color:blue;">Plugin Premium Support</a> page.</p>
    		<hr />
    		<h2>WordPress Installation Services</h2>
    		<p>Are you setting up a new WordPress? Or, are you setting up a new website? WordPress is one of the most popular systems used around the world to create and
    			manage websites. However, sometimes it can be overwhelming or even confusing as to how to set up your server, install WordPress, and get everything
    			configured to exactly how you want it.</p>
    		<p>Let us help you with your installation for you. By allowing us to take this task from you, we can take care of all the steps in getting your site going so you can get back to running your business.</p>
    		<p>For details, visit our <a href="http://mylocalwebstop.com/downloads/new-wordpress-installation/" target="_blank" style="color:blue;">New WordPress Installation</a> page.</p>
    		<hr />
    		<h2>WordPress Maintenance Services</h2>
    		<p>If you currently have a WordPress site, you know how time consuming and difficult it may be to keep it maintained. You have to keep the SEO optimized,
          keep WordPress updated, keep your plugins and themes updated, have regular backups in place, and not to mention keeping the site secure.</p>
        <p>Let us take care of your maintenance for you. You have more important things to do instead of the technical tasks.</p>
    		<p>Visit our <a href="http://mylocalwebstop.com/downloads/wordpress-maintenance/" target="_blank" style="color:blue;">WordPress Maintenance Services</a> page for details.</p>
    	</div>
    	<?php
    }

    /**
     * Generate Support Meta Box
     *
     * @since 0.2.0
     */
    public static function email_box()
    {
      $support_message = "";
    	$tm_version = get_option('mlw_tm_version');
    	if(isset($_POST["support_email"]) && $_POST["support_email"] == 'confirmation')
    	{
    		$user_name = $_POST["username"];
    		$user_email = $_POST["email"];
    		$user_message = $_POST["message"];
    		$current_user = wp_get_current_user();
    		$mlw_site_info = tm_get_system_info();
    		$mlw_message = $user_message."<br> Version: ".$tm_version."<br> User ".$current_user->display_name." from ".$current_user->user_email."<br> Wordpress Info: ".$mlw_site_info;
    		$response = wp_remote_post( "http://mylocalwebstop.com/contact-us/", array(
    			'method' => 'POST',
    			'timeout' => 45,
    			'redirection' => 5,
    			'httpversion' => '1.0',
    			'blocking' => true,
    			'headers' => array(),
    			'body' => array( 'mlwUserName' => $user_name, 'mlwUserComp' => '', 'mlwUserEmail' => $user_email, 'question1' => 'Email', 'question3' => 'Testimonial Master', 'question2' => $mlw_message, 'qmn_question_list' => '1Q3Q2Q', 'complete_quiz' => 'confirmation' ),
    			'cookies' => array()
    		  )
    		);
    		if ( is_wp_error( $response ) ) {
    		   $error_message = $response->get_error_message();
    		   $support_message = "Something went wrong: $error_message";
    		} else {
    		   $support_message = "**Message Sent**";
    		}
    	}
    	?>
    	<div class='mlw_tm_email_support'>
    	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=tm_help" method='post' name='emailForm' onsubmit='return tm_validateForm()'>
    	<input type='hidden' name='support_email' value='confirmation' />
    	<table>
    	<tr>
    	<td><?php _e('If you have any suggestions or issues with the plugin, feel free to use the form below!','testmonial-master'); ?></td>
    	</tr>
    	<tr>
    	<td><span name='mlw_support_message' id='mlw_support_message' style="color: red;"><?php echo $support_message; ?></span></td>
    	</tr>
    	<tr>
    	<td align='left'><span style='font-weight:bold;';><?php _e('Name (Required):','testmonial-master'); ?></span></td>
    	</tr>
    	<tr>
    	<td><input type='text' name='username' value='' /></td>
    	</tr>
    	<tr>
    	<td align='left'><span style='font-weight:bold;';><?php _e('Email (Required):','testmonial-master'); ?></span></td>
    	</tr>
    	<tr>
    	<td><input type='text' name='email' value='' /></td>
    	</tr>
    	<tr>
    	<td align='left'><span style='font-weight:bold;';><?php _e('Message (Required):','testmonial-master'); ?> </span></td>
    	</tr>
    	<tr>
    	<td align='left'><TEXTAREA NAME="message" COLS=40 ROWS=6></TEXTAREA></td>
    	</tr>
    	<tr>
    	<td align='left'><input type='submit' class="button-primary" value='<?php _e('Send Email','testmonial-master'); ?>' /></td>
    	</tr>
    	<tr>
    	<td align='left'></td>
    	</tr>
    	</table>
    	</form>
      <p><?php _e('Disclaimer: In order to better assist you, this form will also send some information about your WordPress installation along with your message.','testmonial-master'); ?></p>
    	</div>
    	<?php
    }

    /**
     * Generate Donation Meta Box
     *
     * @since 0.2.0
     */
    public static function donation_box()
    {
      ?>
    	<p><?php _e('Testimonial Master is and always will be a free plugin. I have spent a lot of time and effort developing and maintaining this plugin. If it has been beneficial to your site, please consider supporting this plugin by making a donation.','testmonial-master'); ?></p>
    	<div class="donation">
    		<a href="http://mylocalwebstop.com/downloads/donation-service-payment/" target="_blank" class="button"><?php _e('Donate','testmonial-master'); ?></a>
    	</div>
    	<p><?php _e('Thank you to those who have contributed so far!','testmonial-master'); ?></p>
    	<?php
    }

    /**
     * Generate News Meta Box
     *
     * @since 0.2.0
     */
    public static function news_box()
    {
      $qmn_rss = array();
    	$qmn_feed = fetch_feed('http://mylocalwebstop.com/category/our-blog/feed');
    	if (!is_wp_error($qmn_feed)) {
    		$qmn_feed_items = $qmn_feed->get_items(0, 5);
    		foreach ($qmn_feed_items as $feed_item) {
    				$qmn_rss[] = array(
    						'link' => $feed_item->get_link(),
    						'title' => $feed_item->get_title(),
    						'description' => $feed_item->get_description(),
    						'date' => $feed_item->get_date( 'F j Y' ),
    						'author' => $feed_item->get_author()->get_name()
    				);
    		}
    	}
    	foreach($qmn_rss as $item)
    	{
    		?>
    		<h3><a target='_blank' href="<?php echo $item['link']; ?>"><?php echo $item['title']; ?></a></h3>
    		<p>By <?php echo $item['author']; ?> on <?php echo $item['date']; ?></p>
    		<div>
    			<?php echo $item['description']; ?>
    		</div>
    		<?php
    	}
    }
}


function tm_get_system_info()
{
	global $wpdb;
	$tm_sys_info = "";

	$theme_data = wp_get_theme();
	$theme      = $theme_data->Name . ' ' . $theme_data->Version;

	$tm_sys_info .= "<h3>Site Information</h3><br />";
	$tm_sys_info .= "Site URL: ".site_url()."<br />";
	$tm_sys_info .= "Home URL: ".home_url()."<br />";
	$tm_sys_info .= "Multisite: ".( is_multisite() ? 'Yes' : 'No' )."<br />";

	$tm_sys_info .= "<h3>WordPress Information</h3><br />";
	$tm_sys_info .= "Version: ".get_bloginfo( 'version' )."<br />";
	$tm_sys_info .= "Language: ".( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' )."<br />";
	$tm_sys_info .= "Active Theme: ".$theme."<br />";
	$tm_sys_info .= "Debug Mode: ".( defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' )."<br />";
	$tm_sys_info .= "Memory Limit: ".WP_MEMORY_LIMIT."<br />";

	$tm_sys_info .= "<h3>Plugins Information</h3><br />";
	$qmn_plugin_mu = get_mu_plugins();
    	if( count( $qmn_plugin_mu > 0 ) ) {
    		$tm_sys_info .= "<h4>Must Use</h4><br />";
	        foreach( $qmn_plugin_mu as $plugin => $plugin_data ) {
	            $tm_sys_info .= $plugin_data['Name'] . ': ' . $plugin_data['Version'] . "<br />";
	        }
    	}
    	$tm_sys_info .= "<h4>Active</h4><br />";
	$plugins = get_plugins();
	$active_plugins = get_option( 'active_plugins', array() );
	foreach( $plugins as $plugin_path => $plugin ) {
		if( !in_array( $plugin_path, $active_plugins ) )
			continue;
		$tm_sys_info .= $plugin['Name'] . ': ' . $plugin['Version'] . "<br />";
	}
	$tm_sys_info .= "<h4>Inactive</h4><br />";
	foreach( $plugins as $plugin_path => $plugin ) {
		if( in_array( $plugin_path, $active_plugins ) )
			continue;
		$tm_sys_info .= $plugin['Name'] . ': ' . $plugin['Version'] . "<br />";
	}

	$tm_sys_info .= "<h3>Server Information</h3><br />";
	$tm_sys_info .= "PHP : ".PHP_VERSION."<br />";
	$tm_sys_info .= "MySQL : ".$wpdb->db_version()."<br />";
	$tm_sys_info .= "Webserver : ".$_SERVER['SERVER_SOFTWARE']."<br />";

	return $tm_sys_info;
}
?>
