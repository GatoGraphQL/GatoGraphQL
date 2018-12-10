<?php

define ('GD_DATALOADER_TAGLIST', 'tag-list');

class GD_Dataloader_TagList extends GD_Dataloader_TagListBase {

	function get_name() {
    
		return GD_DATALOADER_TAGLIST;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_TagList();