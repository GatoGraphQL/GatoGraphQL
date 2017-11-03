<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_SUBSCRIBER', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-subscriber'));

define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_NAVIGATOR', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-profile-navigator'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_ADDONS', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-profile-addons'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-profile-details'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_THUMBNAIL', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-profile-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_LIST', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-profile-list'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POPOVER', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-profile-popover'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_COMMUNITIES', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-profile-communities'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POSTAUTHOR', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-profile-postauthor'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_EDITMEMBERS', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-profile-editmembers'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_EDITMEMBERSHIP', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-profile-editmembership'));

define ('GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-authorpreviewuser-profile-details'));
define ('GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_THUMBNAIL', PoP_TemplateIDUtils::get_template_definition('layout-authorpreviewuser-profile-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_LIST', PoP_TemplateIDUtils::get_template_definition('layout-authorpreviewuser-profile-list'));

define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-teamstaff-details'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_THUMBNAIL', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-teamstaff-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-sponsor-details'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_SMALLDETAILS', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-sponsor-smalldetails'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_THUMBNAIL', PoP_TemplateIDUtils::get_template_definition('layout-previewuser-sponsor-thumbnail'));

class GD_Template_Processor_CustomPreviewUserLayouts extends GD_Template_Processor_CustomPreviewUserLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_SUBSCRIBER,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_NAVIGATOR,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_ADDONS,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_DETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_LIST,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POPOVER,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_COMMUNITIES,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POSTAUTHOR,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_EDITMEMBERS,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_EDITMEMBERSHIP,
			GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_DETAILS,
			GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_LIST,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_DETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_DETAILS,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_SMALLDETAILS,
		);
	}

	function get_quicklinkgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POPOVER:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_COMMUNITIES:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POSTAUTHOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_THUMBNAIL:

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_LIST:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_DETAILS:

				return GD_TEMPLATE_QUICKLINKGROUP_USER;
		}

		return parent::get_quicklinkgroup_top($template_id);
	}	

	function get_title_htmlmarkup($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_DETAILS:

				return 'h3';
		}

		return parent::get_title_htmlmarkup($template_id, $atts);
	}	

	function get_quicklinkgroup_bottom($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POPOVER:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_COMMUNITIES:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POSTAUTHOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_THUMBNAIL:

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_LIST:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_LIST:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_DETAILS:

				return GD_TEMPLATE_QUICKLINKGROUP_USERBOTTOM;

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_EDITMEMBERS:

				return GD_TEMPLATE_QUICKLINKGROUP_USER_EDITMEMBERS;
		}

		return parent::get_quicklinkgroup_bottom($template_id);
	}	

	// function get_extra_class($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_DETAILS:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_THUMBNAIL:
	// 		case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_DETAILS:
	// 		case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_THUMBNAIL:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_DETAILS:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_THUMBNAIL:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_DETAILS:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_THUMBNAIL:

	// 			return 'bg-info text-info belowavatar';
	// 	}

	// 	return parent::get_extra_class($template_id);
	// }

	function get_belowavatar_layouts($template_id) {

		$ret = parent::get_belowavatar_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_DETAILS:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS;
				break;
		}

		return $ret;
	}

	function get_belowexcerpt_layouts($template_id) {

		$ret = parent::get_belowexcerpt_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_THUMBNAIL:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS;
				break;

			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_THUMBNAIL:
			
				$ret[] = GD_URE_TEMPLATE_LAYOUTUSER_MEMBERTAGS;			
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS;
				break;
		}

		return $ret;
	}

	function get_useravatar_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_COMMUNITIES:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POSTAUTHOR:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_ADDONS:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_SMALLDETAILS:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_LIST:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_LIST:

				return GD_TEMPLATE_LAYOUT_USERAVATAR_40;

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SUBSCRIBER:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POPOVER:

				return GD_TEMPLATE_LAYOUT_USERAVATAR_60;

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_EDITMEMBERS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_EDITMEMBERSHIP:

				return GD_TEMPLATE_LAYOUT_USERAVATAR_60_RESPONSIVE;

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_THUMBNAIL:

				return GD_TEMPLATE_LAYOUT_USERAVATAR_150_RESPONSIVE;
		}

		return parent::get_useravatar_template($template_id);
	}

	protected function show_title($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_THUMBNAIL:

				return true;
		}

		return parent::show_title($template_id);
	}

	function show_excerpt($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_DETAILS:

				return true;
		}

		return parent::show_excerpt($template_id);
	}

	function show_short_description($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_EDITMEMBERS:

				return false;
		}

		return parent::show_short_description($template_id);
	}

	function horizontal_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_DETAILS:

				return true;
		}

		return parent::horizontal_layout($template_id);
	}

	function horizontal_media_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SUBSCRIBER:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POPOVER:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_COMMUNITIES:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POSTAUTHOR:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_ADDONS:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_EDITMEMBERS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_EDITMEMBERSHIP:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_LIST:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_LIST:
			
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_SMALLDETAILS:
			
				return true;
		}

		return parent::horizontal_media_layout($template_id);
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_ADDONS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POPOVER:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_COMMUNITIES:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_POSTAUTHOR:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_THUMBNAIL:

				$ret[GD_JS_CLASSES/*'classes'*/]['quicklinkgroup-bottom'] = 'icon-only pull-right';
				break;
		}
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTHORPREVIEWUSER_PROFILE_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_TEAMSTAFF_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_DETAILS:
			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_SPONSOR_THUMBNAIL:

				$ret[GD_JS_CLASSES/*'classes'*/]['belowavatar'] = 'bg-info text-info belowavatar';
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_EDITMEMBERSHIP:

				$this->append_att($template_id, $atts, 'class', 'alert alert-info alert-sm');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomPreviewUserLayouts();