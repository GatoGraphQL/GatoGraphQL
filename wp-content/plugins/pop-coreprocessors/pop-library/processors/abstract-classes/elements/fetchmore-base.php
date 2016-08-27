<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FetchMoreBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FETCHMORE;
	}
	
	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);
		
		$ret[GD_JS_TITLES/*'titles'*/]['fetchmore'] = sprintf(
			'%s %s', 
			$this->get_att($template_id, $atts, 'loading-spinner'),
			$this->get_att($template_id, $atts, 'fetchmore-msg')
		);
		$ret[GD_JS_TITLES/*'titles'*/]['loading'] = $this->get_att($template_id, $atts, 'loading-msg');

		$ret['hr'] = $this->get_att($template_id, $atts, 'hr');
		
		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		// Needed for clicking on 'Retry' when there was a problem in the block
		$this->add_jsmethod($ret, 'saveLastClicked');
		$this->add_jsmethod($ret, 'fetchMore');
		$this->add_jsmethod($ret, 'waypointsFetchMore');

		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		$classs = $this->get_general_att($atts, 'btn-submit-class') ? $this->get_general_att($atts, 'btn-submit-class') : 'btn btn-info btn-block';
		$this->add_att($template_id, $atts, 'class', $classs);
		$this->add_att($template_id, $atts, 'fetchmore-msg', __('Load more', 'pop-coreprocessors'));
		$this->add_att($template_id, $atts, 'loading-msg', GD_CONSTANT_LOADING_MSG);
		$this->append_att($template_id, $atts, 'class', 'fetchmore-btn');
		
		// Needed for clicking on 'Retry' when there was a problem in the block
		// $this->append_att($template_id, $atts, 'class', 'pop-sendrequest-btn');
		
		// Make infinite by default
		$this->add_att($template_id, $atts, 'infinite', true);
		if ($this->get_att($template_id, $atts, 'infinite')) {
			$this->append_att($template_id, $atts, 'class', 'waypoint');
		}

		return parent::init_atts($template_id, $atts);
	}
}
