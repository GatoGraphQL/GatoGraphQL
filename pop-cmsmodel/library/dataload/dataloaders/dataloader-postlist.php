<?php

define ('GD_DATALOADER_POSTLIST', 'post-list');

class GD_Dataloader_PostList extends GD_Dataloader_PostListBase {

	function get_name() {
    
		return GD_DATALOADER_POSTLIST;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_PostList();