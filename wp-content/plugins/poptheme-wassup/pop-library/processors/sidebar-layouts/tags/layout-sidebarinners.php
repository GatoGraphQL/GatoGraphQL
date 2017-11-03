<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_VERTICAL', PoP_TemplateIDUtils::get_template_definition('layout-tagsidebarinner-vertical'));

define ('GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL', PoP_TemplateIDUtils::get_template_definition('layout-tagsidebarinner-horizontal'));

define ('GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL', PoP_TemplateIDUtils::get_template_definition('layout-tagsidebarinner-compacthorizontal'));

class GD_Template_Processor_CustomTagLayoutSidebarInners extends GD_Template_Processor_SidebarInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_VERTICAL,
			GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL,
			GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL:
			case GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_VERTICAL:

				$ret = array_merge(
					$ret,
					FullTagSidebarSettings::get_components(GD_SIDEBARSECTION_TAG)
				);
				break;

			case GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL:

				$ret = array_merge(
					$ret,
					FullTagSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_TAG)
				);
				break;
		}
		
		return $ret;
	}

	function get_wrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL:
			case GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL:

				return 'row';
		}
	
		return parent::get_wrapper_class($template_id);
	}
	
	function get_widgetwrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_HORIZONTAL:
			
				return 'col-xsm-4';

			case GD_TEMPLATE_LAYOUT_TAGSIDEBARINNER_COMPACTHORIZONTAL:

				return 'col-xsm-6';
		}
	
		return parent::get_widgetwrapper_class($template_id);
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomTagLayoutSidebarInners();