<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INITIALIZEDOMAIN', PoP_ServerUtils::get_template_definition('layout-messagefeedback-initializedomain'));

class GD_Template_Processor_DomainMessageFeedbackLayouts extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INITIALIZEDOMAIN,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INITIALIZEDOMAIN:

				$ret['checkpoint-error-header'] = __('Domain Initialization', 'pop-coreprocessors');
				$ret['domainempty'] = __('The domain is empty.', 'pop-coreprocessors');
				$ret['domainnotvalid'] = sprintf(
					__('Domain <strong>%s</strong> is not valid.', 'pop-coreprocessors'),
					GD_Template_Processor_DomainUtils::get_domain_from_request()
				);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_DomainMessageFeedbackLayouts();