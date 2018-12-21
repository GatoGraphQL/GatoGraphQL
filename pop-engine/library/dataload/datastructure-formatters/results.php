<?php
namespace PoP\Engine\Impl;

define ('GD_DATALOAD_DATASTRUCTURE_RESULTS', 'results');

class DataStructureFormatter_Results extends \PoP\Engine\DataStructureFormatter {

	function get_name() {
	
		return GD_DATALOAD_DATASTRUCTURE_RESULTS;
	}
	
	function get_json_encode_type() {
	
		return JSON_FORCE_OBJECT;
	}
	
	function get_formatted_data($data) {

		// If we are requesting only the databases, then return these as a list of items
		$vars = \PoP\Engine\Engine_Vars::get_vars();
		$dataoutputitems = $vars['dataoutputitems'];
		if (in_array(GD_URLPARAM_DATAOUTPUTITEMS_DATABASES, $dataoutputitems)) {
	
			$ret = array();

			// If there are no "databases" entry, then there are no results, so return an empty array
			if ($databases = $data['databases']) {
				
				// First pass: merge all content about the same DB object
				// Eg: notifications can appear under "database" and "userstatedatabase", showing different fields on each
				$merged_databases = array();
				foreach ($databases as $database_name => $database) {
					foreach ($database as $db_key => $dbobject) {
						foreach ($dbobject as $dbobject_id => $dbobject_data) {
							
							$merged_databases[$db_key][$dbobject_id] = array_merge(
								$merged_databases[$db_key][$dbobject_id] ?? array(),
								$dbobject_data
							);
						}
					}
				}

				// Second pass: extract all items, and return it as a list
				// Watch out! It doesn't make sense to mix items from 2 or more db_keys (eg: posts and users),
				// so this formatter should be used only when displaying data from a unique one (eg: only posts)
				foreach ($merged_databases as $db_key => $dbobject) {
					
					$ret = array_merge(
						$ret,
						array_values($dbobject)
					);
				}
			}

			return $ret;
		}

		return parent::get_formatted_data($data);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new DataStructureFormatter_Results();

