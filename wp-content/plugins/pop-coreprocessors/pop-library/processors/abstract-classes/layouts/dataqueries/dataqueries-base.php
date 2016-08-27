<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_DataQueriesBase extends GD_Template_ProcessorBase {

	function get_data_fields($template_id, $atts) {

		$fields = isset($_REQUEST['fields']) ? $_REQUEST['fields'] : array();
		if (!is_array($fields)) {
			$fields = array($fields);
		}

		// Only allow from a specific list of fields. Precaution against hackers.
		// $fields = array_intersect($fields, $atts['dataquery-allowedfields']);
		global $gd_dataquery_manager;
		$fields = array_intersect($fields, $gd_dataquery_manager->get_allowedfields());

		return $fields;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);	
		$ret['fields'] = $this->get_data_fields($template_id, $atts);
		return $ret;
	}
}
