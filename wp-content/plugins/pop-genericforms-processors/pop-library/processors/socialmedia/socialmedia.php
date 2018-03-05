<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER', PoP_TemplateIDUtils::get_template_definition('post-socialmedia-simpleview-volunteer'));
define ('GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEER', PoP_TemplateIDUtils::get_template_definition('post-socialmedia-volunteer'));

class PoPCore_GenericForms_Template_Processor_SocialMedia extends GD_Template_Processor_SocialMediaBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER,
			GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEER,
		);
	}

	function get_modules($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER:

				return array(
					GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND,
					GD_TEMPLATE_MULTICOMPONENT_POSTSOCIALMEDIA,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY,
				);

			case GD_TEMPLATE_POSTSOCIALMEDIA_VOLUNTEER:

				return array(
					GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND,
					GD_TEMPLATE_MULTICOMPONENT_POSTOPTIONS,
					GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY,
				);
		}
		
		return parent::get_modules($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER:

				$modules = $this->get_modules($template_id);
				foreach ($modules as $module) {
					$this->append_att($module, $atts, 'class', 'inline');
				}
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPCore_GenericForms_Template_Processor_SocialMedia();