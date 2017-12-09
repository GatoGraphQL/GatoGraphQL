<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_MAPDETAILS', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-profile-mapdetails'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_HORIZONTALMAPDETAILS', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-profile-horizontalmapdetails'));
define ('GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_MAPDETAILS', PoP_TemplateIDUtils::get_template_definition('layout-authorpreviewuser-profile-mapdetails'));

class GD_EM_Template_Processor_CustomPreviewUserLayouts extends GD_Template_Processor_CustomPreviewUserLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_MAPDETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_HORIZONTALMAPDETAILS,
			GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_MAPDETAILS,
		);
	}

	function get_quicklinkgroup_bottom($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_HORIZONTALMAPDETAILS:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_MAPDETAILS:

				return GD_TEMPLATE_QUICKLINKGROUP_USERBOTTOM;
		}

		return parent::get_quicklinkgroup_bottom($template_id);
	}	

	function get_quicklinkgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_HORIZONTALMAPDETAILS:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_MAPDETAILS:

				return GD_TEMPLATE_QUICKLINKGROUP_USER;
		}

		return parent::get_quicklinkgroup_top($template_id);
	}	

	function get_belowexcerpt_layouts($template_id) {

		$ret = parent::get_belowexcerpt_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_HORIZONTALMAPDETAILS:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKER;
				break;

			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_MAPDETAILS:
			
				$ret[] = GD_URE_TEMPLATE_LAYOUTUSER_MEMBERTAGS;			
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKER;
				break;
		}

		return $ret;
	}

	function get_useravatar_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_MAPDETAILS:
			
				return GD_TEMPLATE_LAYOUT_USERAVATAR_120_RESPONSIVE;
			
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_HORIZONTALMAPDETAILS:
			
				return GD_TEMPLATE_LAYOUT_USERAVATAR_40;
		}

		return parent::get_useravatar_template($template_id);
	}

	function horizontal_media_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_HORIZONTALMAPDETAILS:

				return true;
		}

		return parent::horizontal_media_layout($template_id);
	}

	// function show_short_description($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_MAPDETAILS:
	// 		case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_MAPDETAILS:
			
	// 			return false;
	// 	}

	// 	return parent::show_short_description($template_id);
	// }

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_MAPDETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_HORIZONTALMAPDETAILS:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_MAPDETAILS:

				$ret[GD_JS_CLASSES/*'classes'*/]['quicklinkgroup-bottom'] = 'icon-only pull-right';
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomPreviewUserLayouts();