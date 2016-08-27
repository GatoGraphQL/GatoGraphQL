<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCRIPT_OPINIONATEDVOTEREFERENCES', PoP_ServerUtils::get_template_definition('script-opinionatedvotereferences'));
define ('GD_TEMPLATE_SCRIPT_OPINIONATEDVOTEREFERENCESEMPTY', PoP_ServerUtils::get_template_definition('script-opinionatedvotereferencesempty'));

class VotingProcessors_Template_Processor_ScriptsLayouts extends GD_Template_Processor_AppendScriptsLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCRIPT_OPINIONATEDVOTEREFERENCES,
			GD_TEMPLATE_SCRIPT_OPINIONATEDVOTEREFERENCESEMPTY,
		);
	}
	
	function do_append($template_id) {

		switch ($template_id) {
		
			case GD_TEMPLATE_SCRIPT_OPINIONATEDVOTEREFERENCESEMPTY:

				return false;
		}
		
		return parent::do_append($template_id);
	}
	
	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_SCRIPT_OPINIONATEDVOTEREFERENCES:
			case GD_TEMPLATE_SCRIPT_OPINIONATEDVOTEREFERENCESEMPTY:

				$classes = array(
					GD_TEMPLATE_SCRIPT_OPINIONATEDVOTEREFERENCES => 'opinionatedvotereferences',
					GD_TEMPLATE_SCRIPT_OPINIONATEDVOTEREFERENCESEMPTY => 'opinionatedvotereferences',
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
new VotingProcessors_Template_Processor_ScriptsLayouts();