<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATAQUERY_COMMENT', 'comment');

class GD_DataQuery_Comment extends GD_DataQuery {

	function get_name() {

		return GD_DATAQUERY_COMMENT;
	}

	function get_noncacheable_page() {

		return POP_WPAPI_PAGE_LOADERS_COMMENTS_DATA;
	}
	function get_cacheable_page() {

		return POP_WPAPI_PAGE_LOADERS_COMMENTS_LAYOUTS;
	}
	function get_objectid_fieldname() {

		return 'cid';
	}
	function get_allowedfields() {

		$allowedfields = array_merge(
			$this->get_nocachefields(),
			$this->get_loggedinuserfields()
		);

		return apply_filters('GD_DataQuery_Comment:allowedfields', $allowedfields);
	}
	function get_allowedlayouts() {

		$allowedlayouts = array();
		foreach ($this->get_lazylayouts() as $field => $lazylayouts) {
			foreach ($lazylayouts as $key => $layout) {
				$allowedlayouts[] = $layout;
			}
		}

		return apply_filters('GD_DataQuery_Comment:allowedlayouts', array_unique($allowedlayouts));
	}
	function get_nocachefields() {

		return apply_filters('GD_DataQuery_Comment:nocachefields', array());
	}
	function get_loggedinuserfields() {

		return apply_filters('GD_DataQuery_Comment:loggedinuserfields', array());
	}
	function get_lazylayouts() {

		return apply_filters('GD_DataQuery_Comment:lazylayouts', array());
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataQuery_Comment();