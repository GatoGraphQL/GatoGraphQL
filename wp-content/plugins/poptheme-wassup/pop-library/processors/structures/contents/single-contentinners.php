<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENTINNER_AUTHOR', PoP_ServerUtils::get_template_definition('contentinner-author'));
define ('GD_TEMPLATE_CONTENTINNER_SINGLE', PoP_ServerUtils::get_template_definition('contentinner-single'));
define ('GD_TEMPLATE_CONTENTINNER_LINKSINGLE', PoP_ServerUtils::get_template_definition('contentinner-linksingle'));
define ('GD_TEMPLATE_CONTENTINNER_HIGHLIGHTSINGLE', PoP_ServerUtils::get_template_definition('contentinner-highlightsingle'));
define ('GD_TEMPLATE_CONTENTINNER_POSTHEADER', PoP_ServerUtils::get_template_definition('contentinner-postheader'));
define ('GD_TEMPLATE_CONTENTINNER_USERHEADER', PoP_ServerUtils::get_template_definition('contentinner-userheader'));
define ('GD_TEMPLATE_CONTENTINNER_USERPOSTINTERACTION', PoP_ServerUtils::get_template_definition('contentinner-userpostinteraction'));
define ('GD_TEMPLATE_CONTENTINNER_USERHIGHLIGHTPOSTINTERACTION', PoP_ServerUtils::get_template_definition('contentinner-userhighlightpostinteraction'));

class GD_Template_Processor_SingleContentInners extends GD_Template_Processor_ContentSingleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENTINNER_AUTHOR,
			GD_TEMPLATE_CONTENTINNER_SINGLE,
			GD_TEMPLATE_CONTENTINNER_LINKSINGLE,
			GD_TEMPLATE_CONTENTINNER_HIGHLIGHTSINGLE,
			GD_TEMPLATE_CONTENTINNER_POSTHEADER,
			GD_TEMPLATE_CONTENTINNER_USERHEADER,
			GD_TEMPLATE_CONTENTINNER_USERPOSTINTERACTION,
			GD_TEMPLATE_CONTENTINNER_USERHIGHLIGHTPOSTINTERACTION,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CONTENTINNER_AUTHOR:

				$ret[] = GD_TEMPLATE_LAYOUTAUTHOR_CONTENT;
				break;

			case GD_TEMPLATE_CONTENTINNER_SINGLE:
			case GD_TEMPLATE_CONTENTINNER_HIGHLIGHTSINGLE:

				$ret[] = GD_TEMPLATE_LAYOUT_CONTENT_POST;//GD_TEMPLATE_LAYOUTSINGLE;
				break;

			case GD_TEMPLATE_CONTENTINNER_LINKSINGLE:

				$ret[] = GD_TEMPLATE_LAYOUT_CONTENT_LINK;
				break;

			case GD_TEMPLATE_CONTENTINNER_POSTHEADER:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_HEADER;
				break;

			case GD_TEMPLATE_CONTENTINNER_USERHEADER:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_EDITMEMBERSHIP;
				break;
				
			case GD_TEMPLATE_CONTENTINNER_USERHIGHLIGHTPOSTINTERACTION:
			
				$ret[] = GD_TEMPLATE_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION;
				break;		
			
			case GD_TEMPLATE_CONTENTINNER_USERPOSTINTERACTION:

				$ret[] = GD_TEMPLATE_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
		
			case GD_TEMPLATE_CONTENTINNER_HIGHLIGHTSINGLE:

				// Highlights: it has a different set-up
				$this->append_att($template_id, $atts, 'class', 'well');
				break;
		}
			
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SingleContentInners();