<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ReferencesScriptFrameLayoutsBase extends GD_Template_Processor_ScriptFrameLayoutsBase {

	function do_append($template_id) {

		return true;
	}

	function get_script_template($template_id) {
	
		return $this->do_append($template_id) ? GD_TEMPLATE_SCRIPT_REFERENCES : GD_TEMPLATE_SCRIPT_REFERENCESEMPTY;
	}
}