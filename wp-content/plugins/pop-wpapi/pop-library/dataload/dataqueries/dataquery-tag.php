<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATAQUERY_TAG', 'tag');

class GD_DataQuery_Tag extends GD_DataQuery {

	function get_name() {

		return GD_DATAQUERY_TAG;
	}

	function get_noncacheable_page() {

		return POP_WPAPI_PAGE_LOADERS_TAGS_FIELDS;
	}
	function get_cacheable_page() {

		return POP_WPAPI_PAGE_LOADERS_TAGS_LAYOUTS;
	}
	function get_objectid_fieldname() {

		return 'tid';
	}
	function get_allowedfields() {

		$allowedfields = array_merge(
			$this->get_nocachefields(),
			$this->get_loggedinuserfields()
		);

		return apply_filters('GD_DataQuery_Tag:allowedfields', $allowedfields);
	}
	function get_allowedlayouts() {

		$allowedlayouts = array();
		foreach ($this->get_lazylayouts() as $field => $lazylayouts) {
			foreach ($lazylayouts as $key => $layout) {
				$allowedlayouts[] = $layout;
			}
		}

		return apply_filters('GD_DataQuery_Tag:allowedlayouts', array_unique($allowedlayouts));
	}
	function get_nocachefields() {

		return apply_filters('GD_DataQuery_Tag:nocachefields', array());
	}
	function get_loggedinuserfields() {

		return apply_filters('GD_DataQuery_Tag:loggedinuserfields', array());
	}
	function get_lazylayouts() {

		return apply_filters('GD_DataQuery_Tag:lazylayouts', array());
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataQuery_Tag();