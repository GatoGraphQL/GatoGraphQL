<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTDETAILS', PoP_TemplateIDUtils::get_template_definition('widget-form-locationpostdetails'));
define ('GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTLINKDETAILS', PoP_TemplateIDUtils::get_template_definition('widget-form-locationpostlinkdetails'));
define ('GD_TEMPLATE_WIDGET_FORM_DISCUSSIONDETAILS', PoP_TemplateIDUtils::get_template_definition('widget-form-discussiondetails'));
define ('GD_TEMPLATE_WIDGET_FORM_DISCUSSIONLINKDETAILS', PoP_TemplateIDUtils::get_template_definition('widget-form-discussionlinkdetails'));
define ('GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTDETAILS', PoP_TemplateIDUtils::get_template_definition('widget-form-announcementdetails'));
define ('GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTLINKDETAILS', PoP_TemplateIDUtils::get_template_definition('widget-form-announcementlinkdetails'));
define ('GD_TEMPLATE_WIDGET_FORM_STORYDETAILS', PoP_TemplateIDUtils::get_template_definition('widget-form-storydetails'));
define ('GD_TEMPLATE_WIDGET_FORM_STORYLINKDETAILS', PoP_TemplateIDUtils::get_template_definition('widget-form-storylinkdetails'));
define ('GD_TEMPLATE_WIDGET_FORM_FEATUREDDETAILS', PoP_TemplateIDUtils::get_template_definition('widget-form-featureddetails'));

class GD_Custom_Template_Processor_FormWidgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTDETAILS,		
			GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTLINKDETAILS,		
			GD_TEMPLATE_WIDGET_FORM_DISCUSSIONDETAILS,
			GD_TEMPLATE_WIDGET_FORM_DISCUSSIONLINKDETAILS,
			GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTDETAILS,
			GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTLINKDETAILS,
			GD_TEMPLATE_WIDGET_FORM_STORYDETAILS,
			GD_TEMPLATE_WIDGET_FORM_STORYLINKDETAILS,
			GD_TEMPLATE_WIDGET_FORM_FEATUREDDETAILS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTLINKDETAILS:

				// $ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_LOCATIONPOSTCATEGORIES;
				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_APPLIESTO;					
				}
				$ret[] = GD_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP;
				
				// Only if the Volunteering is enabled
				if (POPTHEME_WASSUP_GF_PAGE_VOLUNTEER) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_VOLUNTEERSNEEDED_SELECT;
				}

				// Comment Leo 16/01/2016: There's no need to ask for the LinkAccess since we don't show it anyway
				// if ($template_id == GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTLINKDETAILS) {
				// 	$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_LINKACCESS;
				// }
				break;

			case GD_TEMPLATE_WIDGET_FORM_DISCUSSIONDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_DISCUSSIONLINKDETAILS:

				// $ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_DISCUSSIONCATEGORIES;
				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_APPLIESTO;					
				}

				// Comment Leo 16/01/2016: There's no need to ask for the LinkAccess since we don't show it anyway
				// if ($template_id == GD_TEMPLATE_WIDGET_FORM_DISCUSSIONLINKDETAILS) {
				// 	$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_LINKACCESS;
				// }
				break;

			case GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTLINKDETAILS:

				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_APPLIESTO;					
				}

				// Only if the Volunteering is enabled
				if (POPTHEME_WASSUP_GF_PAGE_VOLUNTEER) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_VOLUNTEERSNEEDED_SELECT;
				}

				// Comment Leo 16/01/2016: There's no need to ask for the LinkAccess since we don't show it anyway
				// if ($template_id == GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTLINKDETAILS) {
				// 	$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_LINKACCESS;
				// }
				break;

			case GD_TEMPLATE_WIDGET_FORM_STORYDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_STORYLINKDETAILS:

				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_APPLIESTO;					
				}

				// Comment Leo 16/01/2016: There's no need to ask for the LinkAccess since we don't show it anyway
				// if ($template_id == GD_TEMPLATE_WIDGET_FORM_STORYLINKDETAILS) {
				// 	$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_LINKACCESS;
				// }
				break;

			case GD_TEMPLATE_WIDGET_FORM_FEATUREDDETAILS:

				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_APPLIESTO;					
				}
				break;
		}

		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		$locationpost = gd_get_categoryname(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS);
		$titles = array(
			GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTDETAILS => sprintf(__('%s details', 'poptheme-wassup-sectionprocessors'), $locationpost), //__('Location post details', 'poptheme-wassup-sectionprocessors'),	
			GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTLINKDETAILS => sprintf(__('%s link details', 'poptheme-wassup-sectionprocessors'), $locationpost), //__('Location post link details', 'poptheme-wassup-sectionprocessors'),	
			GD_TEMPLATE_WIDGET_FORM_DISCUSSIONDETAILS => __('Article details', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_WIDGET_FORM_DISCUSSIONLINKDETAILS => __('Article link details', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTDETAILS => __('Announcement details', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTLINKDETAILS => __('Announcement link details', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_WIDGET_FORM_STORYDETAILS => __('Story details', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_WIDGET_FORM_STORYLINKDETAILS => __('Story link details', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_WIDGET_FORM_FEATUREDDETAILS => __('Featured article details', 'poptheme-wassup-sectionprocessors'),
		);

		return $titles[$template_id];
	}

	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_DISCUSSIONDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_DISCUSSIONLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_STORYDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_STORYLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_FEATUREDDETAILS:

				if ($class = $this->get_general_att($atts, 'form-widget-class')) {
					return $class;
				}

				return 'panel panel-info';
		}

		return parent::get_widget_class($template_id, $atts);
	}

	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_DISCUSSIONDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_DISCUSSIONLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_STORYDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_STORYLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_FEATUREDDETAILS:

				return 'panel-body';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_DISCUSSIONDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_DISCUSSIONLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_STORYDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_STORYLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_FEATUREDDETAILS:

				return '';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}

	function is_collapsible_open($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTLINKDETAILS:

				// Have the widgets open in the Addons
				if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
					return true;
				}
				break;
		}

		return parent::is_collapsible_open($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_LOCATIONPOSTLINKDETAILS:

				// Typeahead map: make it small
				$this->add_att(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP, $atts, 'wrapper-class', '');
				$this->add_att(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP, $atts, 'map-class', 'spacing-bottom-md');
				$this->add_att(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP, $atts, 'typeahead-class', '');
				$this->add_att(GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS, $atts, 'alert-class', 'alert-sm alert-info fade');
				break;

			case GD_TEMPLATE_WIDGET_FORM_DISCUSSIONLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_DISCUSSIONDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_STORYLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_STORYDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_FEATUREDDETAILS:

				// If the widget has nothing inside, then hide it
				if (!PoPTheme_Wassup_Utils::add_categories() && !PoPTheme_Wassup_Utils::add_appliesto()) {
					$this->append_att($template_id, $atts, 'class', 'hidden');
				}
				break;

			case GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_ANNOUNCEMENTDETAILS:

				// If the widget has nothing inside, then hide it
				if (!PoPTheme_Wassup_Utils::add_categories() && !PoPTheme_Wassup_Utils::add_appliesto() && !POPTHEME_WASSUP_GF_PAGE_VOLUNTEER) {
					$this->append_att($template_id, $atts, 'class', 'hidden');
				}
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_FormWidgets();