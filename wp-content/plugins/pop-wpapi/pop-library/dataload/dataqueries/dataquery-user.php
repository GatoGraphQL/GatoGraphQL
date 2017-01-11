<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATAQUERY_USER', 'user');

class GD_DataQuery_User extends GD_DataQuery {

	function get_name() {

		return GD_DATAQUERY_USER;
	}

	function get_noncacheable_page() {

		return POP_WPAPI_PAGE_LOADERS_USERS_FIELDS;
	}
	function get_cacheable_page() {

		return POP_WPAPI_PAGE_LOADERS_USERS_LAYOUTS;
	}
	function get_objectid_fieldname() {

		return 'uid';
	}
	function get_allowedfields() {

		$allowedfields = array_merge(
			$this->get_nocachefields(),
			$this->get_loggedinuserfields()
		);

		return apply_filters('GD_DataQuery_User:allowedfields', $allowedfields);
	}
	function get_allowedlayouts() {

		$allowedlayouts = array();
		foreach ($this->get_lazylayouts() as $field => $lazylayouts) {
			foreach ($lazylayouts as $key => $layout) {
				$allowedlayouts[] = $layout;
			}
		}

		return apply_filters('GD_DataQuery_User:allowedlayouts', array_unique($allowedlayouts));
	}
	function get_nocachefields() {

		return apply_filters('GD_DataQuery_User:nocachefields', array());
	}
	function get_loggedinuserfields() {

		return apply_filters('GD_DataQuery_User:loggedinuserfields', array());
	}
	function get_lazylayouts() {

		return apply_filters('GD_DataQuery_User:lazylayouts', array());
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataQuery_User();