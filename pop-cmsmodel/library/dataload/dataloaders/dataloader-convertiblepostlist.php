<?php
namespace PoP\CMSModel;

define ('GD_DATALOADER_CONVERTIBLEPOSTLIST', 'convertible-post-list');

class Dataloader_ConvertiblePostList extends Dataloader_PostListBase {

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
new Dataloader_ConvertiblePostList();