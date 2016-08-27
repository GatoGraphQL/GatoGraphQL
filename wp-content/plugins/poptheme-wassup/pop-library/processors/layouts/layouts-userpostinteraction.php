<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_USERPOSTINTERACTION', PoP_ServerUtils::get_template_definition('layout-userpostinteraction'));
define ('GD_TEMPLATE_LAYOUT_USERHIGHLIGHTPOSTINTERACTION', PoP_ServerUtils::get_template_definition('layout-userhighlightpostinteraction'));

class Wassup_Template_Processor_UserPostInteractionLayouts extends GD_Template_Processor_UserPostInteractionLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_USERPOSTINTERACTION,
			GD_TEMPLATE_LAYOUT_USERHIGHLIGHTPOSTINTERACTION,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_USERPOSTINTERACTION:

				// Allow TPPDebate to add the "What do you think about TPP?" before these layouts
				if ($layouts = apply_filters(
					'Wassup_Template_Processor_UserPostInteractionLayouts:userpostinteraction:layouts',
					array(
						GD_TEMPLATE_CONTROLGROUP_USERPOSTINTERACTION,
					),
					$template_id
				)) {
					$ret = array_merge(
						$ret,
						$layouts
					);
				}
				break;

			case GD_TEMPLATE_LAYOUT_USERHIGHLIGHTPOSTINTERACTION:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT;
				break;
		}	

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_UserPostInteractionLayouts();