<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-vertical'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_LINK', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-vertical-link'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_HIGHLIGHT', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-vertical-highlight'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_WEBPOST', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-vertical-webpost'));

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-horizontal'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-horizontal-link'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-horizontal-highlight'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_WEBPOST', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-horizontal-webpost'));

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-compacthorizontal'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LINK', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-compacthorizontal-link'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-compacthorizontal-highlight'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_WEBPOST', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-compacthorizontal-webpost'));

class GD_Template_Processor_CustomPostLayoutSidebarInners extends GD_Template_Processor_SidebarInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_LINK,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_HIGHLIGHT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_WEBPOST,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_WEBPOST,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LINK,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_WEBPOST,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL:

				$ret = array_merge(
					$ret,
					FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_GENERIC)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_LINK:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK:

				$ret = array_merge(
					$ret,
					FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_WEBPOSTLINK)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_HIGHLIGHT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT:

				$ret = array_merge(
					$ret,
					FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_HIGHLIGHT)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_WEBPOST:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_WEBPOST:

				$ret = array_merge(
					$ret,
					FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_WEBPOST)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL:

				$ret = array_merge(
					$ret,
					FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_GENERIC)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LINK:

				$ret = array_merge(
					$ret,
					FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_WEBPOSTLINK)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT:

				$ret = array_merge(
					$ret,
					FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_HIGHLIGHT)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_WEBPOST:

				$ret = array_merge(
					$ret,
					FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_WEBPOST)
				);
				break;
		}
		
		return $ret;
	}

	function get_wrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_WEBPOST:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LINK:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_WEBPOST:

				return 'row';
		}
	
		return parent::get_wrapper_class($template_id);
	}
	
	function get_widgetwrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_LINK:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_HIGHLIGHT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_WEBPOST:
			
				return 'col-xsm-4';
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_LINK:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_HIGHLIGHT:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_WEBPOST:
			
				return 'col-xsm-6';
		}
	
		return parent::get_widgetwrapper_class($template_id);
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomPostLayoutSidebarInners();