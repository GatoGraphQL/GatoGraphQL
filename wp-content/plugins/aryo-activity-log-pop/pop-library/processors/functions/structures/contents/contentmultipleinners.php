<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENTINNER_MARKNOTIFICATIONASREAD', PoP_TemplateIDUtils::get_template_definition('contentinner-marknotificationasread'));
define ('GD_TEMPLATE_CONTENTINNER_MARKNOTIFICATIONASUNREAD', PoP_TemplateIDUtils::get_template_definition('contentinner-marknotificationasunread'));

class GD_AAL_Template_Processor_FunctionsContentMultipleInners extends GD_Template_Processor_ContentMultipleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENTINNER_MARKNOTIFICATIONASREAD,
			GD_TEMPLATE_CONTENTINNER_MARKNOTIFICATIONASUNREAD,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CONTENTINNER_MARKNOTIFICATIONASREAD:

				$ret[] = GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_SHOWHIDEELEMSTYLES;

				// Allow to add extra styles, such as changing background color, etc
				if ($extra = apply_filters(
					'GD_AAL_Template_Processor_FunctionsContentMultipleInners:markasread:layouts',
					array(),
					$template_id
				)) {
					$ret = array_merge(
						$ret,
						$extra
					);
				}
				break;

			case GD_TEMPLATE_CONTENTINNER_MARKNOTIFICATIONASUNREAD:

				$ret[] = GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_SHOWHIDEELEMSTYLES;

				// Allow to add extra styles, such as changing background color, etc
				if ($extra = apply_filters(
					'GD_AAL_Template_Processor_FunctionsContentMultipleInners:markasunread:layouts',
					array(),
					$template_id
				)) {
					$ret = array_merge(
						$ret,
						$extra
					);
				}
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_AAL_Template_Processor_FunctionsContentMultipleInners();