<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELPREV', PoP_ServerUtils::get_template_definition('carouselbuttoncontrol-carouselprev'));
define ('GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELNEXT', PoP_ServerUtils::get_template_definition('carouselbuttoncontrol-carouselnext'));

class GD_Template_Processor_CarouselButtonControls extends GD_Template_Processor_ButtonControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELPREV,
			GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELNEXT,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELPREV:

				return __('Previous', 'pop-coreprocessors');

			case GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:

				return __('Next', 'pop-coreprocessors');
		}

		return parent::get_label($template_id, $atts);
	}
	function get_icon($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELPREV:

				return 'glyphicon-chevron-left';

			case GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:

				return 'glyphicon-chevron-right';
		}

		return parent::get_icon($template_id);
	}
	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
			case GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:

				$classes = array(
					GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELPREV => 'carousel-prev',
					GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELNEXT => 'carousel-next'
				);
				$class = $classes[$template_id];

				$this->append_att($template_id, $atts, 'class', $class . ' fetchmore-btn-disable');
				$carousel_target = $this->get_att($template_id, $atts, 'carousel-target');
				$this->merge_att($template_id, $atts, 'params', array(
					'data-target' => $carousel_target
				));
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
	function get_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
			case GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:

				return null;
		}

		return parent::get_text($template_id, $atts);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
				$this->add_jsmethod($ret, 'controlCarouselPrev');
				$this->add_jsmethod($ret, 'fetchMoreDisable');
				break;

			case GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
				$this->add_jsmethod($ret, 'controlCarouselNext');
				$this->add_jsmethod($ret, 'fetchMoreDisable');
				break;
		}
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CarouselButtonControls();