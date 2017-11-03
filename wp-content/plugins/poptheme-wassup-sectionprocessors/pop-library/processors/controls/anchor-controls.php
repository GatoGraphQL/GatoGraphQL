<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST', PoP_TemplateIDUtils::get_template_definition('custombuttoncontrol-addlocationpost'));
define ('GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK', PoP_TemplateIDUtils::get_template_definition('custombuttoncontrol-addlocationpostlink'));
define ('GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORY', PoP_TemplateIDUtils::get_template_definition('custombuttoncontrol-addstory'));
define ('GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORYLINK', PoP_TemplateIDUtils::get_template_definition('custombuttoncontrol-addstorylink'));
define ('GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENT', PoP_TemplateIDUtils::get_template_definition('custombuttoncontrol-addannouncement'));
define ('GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENTLINK', PoP_TemplateIDUtils::get_template_definition('custombuttoncontrol-addannouncementlink'));
define ('GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSION', PoP_TemplateIDUtils::get_template_definition('custombuttoncontrol-adddiscussion'));
define ('GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSIONLINK', PoP_TemplateIDUtils::get_template_definition('custombuttoncontrol-adddiscussionlink'));

class SectionProcessors_Template_Processor_AnchorControls extends GD_Template_Processor_AnchorControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST,
			GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK,
			GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORY,
			GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORYLINK,
			GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENT,
			GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENTLINK,
			GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSION,
			GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSIONLINK,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
		
				// return __('Add Location post', 'poptheme-wassup-sectionprocessors');
				return sprintf(
					__('Add %s', 'poptheme-wassup-sectionprocessors'), 
					gd_get_categoryname(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS)
				);

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORY:
		
				return __('Add Story', 'poptheme-wassup-sectionprocessors');

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENT:
		
				return __('Add Announcement', 'poptheme-wassup-sectionprocessors');

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSION:
		
				return __('Add Article', 'poptheme-wassup-sectionprocessors');

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORYLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENTLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSIONLINK:
		
				return __('as Link', 'poptheme-wassup-sectionprocessors');
		}

		return parent::get_label($template_id, $atts);
	}
	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORY:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENT:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSION:

				return 'fa-plus';

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORYLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENTLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSIONLINK:

				return 'fa-link';
		}

		return parent::get_fontawesome($template_id, $atts);
	}
	function get_href($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORY:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORYLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENT:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENTLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSION:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSIONLINK:

				$pages = array(
					GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDLOCATIONPOST,
					GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDLOCATIONPOSTLINK,
					GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORY => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORY,
					GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORYLINK => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORYLINK,
					GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENT => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENT,
					GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENTLINK => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENTLINK,
					GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSION => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION,
					GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSIONLINK => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSIONLINK,
				);
				$page = $pages[$template_id];
				
				return get_permalink($page);
		}

		return parent::get_href($template_id, $atts);
	}
	function get_target($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORY:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORYLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENT:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENTLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSION:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSIONLINK:
		
				if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
					
					return GD_URLPARAM_TARGET_ADDONS;
				}
				break;
		}

		return parent::get_target($template_id, $atts);
	}
	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOST:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORY:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENT:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSION:

				$this->append_att($template_id, $atts, 'class', 'btn btn-primary');
				break;

			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDLOCATIONPOSTLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDSTORYLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDANNOUNCEMENTLINK:
			case GD_TEMPLATE_CUSTOMANCHORCONTROL_ADDDISCUSSIONLINK:

				$this->append_att($template_id, $atts, 'class', 'btn btn-info aslink');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new SectionProcessors_Template_Processor_AnchorControls();