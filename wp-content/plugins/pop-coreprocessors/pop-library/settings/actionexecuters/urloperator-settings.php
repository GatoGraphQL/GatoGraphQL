<?php

add_action('init', 'gd_format_initsettings');
function gd_format_initsettings() {
	
	global $gd_dataload_actionexecution_manager;
	$gd_dataload_actionexecuter_settings = $gd_dataload_actionexecution_manager->get_actionexecuter(GD_DATALOAD_ACTIONEXECUTER_SETTINGS);
	$gd_dataload_actionexecuter_settings->add(GD_TEMPLATE_FORMCOMPONENT_SETTINGSFORMAT, new GD_Settings_UrlOperator_Format());
}