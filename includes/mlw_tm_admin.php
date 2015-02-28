<?php


function mlw_tm_generate_admin_page()
{
	//Variables
	global $wpdb;
	$hasAddedTestimonial = false;
	$hasDeletedTestimonial = false;
	$hasEditedTestimonial = false;
	$hasError = false;
	$mlw_tm_error_code = "0000";
	
	
	//Add new testimonial
	if ( isset($_POST["add_testimonial"]) && $_POST["add_testimonial"] == "confirmation")
	{
		if ( isset($_POST["testimonial"] ) ) { $mlw_tm_testimonial = stripslashes(trim(preg_replace('/\s+/',' ', nl2br(htmlspecialchars($_POST["testimonial"], ENT_QUOTES))))); }
		if ( isset($_POST["name"] ) ) { $mlw_tm_name = stripslashes(htmlspecialchars($_POST["name"], ENT_QUOTES)); }
		if ( isset($_POST["url"] ) ) { $mlw_tm_url = stripslashes(htmlspecialchars($_POST["url"], ENT_QUOTES)); }
		$mlw_tm_results = $wpdb->query( $wpdb->prepare( "INSERT INTO ".$wpdb->prefix."mlw_tm_testimonials (name, url, testimonial, deleted) VALUES (%s, %s, %s, 0)", $mlw_tm_name, $mlw_tm_url, $mlw_tm_testimonial ) );
		if ($mlw_tm_results != false)
		{
			$hasAddedTestimonial = true;
		}
		else
		{
			$mlw_tm_error_code = "0001";
			$hasError = true;
		}
	}
	
	//Delete testimonial
	if ( isset($_POST["delete_testimonial"]) && $_POST["delete_testimonial"] == "confirmation")
	{
		if ( isset($_POST["delete_testimonial_id"] ) ) { $mlw_tm_testimonial_id = $_POST["delete_testimonial_id"]; }
		$mlw_tm_results = $wpdb->query( $wpdb->prepare( "UPDATE ".$wpdb->prefix."mlw_tm_testimonials SET deleted=1 WHERE testimonial_id=%d", $mlw_tm_testimonial_id ) );
		if ($mlw_tm_results != false)
		{
			$hasDeletedTestimonial = true;
		}
		else
		{
			$mlw_tm_error_code = "0002";
			$hasError = true;
		}
	}
	
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
	
	
	//Retrieve list of testimonials
	global $wpdb;
	$mlw_tm_all_data = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."mlw_tm_testimonials WHERE deleted=0 ORDER BY testimonial_id DESC" );
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
		$j(function() {
			$j('#dialog').dialog({
				autoOpen: false,
				show: 'blind',
				hide: 'explode',
				buttons: {
				Ok: function() {
					$j(this).dialog('close');
					}
				}
			});
		
			$j('#opener').click(function() {
				$j('#dialog').dialog('open');
				return false;
			});
			
			$j('#new_testimonial_dialog').dialog({
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
		
			$j('#new_testimonial_button').click(function() {
				$j('#new_testimonial_dialog').dialog('open');
				return false;
			});
			
			$j('#new_testimonial_button_two').click(function() {
				$j('#new_testimonial_dialog').dialog('open');
				return false;
			});
			$j( document ).tooltip();
		});
		$j(function() {
			$j("button").button();
		});
		$j(function() {
			$j("#new_testimonial_button, #new_testimonial_button_two").button({
				icons: {
		        primary: "ui-icon-circle-plus"
		      }
		    });
		});
		function deleteTestimonial(id){
			$j("#delete_dialog").dialog({
				autoOpen: false,
				show: 'blind',
				hide: 'explode',
				buttons: {
				Cancel: function() {
					$j(this).dialog('close');
					}
				}
			});
			$j("#delete_dialog").dialog('open');
			var idHidden = document.getElementById("delete_testimonial_id");
			idHidden.value = id;
		};
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
	<div class="wrap">
		<h2>Testimonials<a id="opener" href="">(?)</a></h2>
		<?php
		if ($hasAddedTestimonial)
		{
		?>
			<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Hey!</strong> Your testimonial has been added successfully!</p>
			</div>
		<?php
		}
		if ($hasDeletedTestimonial)
		{
		?>
			<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Hey!</strong> Your testimonial has been deleted successfully!</p>
			</div>
		<?php
		}
		if ($hasEditedTestimonial)
		{
		?>
			<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Hey!</strong> Your testimonial has been edited successfully!</p>
			</div>
		<?php
		}
		if ($hasError)
		{
		?>
			<div class="ui-state-error ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Uh-Oh!</strong> There has been an error in this action! Please share this with the developer: Error Code <?php echo $mlw_tm_error_code; ?></p>
			</div>
		<?php
		}
		?>
		<p>Add testimonials to your website using the built in widget. Or, put [mlw_tm_all] into a post or page to list all your testimonials. Or, put [mlw_tm_random] into a post or page to show a random testimonial!</p>
		<?php echo mlw_tm_show_adverts(); ?>
		<button id="new_testimonial_button_two">Add Testimonial</button>
		<br />
		<?php 
		$testimonial_list = "";
		$display = "";
		$alternate = false;
		foreach($mlw_tm_all_data as $mlw_tm_data) {
			if($alternate) $alternate = "";
			else $alternate = " class=\"alternate\"";
			$testimonial_list .= "<tr{$alternate}>";
			$testimonial_list .= "<td><span style='font-size:16px;'>" . $mlw_tm_data->name . "</span><div><span style='color:green;font-size:12px;'><a href='#' onclick=\"editTestimonial(".$mlw_tm_data->testimonial_id.",'".esc_js($mlw_tm_data->name)."','".esc_js($mlw_tm_data->url)."','".esc_js(htmlspecialchars_decode($mlw_tm_data->testimonial, ENT_QUOTES))."');\">Edit</a>|<a href='#' onclick=\"deleteTestimonial(".$mlw_tm_data->testimonial_id.");\">Delete</a></span></div></td>";
			$testimonial_list .= "<td class='post-title column-title'><span style='font-size:16px;'>" . $mlw_tm_data->url ." </span></td>";
			$testimonial_list .= "<td><span style='font-size:16px;'>".htmlspecialchars_decode($mlw_tm_data->testimonial, ENT_QUOTES)."</span></td>";
			$testimonial_list .= "</tr>";
		}
		
		$display .= "<table class=\"widefat\">";
			$display .= "<thead><tr>
				<th>Name</th>
				<th>URL</th>
				<th>Testimonial</th>
			</tr></thead>";
			$display .= "<tbody id=\"the-list\">{$testimonial_list}</tbody>";
			$display .= "</table>";
		echo $display;
		?>
		
		<button id="new_testimonial_button">Add Testimonial</button>
		
		<div id="new_testimonial_dialog" title="Add Testimonial" style="display:none;">
			<h3><b>Add Testimonial</b></h3>
			<?php
			echo "<form action='' method='post'>";
			?>
				<input type='hidden' name='add_testimonial' value='confirmation' />
				<table class="wide" style="text-align: left; white-space: nowrap;">
					<tr>
						<td><span style='font-weight:bold;'>Testimonial:</span></td>
						<td><textarea name="testimonial" id="testimonial" style="border-color:#000000;color:#3300CC;width: 500px; height: 150px;"></textarea></td>
					</tr>
					<tr>
						<td><span style='font-weight:bold;'>From Who:</span></td>
						<td><input type="text" name="name" name="name" style="border-color:#000000;color:#3300CC;width: 500px;"/></td>
					</tr>
					<tr>
						<td><span style='font-weight:bold;'>From URL:</span></td>
						<td><input type="text" name="url" name="url" style="border-color:#000000;color:#3300CC;width: 500px;"/></td>
					</tr>
				</table>
				<p class='submit'><input type='submit' class='button-primary' value='Add Testimonial' /></p>
			</form>
		</div>
		
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
		
		<div id="delete_dialog" title="Delete Testimonial?" style="display:none;">
			<h3><b>Are you sure you want to delete this testimonial?</b></h3>
			<form action="" method="post">
				<input type="hidden" name="delete_testimonial" value="confirmation" />
				<input type="hidden" name="delete_testimonial_id" id="delete_testimonial_id" value="" />
				<p class='submit'><input type='submit' class='button-primary' value='Delete Testimonial' /></p>
			</form>
		</div>
		
		<div id="dialog" title="Help" style="display:none;">
		<h3><b>Help</b></h3>
		<p>Thank you for choosing to use this plugin!</p>
		<p>To add a new testimonial, click the button that says Add Testimonial. In the pop-up, you will see three fields.</p>
		<p>First, enter the testimonial. Second, fill out who said this testimonial. Last, enter in the url where that person/company can be found.</p>
		<p>Click Add Testimonial, and your testimonial will be added. On the new entry, you will see two links.</p>
		<p>Edit allows you to edit the testimonial while Delete will delete it.</p>
		</div>
	</div>	
<?php
}
?>