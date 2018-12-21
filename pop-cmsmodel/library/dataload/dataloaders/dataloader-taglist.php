<?php
namespace PoP\CMSModel;

define ('GD_DATALOADER_TAGLIST', 'tag-list');

class Dataloader_TagList extends Dataloader_TagListBase {

	function get_name() {
    
		return GD_DATALOADER_TAGLIST;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new Dataloader_TagList();