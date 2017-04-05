<?php

// Comment Leo 02/04/207: add it under CheckpointIOHandler and not on TopLevelIOHandler
// so that this information is also brought for when doing fetching-json-data
// add_filter('GD_DataLoad_TopLevelIOHandler:feedback', 'pop_cdncore_toplevelfeedback');
add_filter('GD_DataLoad_CheckpointIOHandler:feedback', 'pop_cdncore_toplevelfeedback');
function pop_cdncore_toplevelfeedback($feedback) {

	// // Add the version to the topLevel feedback to be sent in the URL params
	// $feedback[GD_DATALOAD_PARAMS][POP_CDNCORE_URLPARAM_VERSION] = pop_version();

	// Add all the Thumbprint values
    $thumbprint_values = array();
    global $pop_cdncore_thumbprint_manager;
    foreach ($pop_cdncore_thumbprint_manager->get_thumbprints() as $thumbprint) {
		
		$thumbprint_values[$thumbprint] = $pop_cdncore_thumbprint_manager->get_thumbprint_value($thumbprint);
    }
    $feedback[POP_CDNCORE_THUMBPRINTVALUES] = $thumbprint_values;

	return $feedback;
}

add_filter('GD_TemplateManager_Utils:current_url:remove_params', 'pop_cdncore_remove_urlparams');
function pop_cdncore_remove_urlparams($remove_params) {

    // $remove_params[] = POP_CDNCORE_URLPARAM_VERSION;
    $remove_params[] = GD_URLPARAM_CDNTHUMBPRINT;

    return $remove_params;
}