<?php

define ('GD_DATAQUERY_POST', 'post');

class GD_DataQuery_Post extends GD_DataQuery {

	function get_name() {

		return GD_DATAQUERY_POST;
	}

	function get_noncacheable_page() {

		return POP_CMSMODEL_PAGE_LOADERS_POSTS_FIELDS;
	}
	function get_cacheable_page() {

		return POP_CMSMODEL_PAGE_LOADERS_POSTS_LAYOUTS;
	}
	function get_objectid_fieldname() {

		return POP_INPUTNAME_POSTID;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataQuery_Post();