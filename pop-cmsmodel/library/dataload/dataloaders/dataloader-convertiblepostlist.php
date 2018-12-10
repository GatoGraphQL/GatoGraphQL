<?php

define ('GD_DATALOADER_CONVERTIBLEPOSTLIST', 'convertible-post-list');

class GD_Dataloader_ConvertiblePostList extends GD_Dataloader_PostListBase {

	function get_name() {
    
		return GD_DATALOADER_CONVERTIBLEPOSTLIST;
	}

    function get_fieldprocessor() {

		return GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_POSTS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_ConvertiblePostList();