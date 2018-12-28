<?php
namespace PoP\CMSModel;

define ('GD_DATAQUERY_COMMENT', 'comment');

class DataQuery_Comment extends \PoP\Engine\DataQueryBase {

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
new DataQuery_Comment();