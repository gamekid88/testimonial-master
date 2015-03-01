<?php
/**
* This class generates the about page for the plugin.
*
* When loaded, it loads the included plugin files and add functions to hooks or filters.
*
* @since 0.2.0
*/
class TMAboutPage
{
    /**
  	  * Main Construct Function
  	  *
  	  * Call functions within class
  	  *
  	  * @since 0.2.0
  	  * @uses TMAboutPage::load_dependencies() Loads required filed
  	  * @uses TMAboutPage::add_hooks() Adds actions to hooks and filters
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
     * Generates About Page
     *
     * @since 0.2.0
     */
    public static function generate_page()
    {
      global $MLWTestimonialMaster;
    	$version = $MLWTestimonialMaster->version;
    	wp_enqueue_script( 'jquery' );
      wp_enqueue_style( 'tm_admin_style', plugins_url( '../css/admin.css' , __FILE__ ) );
      wp_enqueue_script( 'tm_admin_script', plugins_url( '../js/admin.js' , __FILE__ ) );
      ?>
    	<div class="wrap about-wrap">
      	<h1><?php _e('Welcome To Testimonial Master', 'testmonial-master'); ?></h1>
      	<div class="about-text"><?php _e('Thank you for updating!', 'testmonial-master'); ?></div>
      	<h2 class="nav-tab-wrapper">
      		<a href="javascript:tm_setTab(1);" id="tab_1" class="nav-tab nav-tab-active">
      			<?php _e("What's New!", 'testmonial-master'); ?></a>
      		<a href="javascript:tm_setTab(2);" id="tab_2" class="nav-tab">
      			<?php _e('Changelog', 'testmonial-master'); ?></a>
      	</h2>
      	<div id="what_new">
          <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">New User Submission Shortcode</h2>
        	<p style="text-align: center;">The new shortcode allows you to place a form on your website where users can enter in testimonials.</p>
        	<br />
          <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">Admin Page Redesign</h2>
        	<p style="text-align: center;">I completely redesigned the admin page. I added a new available shortcode area as well as changed how to edit and add testimonials.</p>
        	<br />
          <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">New Update Page</h2>
        	<p style="text-align: center;">This version brings a new update page so you know what new features and capabilities are in the new version.</p>
        	<br />
          <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">This Plugin Is Now Translation Ready!</h2>
        	<p style="text-align: center;">For those who wish to assist in translating, you can find the POT in the languages folder. If you do not know what that is, feel free to contact me and I will assist you with it.</p>
        	<br />
          <hr />
        	<h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">For Developers:</h2>
        	<br />
          <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">Rewrote Entire Plugin</h2>
        	<p style="text-align: center;">I completely rewrote the entire new plugin with better structure and OOP to help with future extendability.</p>
        	<br />
          <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">Now With Custom Post Types</h2>
        	<p style="text-align: center;">All testimonials are posts with the custom post type of testimonial.</p>
        	<br />
          <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">New Hooks And Filters</h2>
        	<p style="text-align: center;">I added several new hooks and filters to assist with extending the plugin.</p>
        	<br />
          <h2 style="margin: 1.1em 0 .2em;font-size: 2.4em;font-weight: 300;line-height: 1.3;text-align: center;">We Are On GitHub Now</h2>
        	<p style="text-align: center;">Testimonial Master is now on GitHub! I would love for you to add suggestions/feedback by creating issues. Feel free to fork and create pull requests too. Be sure to <a href="https://github.com/fpcorso/testimonial-master">check out the repository</a>.</p>
        	<br />
      	</div>
      	<div id="changelog" style="display: none;">
          <h3><?php echo $version; ?> (March 1, 2015)</h3>
        	<ul>
            <li>New Shortcode For Allowing Users To Enter Testimonial</li>
            <li>Admin Page Redesign</li>
            <li>New Update Page</li>
            <li>Now Translation Ready</li>
            <li>In Code: Now Uses Custom Post Types</li>
            <li>In Code: Rewrote Entire Plugin</li>
            <li>In Code: Added Various Hooks And Filters For Extending Plugin</li>
          </ul>
      	</div>
    	</div>
      <?php
    }
}

?>
