<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER', PoP_TemplateIDUtils::get_template_definition('volunteerpost-socialmedia-simpleview-wrapper'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER', PoP_TemplateIDUtils::get_template_definition('volunteerpost-socialmedia-wrapper'));

class PoPCore_GenericForms_Template_Processor_SocialMediaPostWrappers extends GD_Template_Processor_SocialMediaPostWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER,
			GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER,
		);
	}

	function get_socialmedia_template($template_id) {

		$socialmedias = array(
			GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER => GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER,
			GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER => GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEER,
		);

		if ($socialmedia = $socialmedias[$template_id]) {
			return $socialmedia;
		}
	
		return parent::get_socialmedia_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPCore_GenericForms_Template_Processor_SocialMediaPostWrappers();