<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('multicomponent-userpostactivity-simpleview'));
define ('GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY_LAZYSIMPLEVIEW', PoP_ServerUtils::get_template_definition('multicomponent-userpostactivity-lazysimpleview'));
define ('GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY', PoP_ServerUtils::get_template_definition('multicomponent-userpostactivity'));

class GD_Template_Processor_MultipleComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY_SIMPLEVIEW,
			GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY_LAZYSIMPLEVIEW,
			GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY_SIMPLEVIEW:

				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY_LAZYSIMPLEVIEW:

				$ret[] = GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY;
				$ret[] = GD_TEMPLATE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY:

				$ret[] = GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER;
				$ret[] = GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW;
				$ret[] = GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW;
				$ret[] = GD_TEMPLATE_LAZYSUBCOMPONENT_POSTCOMMENTS;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY_LAZYSIMPLEVIEW:

				// Make the User Post Interaction group a collapse, initially collapsed
				$this->append_att(GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY, $atts, 'class', 'collapse');
				
				// Indicate the button what collapse to open
				$this->add_att(GD_TEMPLATE_BUTTON_TOGGLEUSERPOSTACTIVITY, $atts, 'target-template', GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY);
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MultipleComponents();