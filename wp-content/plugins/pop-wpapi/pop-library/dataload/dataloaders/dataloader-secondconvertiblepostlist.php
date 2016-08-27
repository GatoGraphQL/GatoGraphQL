<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Ajax Load Posts Library
 * This Class is needed to fetch posts after using GD_DATALOADER_POSTLIST
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_SECONDCONVERTIBLEPOSTLIST', 'secondconvertiblepost-list');

class GD_DataLoader_SecondConvertiblePostList extends GD_DataLoader_ConvertiblePostList {

	function get_name() {
    
		return GD_DATALOADER_SECONDCONVERTIBLEPOSTLIST;
	}

    function get_execution_priority() {
    
		return 2;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_SecondConvertiblePostList();