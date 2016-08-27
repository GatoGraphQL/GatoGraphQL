<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATAQUERY_NOTIFICATION', 'notification');

class GD_DataQuery_Notification extends GD_DataQuery {

	function get_name() {

		return GD_DATAQUERY_NOTIFICATION;
	}

	function get_objectid_fieldname() {

		return 'nid';
	}
	function get_allowedfields() {

		$allowedfields = array_merge(
			$this->get_nocachefields(),
			$this->get_loggedinuserfields()
		);

		return apply_filters('GD_DataQuery_Notification:allowedfields', $allowedfields);
	}
	function get_allowedlayouts() {

		$allowedlayouts = array();
		foreach ($this->get_lazylayouts() as $field => $lazylayouts) {
			foreach ($lazylayouts as $key => $layout) {
				$allowedlayouts[] = $layout;
			}
		}

		return apply_filters('GD_DataQuery_Notification:allowedlayouts', array_unique($allowedlayouts));
	}
	function get_nocachefields() {

		return apply_filters('GD_DataQuery_Notification:nocachefields', array());
	}
	function get_loggedinuserfields() {

		return apply_filters(
			'GD_DataQuery_Notification:loggedinuserfields', 
			array(
				'message',
				'status',
				'is-status-read',
				'is-status-not-read',
			)
		);
	}
	function get_lazylayouts() {

		return apply_filters('GD_DataQuery_Notification:lazylayouts', array());
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataQuery_Notification();