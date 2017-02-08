<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// class GD_Template_Processor_BootstrapJavascriptBlockGroupsBase extends GD_Template_Processor_InterceptableBlockGroupsBase {
class GD_Template_Processor_BootstrapJavascriptBlockGroupsBase extends GD_Template_Processor_BlockGroupsBase {

	function get_bootstrapcomponent_class($template_id) {

		// Needed for all the hooks using Bootstrap (show.bs.modal, etc)
		return 'pop-bscomponent';
	}

	function get_container_class($template_id) {

		return '';
	}

	function get_bootstrapcomponent_type($template_id) {

		return '';
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		if ($component_class = $this->get_bootstrapcomponent_class($template_id)) {
		
			$ret[GD_JS_CLASSES/*'classes'*/]['bootstrap-component'] = $component_class;
		}
		if ($container_class = $this->get_container_class($template_id)) {
		
			$ret[GD_JS_CLASSES/*'classes'*/]['container'] = $container_class;
		}
		if ($component_type = $this->get_bootstrapcomponent_type($template_id)) {
		
			$ret['bootstrap-type'] = $component_type;
		}
				
		return $ret;
	}
}