<?php

add_action('init', 'gd_qt_initsettings');
function gd_qt_initsettings() {
	
	global $gd_dataload_actionexecution_manager;
	$gd_dataload_actionexecuter_settings = $gd_dataload_actionexecution_manager->get_actionexecuter(GD_DATALOAD_ACTIONEXECUTER_SETTINGS);
	$gd_dataload_actionexecuter_settings->add(GD_QT_TEMPLATE_FORMCOMPONENT_LANGUAGE, new GD_QT_Settings_UrlOperator_Language());
}
