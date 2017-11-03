<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_LOCATIONPOST', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-vertical-locationpost'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_STORY', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-vertical-story'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_DISCUSSION', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-vertical-discussion'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_BLOG', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-vertical-blog'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_ANNOUNCEMENT', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-vertical-announcement'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_FEATURED', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-vertical-featured'));

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-horizontal-locationpost'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STORY', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-horizontal-story'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_DISCUSSION', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-horizontal-discussion'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_BLOG', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-horizontal-blog'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_ANNOUNCEMENT', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-horizontal-announcement'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_FEATURED', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-horizontal-featured'));

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-compacthorizontal-locationpost'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STORY', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-compacthorizontal-story'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_DISCUSSION', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-compacthorizontal-discussion'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_BLOG', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-compacthorizontal-blog'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_ANNOUNCEMENT', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-compacthorizontal-announcement'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_FEATURED', PoP_TemplateIDUtils::get_template_definition('layout-postsidebarinner-compacthorizontal-featured'));

class GD_Custom_Template_Processor_CustomPostLayoutSidebarInners extends GD_Template_Processor_SidebarInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_LOCATIONPOST,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_STORY,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_DISCUSSION,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_BLOG,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_ANNOUNCEMENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_FEATURED,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STORY,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_DISCUSSION,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_BLOG,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_ANNOUNCEMENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_FEATURED,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STORY,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_DISCUSSION,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_BLOG,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_ANNOUNCEMENT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_FEATURED,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_LOCATIONPOST:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_LOCATIONPOST)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_STORY:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STORY:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_STORY)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_DISCUSSION:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_DISCUSSION:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_DISCUSSION)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_BLOG:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_BLOG:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_BLOG)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_FEATURED:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_FEATURED:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_FEATURED)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_ANNOUNCEMENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_ANNOUNCEMENT:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_ANNOUNCEMENT)
				);
				break;
			

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_LOCATIONPOST)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STORY:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_STORY)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_DISCUSSION:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_DISCUSSION)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_BLOG:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_BLOG)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_FEATURED:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_FEATURED)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_ANNOUNCEMENT:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_ANNOUNCEMENT)
				);
				break;
		}
		
		return $ret;
	}

	function get_wrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STORY:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_DISCUSSION:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_BLOG:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_ANNOUNCEMENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_FEATURED:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STORY:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_DISCUSSION:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_BLOG:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_ANNOUNCEMENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_FEATURED:

				return 'row';
		}
	
		return parent::get_wrapper_class($template_id);
	}
	
	function get_widgetwrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LOCATIONPOST:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_STORY:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_DISCUSSION:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_BLOG:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_ANNOUNCEMENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_FEATURED:
			
				return 'col-xsm-4';
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LOCATIONPOST:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_STORY:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_DISCUSSION:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_BLOG:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_ANNOUNCEMENT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_FEATURED:
			
				return 'col-xsm-6';
		}
	
		return parent::get_widgetwrapper_class($template_id);
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CustomPostLayoutSidebarInners();