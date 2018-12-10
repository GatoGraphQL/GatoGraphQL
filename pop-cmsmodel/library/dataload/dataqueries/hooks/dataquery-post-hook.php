<?php
 
class GD_DataQuery_PostHook extends GD_DataQuery_PostHookBase {

	function get_nocachefields() {

		return array('comments-count');
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataQuery_PostHook();