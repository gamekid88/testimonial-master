<?php


function mlw_tm_generate_settings_page()
{
	?>
	<!-- css -->
	<link type="text/css" href="<?php echo plugin_dir_path( __FILE__ ); ?>css/redmond/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
	<!-- jquery scripts -->
	<?php
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-dialog' );
	wp_enqueue_script( 'jquery-ui-button' );
	wp_enqueue_script( 'jquery-ui-accordion' );
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
		}	);
		});
		$j(function() {
			$j("button").button();
		});
  		$j(function() {
			$j("#accordion").accordion({
				heightStyle: "content"
			});

		});
	</script>
	<style type="text/css">
		div.mlw_quiz_options input[type='text'] {
			border-color:#000000;
			color:#3300CC; 
			cursor:hand;
		}
	</style>
	<div class="wrap">
	<div class='mlw_quiz_options'>
	<h2>Testimonial Master Settings<a id="opener" href="">(?)</a></h2>
	
	<div id="dialog" title="Help">
	<h3><b>Help</b></h3>
	<p>This page contains numerous how-to's for using the plugin.</p>
	</div>	
	</div>
	</div>	
<?php
}
?>