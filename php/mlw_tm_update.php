<?php
/*
This is the update function for the plugin. When the plugin gets updated, the database changes are done here. This function is placed in the init of wordpress.
*/
function mlw_tm_update()
{
	
	//Update this variable each update. This is what is checked when the plugin is deciding to run the upgrade script or not.
	$data = "0.1.8";
	if ( ! get_option('mlw_tm_version'))
	{
		add_option('mlw_tm_version' , $data);
	}
	elseif (get_option('mlw_tm_version') != $data)
	{
		update_option('mlw_tm_version' , $data);
	}
	if ( ! get_option('mlw_advert_shows'))
	{
		add_option('mlw_advert_shows' , 'true');
	}
}
?>