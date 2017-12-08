<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_INVITENEWUSERS', PoP_TemplateIDUtils::get_template_definition('forminner-inviteusers'));
define ('GD_TEMPLATE_FORMINNER_EVERYTHINGQUICKLINKS', PoP_TemplateIDUtils::get_template_definition('forminner-everythingquicklinks'));

class PoP_Core_Template_Processor_FormInners extends GD_Template_Processor_FormInnersBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_FORMINNER_INVITENEWUSERS,
			GD_TEMPLATE_FORMINNER_EVERYTHINGQUICKLINKS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_INVITENEWUSERS:

				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILS;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_SENDERNAME;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_CAPTCHA;
				$ret[] = GD_TEMPLATE_SUBMITBUTTON_SEND;
				break;

			case GD_TEMPLATE_FORMINNER_EVERYTHINGQUICKLINKS:

				$ret[] = GD_TEMPLATE_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Core_Template_Processor_FormInners();