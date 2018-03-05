<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-featuredimage'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-featuredimagevolunteer'));

define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_GENERIC', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-generic'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_LINK', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-link'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_HIGHLIGHTLEFT', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-highlightleft'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_HIGHLIGHTRIGHT', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-highlightright'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_WEBPOST', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-webpost'));
// define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_WEBPOSTLEFT', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-webpostleft'));
// define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_WEBPOSTRIGHT', PoP_TemplateIDUtils::get_template_definition('sidebarmulticomponent-webpostright'));

class GD_Template_Processor_CustomPostMultipleSidebarComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_GENERIC,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_LINK,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_HIGHLIGHTLEFT,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_HIGHLIGHTRIGHT,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_WEBPOST,
			// GD_TEMPLATE_SIDEBARMULTICOMPONENT_WEBPOSTLEFT,
			// GD_TEMPLATE_SIDEBARMULTICOMPONENT_WEBPOSTRIGHT,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE:

				// Allow TPP Debate to override, adding GD_TEMPLATE_BUTTONGROUPWRAPPER_OPINIONATEDVOTECOUNT
				$layouts = array();
				$layouts[] = GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE;
				$layouts[] = GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER;
				$layouts = apply_filters('GD_Template_Processor_CustomPostMultipleSidebarComponents:featuredimage:modules', $layouts, $template_id);
				$ret = array_merge(
					$ret,
					$layouts
				);
				break;

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER:

				// Allow TPP Debate to override, adding GD_TEMPLATE_BUTTONGROUPWRAPPER_OPINIONATEDVOTECOUNT
				$layouts = array();
				$layouts[] = GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER;
				// Added through a hook
				// $layouts[] = GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG;
				$layouts[] = GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER;
				$layouts = apply_filters('GD_Template_Processor_CustomPostMultipleSidebarComponents:featuredimagevolunteer:modules', $layouts, $template_id);
				$ret = array_merge(
					$ret,
					$layouts
				);
				break;
				
			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_GENERIC:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_GENERICINFO;
				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS;
				break;
				
			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_LINK:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_LINKINFO;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES;
				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS;
				break;

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_WEBPOST:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_WEBPOSTINFO;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES;
				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS;
				break;
				
			// case GD_TEMPLATE_SIDEBARMULTICOMPONENT_WEBPOSTLEFT:

			// 	// Only if the Volunteering is enabled
			// 	if (defined('POP_GENERICFORMS_PAGE_VOLUNTEER')) {
			// 		$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGEVOLUNTEER;
			// 	}
			// 	else {
			// 		$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATUREDIMAGE;
			// 	}
			// 	$ret[] = GD_TEMPLATE_WIDGETCOMPACT_WEBPOSTINFO;
			// 	$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES;
			// 	break;
				
			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_HIGHLIGHTLEFT:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_HIGHLIGHTINFO;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCES;
				$ret[] = GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER;
				break;
				
			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_HIGHLIGHTRIGHT:
			// case GD_TEMPLATE_SIDEBARMULTICOMPONENT_WEBPOSTRIGHT:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomPostMultipleSidebarComponents();