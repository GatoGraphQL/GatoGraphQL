<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_WAYPOINTS', PoP_TemplateIDUtils::get_template_definition('waypoints'));
define ('POP_RESOURCELOADER_WAYPOINTSFETCHMORE', PoP_TemplateIDUtils::get_template_definition('waypoints-fetchmore'));
define ('POP_RESOURCELOADER_WAYPOINTSHISTORYSTATE', PoP_TemplateIDUtils::get_template_definition('waypoints-historystate'));
define ('POP_RESOURCELOADER_WAYPOINTSTHEATER', PoP_TemplateIDUtils::get_template_definition('waypoints-theater'));
define ('POP_RESOURCELOADER_WAYPOINTSTOGGLECLASS', PoP_TemplateIDUtils::get_template_definition('waypoints-toggleclass'));
define ('POP_RESOURCELOADER_WAYPOINTSTOGGLECOLLAPSE', PoP_TemplateIDUtils::get_template_definition('waypoints-togglecollapse'));

class PoP_CoreProcessors_WaypointsResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_WAYPOINTS,
			POP_RESOURCELOADER_WAYPOINTSFETCHMORE,
			POP_RESOURCELOADER_WAYPOINTSHISTORYSTATE,
			POP_RESOURCELOADER_WAYPOINTSTHEATER,
			POP_RESOURCELOADER_WAYPOINTSTOGGLECLASS,
			POP_RESOURCELOADER_WAYPOINTSTOGGLECOLLAPSE,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_WAYPOINTS => 'waypoints',
			POP_RESOURCELOADER_WAYPOINTSFETCHMORE => 'waypoints-fetchmore',
			POP_RESOURCELOADER_WAYPOINTSHISTORYSTATE => 'waypoints-historystate',
			POP_RESOURCELOADER_WAYPOINTSTHEATER => 'waypoints-theater',
			POP_RESOURCELOADER_WAYPOINTSTOGGLECLASS => 'waypoints-toggleclass',
			POP_RESOURCELOADER_WAYPOINTSTOGGLECOLLAPSE => 'waypoints-togglecollapse',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POP_COREPROCESSORS_VERSION;
	}
	
	function get_dir($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_COREPROCESSORS_DIR.'/js/'.$subpath.'libraries/3rdparties/waypoints';
	}
	
	function get_asset_path($resource) {
	
		return POP_COREPROCESSORS_DIR.'/js/libraries/3rdparties/waypoints/'.$this->get_filename($resource).'.js';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_COREPROCESSORS_URL.'/js/'.$subpath.'libraries/3rdparties/waypoints';
	}

	function get_jsobjects($resource) {

		$objects = array(
			POP_RESOURCELOADER_WAYPOINTS => array(
				'popWaypoints',
			),
			POP_RESOURCELOADER_WAYPOINTSFETCHMORE => array(
				'popWaypointsFetchMore',
			),
			POP_RESOURCELOADER_WAYPOINTSHISTORYSTATE => array(
				'popWaypointsHistoryState',
			),
			POP_RESOURCELOADER_WAYPOINTSTHEATER => array(
				'popWaypointsTheater',
			),
			POP_RESOURCELOADER_WAYPOINTSTOGGLECLASS => array(
				'popWaypointsToggleClass',
			),
			POP_RESOURCELOADER_WAYPOINTSTOGGLECOLLAPSE => array(
				'popWaypointsToggleCollapse',
			),
		);
		if ($object = $objects[$resource]) {

			return $object;
		}

		return parent::get_jsobjects($resource);
	}
	
	function get_dependencies($resource) {

		$dependencies = parent::get_dependencies($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_WAYPOINTS:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_WAYPOINTS;
				break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CoreProcessors_WaypointsResourceLoaderProcessor();
