<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_DataQuery_UpdateDataLayoutsBase extends GD_Template_Processor_DataQueriesBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_DATAQUERY_UPDATEDATA;
	}
}