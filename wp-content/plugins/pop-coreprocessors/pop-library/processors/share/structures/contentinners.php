<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENTINNER_EMBEDPREVIEW', PoP_ServerUtils::get_template_definition('contentinner-embedpreview'));
define ('GD_TEMPLATE_CONTENTINNER_EMBED', PoP_ServerUtils::get_template_definition('contentinner-embed'));
define ('GD_TEMPLATE_CONTENTINNER_API', PoP_ServerUtils::get_template_definition('contentinner-api'));
define ('GD_TEMPLATE_CONTENTINNER_COPYSEARCHURL', PoP_ServerUtils::get_template_definition('contentinner-copysearchurl'));

class GD_Template_Processor_ShareContentInners extends GD_Template_Processor_ContentSingleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENTINNER_EMBEDPREVIEW,
			GD_TEMPLATE_CONTENTINNER_EMBED,
			GD_TEMPLATE_CONTENTINNER_API,
			GD_TEMPLATE_CONTENTINNER_COPYSEARCHURL,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CONTENTINNER_EMBEDPREVIEW:

				$ret[] = GD_TEMPLATE_LAYOUT_EMBEDPREVIEW;
				break;

			case GD_TEMPLATE_CONTENTINNER_EMBED:

				$ret[] = GD_TEMPLATE_FORMCOMPONENT_EMBEDCODE;
				break;

			case GD_TEMPLATE_CONTENTINNER_API:
				
				$ret[] = GD_TEMPLATE_FORMCOMPONENT_API;
				break;

			case GD_TEMPLATE_CONTENTINNER_COPYSEARCHURL:

				$ret[] = GD_TEMPLATE_FORMCOMPONENT_COPYSEARCHURL;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ShareContentInners();