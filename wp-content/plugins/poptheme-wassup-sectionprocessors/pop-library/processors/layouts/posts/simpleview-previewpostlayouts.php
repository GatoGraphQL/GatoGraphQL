<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_ANNOUNCEMENT_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('layout-previewpost-announcement-simpleview'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_LOCATIONPOST_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('layout-previewpost-locationpost-simpleview'));
// define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_FEATURED_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('layout-previewost-featured-simpleview'));
// define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_DISCUSSION_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('layout-previewost-discussion-simpleview'));
// define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_STORY_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('layout-previewost-story-simpleview'));
// define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_BLOG_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('layout-previewost-blog-simpleview'));

class PoPSF_Template_Processor_SimpleViewPreviewPostLayouts extends GD_Template_Processor_CustomSimpleViewPreviewPostLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			// GD_TEMPLATE_LAYOUT_PREVIEWPOST_ANNOUNCEMENT_SIMPLEVIEW,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_LOCATIONPOST_SIMPLEVIEW,
			// GD_TEMPLATE_LAYOUT_PREVIEWPOST_FEATURED_SIMPLEVIEW,
			// GD_TEMPLATE_LAYOUT_PREVIEWPOST_DISCUSSION_SIMPLEVIEW,
			// GD_TEMPLATE_LAYOUT_PREVIEWPOST_STORY_SIMPLEVIEW,
			// GD_TEMPLATE_LAYOUT_PREVIEWPOST_BLOG_SIMPLEVIEW,
		);
	}

	function get_quicklinkgroup_top($template_id) {

		switch ($template_id) {

			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_ANNOUNCEMENT_SIMPLEVIEW:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LOCATIONPOST_SIMPLEVIEW:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_FEATURED_SIMPLEVIEW:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_DISCUSSION_SIMPLEVIEW:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_STORY_SIMPLEVIEW:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_BLOG_SIMPLEVIEW:

				return GD_TEMPLATE_QUICKLINKGROUP_POST;
		}

		return parent::get_quicklinkgroup_top($template_id);
	}

	function get_abovecontent_layouts($template_id) {

		$ret = parent::get_abovecontent_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_LOCATIONPOST_SIMPLEVIEW:

				$ret[] = GD_TEMPLATE_MULTICOMPONENT_LOCATIONVOLUNTEER;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPSF_Template_Processor_SimpleViewPreviewPostLayouts();