<?php

define ('GD_DATAQUERY_TAG', 'tag');

class GD_DataQuery_Tag extends GD_DataQuery {

	function get_name() {

		return GD_DATAQUERY_TAG;
	}

	function get_noncacheable_page() {

		return POP_CMSMODEL_PAGE_LOADERS_TAGS_FIELDS;
	}
	function get_cacheable_page() {

		return POP_CMSMODEL_PAGE_LOADERS_TAGS_LAYOUTS;
	}
	function get_objectid_fieldname() {

		return POP_INPUTNAME_TAGID;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataQuery_Tag();