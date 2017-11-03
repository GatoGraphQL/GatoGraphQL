<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_AAL_TEMPLATE_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP', PoP_TemplateIDUtils::get_template_definition('ure-aal-multicomponent-layoutuser-membership'));

class Wassup_URE_AAL_Template_Processor_MultiMembership extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_URE_AAL_TEMPLATE_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP:

				$ret[] = GD_URE_AAL_TEMPLATE_LAYOUTUSER_MEMBERSTATUS;
				$ret[] = GD_URE_AAL_TEMPLATE_LAYOUTUSER_MEMBERPRIVILEGES;
				$ret[] = GD_URE_AAL_TEMPLATE_LAYOUTUSER_MEMBERTAGS;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_URE_AAL_TEMPLATE_MULTICOMPONENT_LAYOUTUSER_MEMBERSHIP:

				$this->append_att($template_id, $atts, 'class', 'pop-usermembership');
				foreach ($this->get_modules($template_id) as $module) {

					$this->append_att($module, $atts, 'class', 'item');
				}
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_URE_AAL_Template_Processor_MultiMembership();