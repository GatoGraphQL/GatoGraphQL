<?php
namespace PoP\CMSModel;

define ('GD_DATAQUERY_USER', 'user');

class DataQuery_User extends \PoP\Engine\DataQueryBase {

	function get_name() {

		return GD_DATAQUERY_USER;
	}

	function get_noncacheable_page() {

		return POP_CMSMODEL_PAGE_LOADERS_USERS_FIELDS;
	}
	function get_cacheable_page() {

		return POP_CMSMODEL_PAGE_LOADERS_USERS_LAYOUTS;
	}
	function get_objectid_fieldname() {

		return POP_INPUTNAME_USERID;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new DataQuery_User();