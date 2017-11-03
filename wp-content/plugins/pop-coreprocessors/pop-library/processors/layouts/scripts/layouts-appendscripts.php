<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCRIPT_SINGLECOMMENT', PoP_TemplateIDUtils::get_template_definition('script-singlecomment'));
define ('GD_TEMPLATE_SCRIPT_COMMENTS', PoP_TemplateIDUtils::get_template_definition('script-comments'));
define ('GD_TEMPLATE_SCRIPT_COMMENTSEMPTY', PoP_TemplateIDUtils::get_template_definition('script-commentsempty'));
define ('GD_TEMPLATE_SCRIPT_REFERENCES', PoP_TemplateIDUtils::get_template_definition('script-references'));
define ('GD_TEMPLATE_SCRIPT_REFERENCESEMPTY', PoP_TemplateIDUtils::get_template_definition('script-referencesempty'));

class GD_Template_Processor_ScriptsLayouts extends GD_Template_Processor_AppendScriptsLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCRIPT_SINGLECOMMENT,
			GD_TEMPLATE_SCRIPT_COMMENTS,
			GD_TEMPLATE_SCRIPT_COMMENTSEMPTY,
			GD_TEMPLATE_SCRIPT_REFERENCES,
			GD_TEMPLATE_SCRIPT_REFERENCESEMPTY,
		);
	}

	function do_append($template_id) {

		switch ($template_id) {
		
			case GD_TEMPLATE_SCRIPT_COMMENTSEMPTY:
			case GD_TEMPLATE_SCRIPT_REFERENCESEMPTY:

				return false;
		}
		
		return parent::do_append($template_id);
	}

	function get_layout_template($template_id) {

		switch ($template_id) {
		
			case GD_TEMPLATE_SCRIPT_SINGLECOMMENT:

				return GD_TEMPLATE_SCRIPT_APPENDCOMMENT;
		}

		return parent::get_layout_template($template_id);
	}
	
	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_SCRIPT_SINGLECOMMENT:
			case GD_TEMPLATE_SCRIPT_COMMENTS:
			case GD_TEMPLATE_SCRIPT_COMMENTSEMPTY:
			case GD_TEMPLATE_SCRIPT_REFERENCES:
			case GD_TEMPLATE_SCRIPT_REFERENCESEMPTY:

				$classes = array(
					GD_TEMPLATE_SCRIPT_SINGLECOMMENT => 'comments',
					GD_TEMPLATE_SCRIPT_COMMENTS => 'comments',
					GD_TEMPLATE_SCRIPT_COMMENTSEMPTY => 'comments',
					GD_TEMPLATE_SCRIPT_REFERENCES => 'references',
					GD_TEMPLATE_SCRIPT_REFERENCESEMPTY => 'references',
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
new GD_Template_Processor_ScriptsLayouts();