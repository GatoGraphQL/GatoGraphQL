<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGET_FORM_FEATUREDIMAGE', PoP_TemplateIDUtils::get_template_definition('widget-form-featuredimage'));
define ('GD_TEMPLATE_WIDGET_FORM_METAINFORMATION', PoP_TemplateIDUtils::get_template_definition('widget-form-metainformation'));
define ('GD_TEMPLATE_WIDGET_FORM_WEBPOSTLINKDETAILS', PoP_TemplateIDUtils::get_template_definition('widget-form-linkdetails'));
define ('GD_TEMPLATE_WIDGET_FORM_WEBPOSTDETAILS', PoP_TemplateIDUtils::get_template_definition('widget-form-webpostdetails'));

class Wassup_Template_Processor_FormWidgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGET_FORM_FEATUREDIMAGE,
			GD_TEMPLATE_WIDGET_FORM_METAINFORMATION,
			GD_TEMPLATE_WIDGET_FORM_WEBPOSTLINKDETAILS,
			GD_TEMPLATE_WIDGET_FORM_WEBPOSTDETAILS,
		);
	}

	function is_collapsible_open($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_FEATUREDIMAGE:

				// Have the widgets open in the Addons
				if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
					return true;
				}
				break;
		}

		return parent::is_collapsible_open($template_id, $atts);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_FEATUREDIMAGE:

				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_FEATUREDIMAGE;
				break;

			case GD_TEMPLATE_WIDGET_FORM_METAINFORMATION:

				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS;
				break;

			case GD_TEMPLATE_WIDGET_FORM_WEBPOSTLINKDETAILS:

				// $ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_LINKCATEGORIES;
				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_APPLIESTO;					
				}
				if (PoPTheme_Wassup_Utils::add_link_accesstype()) {

					$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_LINKACCESS;
				}
				break;

			case GD_TEMPLATE_WIDGET_FORM_WEBPOSTDETAILS:

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

		$titles = array(
			GD_TEMPLATE_WIDGET_FORM_FEATUREDIMAGE => __('Featured Image', 'poptheme-wassup'),
			GD_TEMPLATE_WIDGET_FORM_METAINFORMATION => __('Meta information', 'poptheme-wassup'),
			GD_TEMPLATE_WIDGET_FORM_WEBPOSTLINKDETAILS => __('Link details', 'poptheme-wassup'),
			GD_TEMPLATE_WIDGET_FORM_WEBPOSTDETAILS => __('Post details', 'poptheme-wassup'),
		);

		return $titles[$template_id];
	}

	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_FEATUREDIMAGE:
			case GD_TEMPLATE_WIDGET_FORM_METAINFORMATION:
			case GD_TEMPLATE_WIDGET_FORM_WEBPOSTLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_WEBPOSTDETAILS:

				if ($class = $this->get_general_att($atts, 'form-widget-class')) {
					return $class;
				}

				return 'panel panel-info';
		}

		return parent::get_widget_class($template_id, $atts);
	}

	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_FEATUREDIMAGE:
			case GD_TEMPLATE_WIDGET_FORM_METAINFORMATION:
			case GD_TEMPLATE_WIDGET_FORM_WEBPOSTLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_WEBPOSTDETAILS:

				return 'panel-body';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_FEATUREDIMAGE:
			case GD_TEMPLATE_WIDGET_FORM_METAINFORMATION:
			case GD_TEMPLATE_WIDGET_FORM_WEBPOSTLINKDETAILS:
			case GD_TEMPLATE_WIDGET_FORM_WEBPOSTDETAILS:

				return '';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_FORM_METAINFORMATION:

				// Remove the `inline` property from all typeaheads selected elements
				$this->add_att(GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES, $atts, 'alert-class', 'alert-sm alert-info fade');
				$this->add_att(GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS, $atts, 'alert-class', 'alert-sm alert-info fade');
				break;

			case GD_TEMPLATE_WIDGET_FORM_FEATUREDIMAGE:

				// No need for the label in the formcomponent
				$this->add_att(GD_TEMPLATE_FORMCOMPONENTGROUP_FEATUREDIMAGE, $atts, 'use-component-configuration', false);
				$this->add_att(GD_TEMPLATE_FORMCOMPONENT_FEATUREDIMAGEINNER, $atts, 'setbtn-class', 'btn btn-sm btn-link');
				$this->add_att(GD_TEMPLATE_FORMCOMPONENT_FEATUREDIMAGEINNER, $atts, 'removebtn-class', 'btn btn-sm btn-link');
				$this->add_att(GD_TEMPLATE_FORMCOMPONENT_FEATUREDIMAGEINNER, $atts, 'options-class', '');
				break;

			case GD_TEMPLATE_WIDGET_FORM_WEBPOSTDETAILS:

				// If the widget has nothing inside, then hide it
				if (!PoPTheme_Wassup_Utils::add_categories() && !PoPTheme_Wassup_Utils::add_appliesto()) {
					$this->append_att($template_id, $atts, 'class', 'hidden');
				}
				break;

			case GD_TEMPLATE_WIDGET_FORM_WEBPOSTLINKDETAILS:

				// If the widget has nothing inside, then hide it
				if (!PoPTheme_Wassup_Utils::add_categories() && !PoPTheme_Wassup_Utils::add_appliesto() && !PoPTheme_Wassup_Utils::add_link_accesstype()) {
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
new Wassup_Template_Processor_FormWidgets();