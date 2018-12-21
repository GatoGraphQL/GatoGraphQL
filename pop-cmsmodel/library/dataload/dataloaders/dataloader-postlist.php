<?php
namespace PoP\CMSModel;

define ('GD_DATALOADER_POSTLIST', 'post-list');

class Dataloader_PostList extends Dataloader_PostListBase {

	function get_name() {
    
		return GD_DATALOADER_POSTLIST;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new Dataloader_PostList();