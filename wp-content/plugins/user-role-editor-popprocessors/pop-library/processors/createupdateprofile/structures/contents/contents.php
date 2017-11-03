<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_CONTENT_MEMBER', PoP_TemplateIDUtils::get_template_definition('ure-content-member'));

class GD_URE_Template_Processor_CustomContents extends GD_Template_Processor_ContentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_CONTENT_MEMBER,
		);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_CONTENT_MEMBER:

				return GD_URE_TEMPLATE_CONTENTINNER_MEMBER;
		}

		return parent::get_inner_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomContents();