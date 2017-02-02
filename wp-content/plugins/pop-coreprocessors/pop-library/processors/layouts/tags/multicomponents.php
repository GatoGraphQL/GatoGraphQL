<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_TAG_DETAILS', PoP_ServerUtils::get_template_definition('multicomponent-tag'));

class GD_Template_ProcessorTagMultipleComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_TAG_DETAILS,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_TAG_DETAILS:

				$ret[] = GD_TEMPLATE_QUICKLINKGROUP_TAG;
				$ret[] = GD_TEMPLATE_LAYOUT_TAGH4;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_TAGSUBSCRIBETOUNSUBSCRIBEFROM;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_TAG_DETAILS:

				$this->append_att($template_id, $atts, 'class', 'layout');
				$this->append_att(GD_TEMPLATE_QUICKLINKGROUP_TAG, $atts, 'class', 'quicklinkgroup quicklinkgroup-top icon-only pull-right');
				$this->append_att(GD_TEMPLATE_LAYOUT_TAGH4, $atts, 'class', 'layout-tag-details');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_ProcessorTagMultipleComponents();