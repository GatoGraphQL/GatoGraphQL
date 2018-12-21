<?php
namespace PoP\CMSModel;
 
class DataQuery_PostHook extends DataQuery_PostHookBase {

	function get_nocachefields() {

		return array('comments-count');
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new DataQuery_PostHook();