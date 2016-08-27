<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_OPINIONATEDVOTELEFT', PoP_ServerUtils::get_template_definition('sidebarmulticomponent-opinionatedvoteleft'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_OPINIONATEDVOTERIGHT', PoP_ServerUtils::get_template_definition('sidebarmulticomponent-opinionatedvoteright'));

class VotingProcessors_Template_Processor_CustomPostMultipleSidebarComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_OPINIONATEDVOTELEFT,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_OPINIONATEDVOTERIGHT,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_OPINIONATEDVOTELEFT:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_OPINIONATEDVOTEINFO;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCES;
				$ret[] = GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER;
				break;
				
			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_OPINIONATEDVOTERIGHT:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomPostMultipleSidebarComponents();