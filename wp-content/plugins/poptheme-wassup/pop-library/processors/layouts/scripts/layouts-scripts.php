<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCRIPT_HIGHLIGHTREFERENCES', PoP_ServerUtils::get_template_definition('script-highlightreferences'));
define ('GD_TEMPLATE_SCRIPT_HIGHLIGHTREFERENCESEMPTY', PoP_ServerUtils::get_template_definition('script-highlightreferencesempty'));

class Wassup_Template_Processor_ScriptsLayouts extends GD_Template_Processor_AppendScriptsLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCRIPT_HIGHLIGHTREFERENCES,
			GD_TEMPLATE_SCRIPT_HIGHLIGHTREFERENCESEMPTY,
		);
	}
	
	function do_append($template_id) {

		switch ($template_id) {
		
			case GD_TEMPLATE_SCRIPT_HIGHLIGHTREFERENCESEMPTY:

				return false;
		}
		
		return parent::do_append($template_id);
	}
	
	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_SCRIPT_HIGHLIGHTREFERENCES:
			case GD_TEMPLATE_SCRIPT_HIGHLIGHTREFERENCESEMPTY:

				$classes = array(
					GD_TEMPLATE_SCRIPT_HIGHLIGHTREFERENCES => 'highlightreferences',
					GD_TEMPLATE_SCRIPT_HIGHLIGHTREFERENCESEMPTY => 'highlightreferences',
				);
				$ret[GD_JS_CLASSES/*'classes'*/][GD_JS_APPENDABLE/*'appendable'*/] = $classes[$template_id];
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_ScriptsLayouts();