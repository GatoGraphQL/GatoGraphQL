<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCRIPT_CREATEORUPDATEOPINIONATEDVOTEBUTTON', PoP_ServerUtils::get_template_definition('script-createorupdateopinionatedvotebutton'));

class GD_Template_Processor_OpinionatedVotedScriptsLayouts extends GD_Template_Processor_AppendScriptsLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCRIPT_CREATEORUPDATEOPINIONATEDVOTEBUTTON,
		);
	}

	function get_operation($template_id, $atts) {

		switch ($template_id) {
		
			case GD_TEMPLATE_SCRIPT_CREATEORUPDATEOPINIONATEDVOTEBUTTON:

				return 'replace';
		}

		return parent::get_operation($template_id, $atts);
	}
	
	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_SCRIPT_CREATEORUPDATEOPINIONATEDVOTEBUTTON:

				$classes = array(
					GD_TEMPLATE_SCRIPT_CREATEORUPDATEOPINIONATEDVOTEBUTTON => 'createorupdateopinionatedvote',
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
new GD_Template_Processor_OpinionatedVotedScriptsLayouts();