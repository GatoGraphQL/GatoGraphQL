<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_CONTENTINNER_MEMBER', PoP_ServerUtils::get_template_definition('ure-contentinner-member'));

class GD_URE_Template_Processor_CustomContentInners extends GD_Template_Processor_ContentSingleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_CONTENTINNER_MEMBER,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_URE_TEMPLATE_CONTENTINNER_MEMBER:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_EDITMEMBERSHIP;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomContentInners();