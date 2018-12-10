<?php

define ('GD_DATALOADER_PAGELIST', 'page-list');

class GD_Dataloader_PageList extends GD_Dataloader_PageListBase {

	function get_name() {
    
		return GD_DATALOADER_PAGELIST;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_PageList();