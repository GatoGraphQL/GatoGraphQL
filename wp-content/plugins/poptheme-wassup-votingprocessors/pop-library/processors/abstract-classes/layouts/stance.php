<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class VotingProcessors_Template_Processor_StanceLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUTSTANCE;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('stance-text');
	}

	function get_stance_title($template_id, $atts) {
	
		return __('Stance:', 'poptheme-wassup-votingprocessors');
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);
	
		$ret[GD_JS_TITLES/*'titles'*/]['stance'] = $this->get_stance_title($template_id, $atts);
		
		return $ret;
	}
}