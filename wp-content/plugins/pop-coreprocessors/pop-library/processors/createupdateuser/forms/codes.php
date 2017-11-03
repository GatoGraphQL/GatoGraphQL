<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_LABEL', PoP_TemplateIDUtils::get_template_definition('code-emailnotifications-label'));
define ('GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_GENERALLABEL', PoP_TemplateIDUtils::get_template_definition('code-emailnotifications-generallabel'));
define ('GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_NETWORKLABEL', PoP_TemplateIDUtils::get_template_definition('code-emailnotifications-networklabel'));
define ('GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL', PoP_TemplateIDUtils::get_template_definition('code-emailnotifications-subscribedtopicslabel'));
define ('GD_TEMPLATE_CODE_DAILYEMAILDIGESTS_LABEL', PoP_TemplateIDUtils::get_template_definition('code-dailyemaildigestslabel'));

class GD_Template_Processor_UserCodes extends GD_Template_Processor_CodesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_LABEL,
			GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_GENERALLABEL,
			GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_NETWORKLABEL,
			GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL,
			GD_TEMPLATE_CODE_DAILYEMAILDIGESTS_LABEL,
		);
	}

	function get_code($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_LABEL:
			case GD_TEMPLATE_CODE_DAILYEMAILDIGESTS_LABEL:

				$titles = array(
					GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_LABEL => __('Email notifications', 'pop-coreprocessors'),
					GD_TEMPLATE_CODE_DAILYEMAILDIGESTS_LABEL => __('Email digests', 'pop-coreprocessors'),
				);
				return sprintf(
					'<h3>%s</h3>',
					$titles[$template_id]
				);

			case GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_GENERALLABEL:
			case GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_NETWORKLABEL:
			case GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL:

				$titles = array(
					GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_GENERALLABEL => __('General:', 'pop-coreprocessors'),
					GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_NETWORKLABEL => __('A user on my network:', 'pop-coreprocessors'),
					GD_TEMPLATE_CODE_EMAILNOTIFICATIONS_SUBSCRIBEDTOPICSLABEL => __('A topic I am subscribed to:', 'pop-coreprocessors'),
				);
				return sprintf(
					'<h4>%s</h4>',
					$titles[$template_id]
				);
		}
	
		return parent::get_code($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserCodes();