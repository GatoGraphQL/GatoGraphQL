<?php

define ('GD_DATAQUERY_COMMENT', 'comment');

class GD_DataQuery_Comment extends GD_DataQuery {

	function get_name() {

		return GD_DATAQUERY_COMMENT;
	}

	function get_noncacheable_page() {

		return POP_CMSMODEL_PAGE_LOADERS_COMMENTS_FIELDS;
	}
	function get_cacheable_page() {

		return POP_CMSMODEL_PAGE_LOADERS_COMMENTS_LAYOUTS;
	}
	function get_objectid_fieldname() {

		return POP_INPUTNAME_COMMENTID;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataQuery_Comment();