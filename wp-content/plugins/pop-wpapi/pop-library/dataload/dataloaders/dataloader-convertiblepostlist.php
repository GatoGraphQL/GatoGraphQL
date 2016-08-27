<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Ajax Load Posts Library
 * This Class is needed to fetch posts after using GD_DATALOADER_POSTLIST
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_CONVERTIBLEPOSTLIST', 'convertiblepost-list');

class GD_DataLoader_ConvertiblePostList extends GD_DataLoader_PostList {

	function get_name() {
    
		return GD_DATALOADER_CONVERTIBLEPOSTLIST;
	}

 //    function get_execution_priority() {
    
	// 	// Execute before anything else
	// 	return 1;
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_ConvertiblePostList();