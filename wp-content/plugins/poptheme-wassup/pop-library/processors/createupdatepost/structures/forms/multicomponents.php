<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_MODERATEDPUBLISH', PoP_ServerUtils::get_template_definition('multicomponent-formcomponents-moderatedpublish'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_UNMODERATEDPUBLISH', PoP_ServerUtils::get_template_definition('multicomponent-formcomponents-unmoderatedpublish'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_LEFTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-leftside'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_LINK_LEFTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-link-left'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOSTLINK_LEFTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-webpostlink-left'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOSTLINK_RIGHTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-webpostlink-rightside'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOST_LEFTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-webpost-leftside'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOST_RIGHTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-webpost-rightside'));

class Wassup_Template_Processor_FormMultipleComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_MODERATEDPUBLISH,
			GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_UNMODERATEDPUBLISH,
			GD_TEMPLATE_MULTICOMPONENT_FORM_LEFTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_LINK_LEFTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOSTLINK_LEFTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOSTLINK_RIGHTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOST_LEFTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOST_RIGHTSIDE,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_MODERATEDPUBLISH:

				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_STATUS;
				$ret[] = GD_TEMPLATE_SUBMITBUTTON_SUBMIT;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_UNMODERATEDPUBLISH:

				$ret[] = GD_TEMPLATE_FORMCOMPONENT_CUP_KEEPASDRAFT;
				$ret[] = GD_TEMPLATE_SUBMITBUTTON_SUBMIT;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_FORM_LEFTSIDE:

				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_TITLE;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EDITOR;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_FORM_LINK_LEFTSIDE:

				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_LINK;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_LINKTITLE;
				$ret[] = GD_TEMPLATE_FORMGROUP_EMBEDPREVIEW;
				$ret[] = GD_TEMPLATE_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOSTLINK_LEFTSIDE:

				// if (PoPTheme_Wassup_Utils::add_webpost_sections()) {
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_BUTTONGROUP_WEBPOSTSECTIONS;
				// }
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_LINK;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_LINKTITLE;
				$ret[] = GD_TEMPLATE_FORMGROUP_EMBEDPREVIEW;
				$ret[] = GD_TEMPLATE_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOSTLINK_RIGHTSIDE:

				$ret[] = GD_TEMPLATE_WIDGET_FORM_WEBPOSTLINKDETAILS;
				$ret[] = GD_TEMPLATE_WIDGET_FORM_FEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_WIDGET_FORM_METAINFORMATION;
				$status = GD_CreateUpdate_Utils::moderate() ? GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_MODERATEDPUBLISH : GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_UNMODERATEDPUBLISH;
				$ret[] = $status;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOST_LEFTSIDE:

				// if (PoPTheme_Wassup_Utils::add_webpost_sections()) {
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_BUTTONGROUP_WEBPOSTSECTIONS;
				// }
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_TITLE;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EDITOR;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOST_RIGHTSIDE:

				$ret[] = GD_TEMPLATE_WIDGET_FORM_WEBPOSTDETAILS;
				$ret[] = GD_TEMPLATE_WIDGET_FORM_FEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_WIDGET_FORM_METAINFORMATION;
				$status = GD_CreateUpdate_Utils::moderate() ? GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_MODERATEDPUBLISH : GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_UNMODERATEDPUBLISH;
				$ret[] = $status;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			// case GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_MODERATEDPUBLISH:

			// 	if (!($classs = $this->get_general_att($atts, 'alert-class'))) {
			// 		$classs = 'alert alert-info';
			// 	}
			// 	$this->append_att($template_id, $atts, 'class', $classs);
			// 	break;

			case GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOSTLINK_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOST_RIGHTSIDE:

				if (!($classs = $this->get_general_att($atts, 'formcomponent-publish-class'))) {
					$classs = 'alert alert-info';
				}
				$status = GD_CreateUpdate_Utils::moderate() ? GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_MODERATEDPUBLISH : GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_UNMODERATEDPUBLISH;
				$this->append_att($status, $atts, 'class', $classs);
				break;
		
			case GD_TEMPLATE_MULTICOMPONENT_FORM_LINK_LEFTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOSTLINK_LEFTSIDE:

				// Bind the Embed iframe and the input together. When the input value changes, the iframe
				// will update itself with the URL in the input
				$this->add_att(GD_TEMPLATE_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR, $atts, 'iframe-template', GD_TEMPLATE_LAYOUT_USERINPUTEMBEDPREVIEW);
				$this->add_att(GD_TEMPLATE_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR, $atts, 'input-template', GD_TEMPLATE_FORMCOMPONENT_LINK);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_FormMultipleComponents();