<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER', PoP_TemplateIDUtils::get_template_definition('volunteerpost-socialmedia-simpleview-wrapper'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER', PoP_TemplateIDUtils::get_template_definition('volunteerpost-socialmedia-wrapper'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER', PoP_TemplateIDUtils::get_template_definition('post-socialmedia-wrapper'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA_COUNTER_POSTWRAPPER', PoP_TemplateIDUtils::get_template_definition('post-socialmedia-counter-wrapper'));
define ('GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER', PoP_TemplateIDUtils::get_template_definition('subjugatedpost-socialmedia-wrapper'));
define ('GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER_POSTWRAPPER', PoP_TemplateIDUtils::get_template_definition('subjugatedpost-socialmedia-counter-wrapper'));

class GD_Template_Processor_SocialMediaPostWrappers extends GD_Template_Processor_SocialMediaPostWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER,
			GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER,
			GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER,
			GD_TEMPLATE_POSTSOCIALMEDIA_COUNTER_POSTWRAPPER,
			GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER,
			GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER_POSTWRAPPER,
		);
	}

	function get_socialmedia_template($template_id) {

		$socialmedias = array(
			GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER => GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER,
			GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER => GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEER,
			GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER => GD_TEMPLATE_POSTSOCIALMEDIA,
			GD_TEMPLATE_POSTSOCIALMEDIA_COUNTER_POSTWRAPPER => GD_TEMPLATE_POSTSOCIALMEDIA_COUNTER,
			GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER => GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA,
			GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER_POSTWRAPPER => GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_COUNTER,
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
new GD_Template_Processor_SocialMediaPostWrappers();