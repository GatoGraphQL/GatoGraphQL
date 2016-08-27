<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MEMBERS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-members'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ORGANIZATIONS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-organizations'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INDIVIDUALS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-individuals'));

class GD_URE_Template_Processor_CustomListMessageFeedbackLayouts extends GD_Template_Processor_ListMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MEMBERS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ORGANIZATIONS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INDIVIDUALS,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MEMBERS:

				$name = __('member', 'poptheme-wassup');
				$names = __('members', 'poptheme-wassup');
				break;
			
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ORGANIZATIONS:
			
				$name = __('organization', 'poptheme-wassup');
				$names = __('organizations', 'poptheme-wassup');
				break;
							
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INDIVIDUALS:

				$name = __('individual', 'poptheme-wassup');
				$names = __('individuals', 'poptheme-wassup');
				break;
		}

		$ret['noresults'] = sprintf(
			__('No %s found.', 'poptheme-wassup'),
			$names
		);
		$ret['nomore'] = sprintf(
			__('No more %s found.', 'poptheme-wassup'),
			$names
		);

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomListMessageFeedbackLayouts();