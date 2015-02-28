<?php
/*
Generates the support for Testimonial Master
Copyright 2014, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/


function mlw_tm_generate_help_page()
{	
	echo "
		<script>
		function mlw_validateForm()
		{
			var x=document.forms['emailForm']['email'].value;
			if (x==null || x=='')
			{
				document.getElementById('mlw_support_message').innerHTML = '**Email must be filled out!**';
				return false;
			};
			var x=document.forms['emailForm']['username'].value;
			if (x==null || x=='')
			{
				document.getElementById('mlw_support_message').innerHTML = '**Name must be filled out!**';
				return false;
			};
			var x=document.forms['emailForm']['message'].value;
			if (x==null || x=='')
			{
				document.getElementById('mlw_support_message').innerHTML = '**There must be a message to send!**';
				return false;
			};
			var x=document.forms['emailForm']['email'].value;
			var atpos=x.indexOf('@');
			var dotpos=x.lastIndexOf('.');
			if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
			{
				document.getElementById('mlw_support_message').innerHTML = '**Not a valid e-mail address!**';
				return false;
			}
		}
	</script>
	";
	$mlw_tm_version = get_option('mlw_tm_version');
	add_meta_box("wpss_mrts", 'Support', "mlw_tm_wpss_mrt_meta_box3", "mlw_tm_wpss3");
	add_meta_box("wpss_mrts", 'My Local Webstop Services', "mlw_tm_dashboard_services", "mlw_tm_wpss6");
	add_meta_box("wpss_mrts", 'Contribution', "mlw_tm_wpss_mrt_meta_box4", "mlw_tm_wpss4");
	add_meta_box("wpss_mrts", 'News From My Local Webstop', "mlw_tm_wpss_mrt_meta_box5", "mlw_tm_wpss5");
	?>
	<!-- css -->
	<style type="text/css">
		div.mlw_tm_email_support {
		text-align: left;
		}
		div.mlw_tm_email_support input[type='text'] {
		border-color:#000000;
		color:#3300CC; 
		cursor:hand;
		}
		textarea{
		border-color:#000000;
		color:#3300CC; 
		cursor:hand;
		}
		div.donation {
		border-width: 1px;
		border-style: solid;
		padding: 0 0.6em;
		margin: 5px 0 15px;
		-moz-border-radius: 3px;
		-khtml-border-radius: 3px;
		-webkit-border-radius: 3px;
		border-radius: 3px;
		background-color: #ffffe0;
		border-color: #e6db55;
		text-align: center;
		}
		donation.p {	margin: 0.5em 0;
		line-height: 1;
		padding: 2px;
		}
		p em {
		padding-left: 1em;
		color: #555;
		font-weight: bold;
		}
	</style>
	<div class="wrap">
	<h2>Testimonial Master Help And Support</h2>
	
	<h3>Version <?php echo $mlw_tm_version; ?></h3>
	<?php echo mlw_tm_show_adverts(); ?>
	
	<div style="float:left; width:33%;" class="inner-sidebar1">
		<?php do_meta_boxes('mlw_tm_wpss3','advanced','');  ?>	
	</div>
	
	<div style="float:right; width:33%; " class="inner-sidebar1">
		<?php do_meta_boxes('mlw_tm_wpss5','advanced',''); ?>	
	</div>
	
	<div style="float:right; width:33%; " class="inner-sidebar1">
		<?php if ( get_option('mlw_advert_shows') == 'true' ) {do_meta_boxes('mlw_tm_wpss6','advanced','');} ?>	
	</div>
			
	<!--<div style="clear:both"></div>-->
						
	<div style="float:left; width:60%; " class="inner-sidebar1">
		<?php if ( get_option('mlw_advert_shows') == 'true' ) {do_meta_boxes('mlw_tm_wpss4','advanced','');} ?>	
	</div>

	</div>
	<?php
}

function mlw_tm_dashboard_services()
{
	?>
	<div>
		<h2>Plugin Premium Support</h2>
		<p>Plugin Premium Support includes 1 year of priority support, priority feature requests, and access to WordPress training videos.</p>
		<p>You can also purchase 1-on-1 training to go with your support!</p>
		<p>For details, visit our <a href="http://mylocalwebstop.com/product/plugin-premium-support/" target="_blank" style="color:blue;">Plugin Premium Support</a> page.</p>
		<hr /> 
		<h2>Plugin Installation Services</h2>
		<p>We will install and configure any or all of our WordPress plugins on your existing WordPress site.</p>
		<p>We also offer 1-on-1 training to go with your installation!</p>
		<p>For details, visit our <a href="http://mylocalwebstop.com/product/plugin-installation/" target="_blank" style="color:blue;">Plugin Installation</a> page.</p>
		<hr />
		<h2>WordPress Maintenance Services</h2>
		<p>Our maintenance service includes around the clock security monitoring, off-site backups, plugin updates, theme updates, WordPress updates, WordPress training videos, and a Monthly Status Report.</p>
		<p>Up to 30 minutes of support, consultation, and training included each month.</p>
		<p>Visit our <a href="http://mylocalwebstop.com/wordpress-maintenance-services/" target="_blank" style="color:blue;">WordPress Maintenance Services</a> page for details.</p>
	</div>
	<?php
}

function mlw_tm_wpss_mrt_meta_box3()
{
	$mlw_tm_email_message = "";
	$mlw_tm_version = get_option('mlw_tm_version');
	if(isset($_POST["action"]))
	{
		$mlw_tm_email_success = $_POST["action"];
		$mlw_tm_user_name = $_POST["username"];
		$mlw_tm_user_email = $_POST["email"];
		$mlw_tm_user_message = $_POST["message"];
		$mlw_tm_current_user = wp_get_current_user();
		$mlw_tm_site_name = get_bloginfo('name');
		$mlw_tm_site_url = get_bloginfo('url');
		$mlw_tm_site_version = get_bloginfo('version');
		$mlw_tm_site_info = $mlw_tm_site_name." ".$mlw_tm_site_url." ".$mlw_tm_site_version;
		if ($mlw_tm_email_success == 'update')
		{
			$mlw_tm_message = "Message from ".$mlw_tm_user_name." at ".$mlw_tm_user_email." It says: \n \n ".$mlw_tm_user_message."\n Version: ".$mlw_tm_version."\n User ".$mlw_tm_current_user->display_name." from ".$mlw_tm_current_user->user_email."\n Wordpress Info: ".$mlw_tm_site_info;
			wp_mail('fpcorso@mylocalwebstop.com' ,'Support From Testimonial Master Plugin', $mlw_tm_message);
			$mlw_tm_email_message = "**Message Sent**";
		}
	}
	?>
	<div class='mlw_tm_email_support'>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=mlw_tm_help" method='post' name='emailForm' onsubmit='return mlw_validateForm()'>
	<input type='hidden' name='action' value='update' />
	<table>
	<tr>
	<td>If there is something you would like to suggest to add or even if you just want 
	to let me know if you like the plugin or not, feel free to use the email form below.</td>
	</tr>
	<tr>
	<td><span name='mlw_support_message' id='mlw_support_message' style="color: red;"><?php echo $mlw_tm_email_message; ?></span></td>
	</tr>
	<tr>
	<td align='left'><span style='font-weight:bold;';>Name (Required): </span></td>
	</tr>
	<tr>
	<td><input type='text' name='username' value='' /></td>
	</tr>
	<tr>
	<td align='left'><span style='font-weight:bold;';>Email (Required): </span></td>
	</tr>
	<tr>
	<td><input type='text' name='email' value='' /></td>
	</tr>
	<tr>
	<td align='left'><span style='font-weight:bold;';>Message (Required): </span></td>
	</tr>
	<tr>
	<td align='left'><TEXTAREA NAME="message" COLS=40 ROWS=6></TEXTAREA></td>
	</tr>
	<tr>
	<td align='left'><input type='submit' value='Send Email' /></td>
	</tr>
	<tr>
	<td align='left'></td>
	</tr>
	</table>
	</form>
	</div>
	<?php
}

function mlw_tm_wpss_mrt_meta_box4()
{
	?>
	<div>
	<table width='100%'>
	<tr>
	<td align='left'>
	Testimonial Master is and always will be a free plugin. I have spent a lot of time and effort developing and maintaining this plugin. If it has been beneficial to your site, please consider supporting this plugin by making a donation.
	</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td>
	<div class="donation">
	<p>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="RTGYAETX36ZQJ">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
	</p>
	</div>
	</td>
	</tr>
	</table>
	<p>Thank you to those who have contributed so far!</p>
	</div>
	<?php
}
function mlw_tm_wpss_mrt_meta_box5()
{
	?>
	<div>
	<table width='100%'>
	<tr>
	<td align='left'><iframe src="http://www.mylocalwebstop.com/mlw_news.html?cache=<?php echo rand(); ?>" seamless="seamless" style="width: 100%; height: 550px;"></iframe></td>
	</tr>
	</table>
	</div>
	<?php
}
?>