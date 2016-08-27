<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_EDITMEMBERSHIP', PoP_ServerUtils::get_template_definition('forminner-editmembership'));
define ('GD_TEMPLATE_FORMINNER_MYCOMMUNITIES_UPDATE', PoP_ServerUtils::get_template_definition('forminner-mycommunities-update'));

class GD_URE_Template_Processor_ProfileFormInners extends GD_Template_Processor_FormInnersBase {

	function get_templates_to_process() {
	
		return array(			
			// GD_TEMPLATE_FORMINNER_INVITENEWMEMBERS,
			GD_TEMPLATE_FORMINNER_EDITMEMBERSHIP,
			GD_TEMPLATE_FORMINNER_MYCOMMUNITIES_UPDATE,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			// case GD_TEMPLATE_FORMINNER_INVITENEWMEMBERS:

			// 	$ret[] = GD_URE_TEMPLATE_FORMCOMPONENTGROUP_EMAILS;
			// 	$ret[] = GD_URE_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE;
			// 	$ret[] = GD_TEMPLATE_SUBMITBUTTON_SEND;
			// 	break;

			case GD_TEMPLATE_FORMINNER_EDITMEMBERSHIP:

				$ret[] = GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERSTATUS;
				$ret[] = GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERPRIVILEGES;
				$ret[] = GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERTAGS;
				$ret[] = GD_TEMPLATE_SUBMITBUTTON_SAVE;
				break;

			case GD_TEMPLATE_FORMINNER_MYCOMMUNITIES_UPDATE:

				$ret[] = GD_URE_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES;
				$ret[] = GD_TEMPLATE_SUBMITBUTTON_SAVE;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_MYCOMMUNITIES_UPDATE:

				$this->add_att(GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES, $atts, 'load-itemobject-value', true);
				break;

			case GD_TEMPLATE_FORMINNER_EDITMEMBERSHIP:

				$this->add_att(GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERSTATUS, $atts, 'load-itemobject-value', true);
				$this->add_att(GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERPRIVILEGES, $atts, 'load-itemobject-value', true);
				$this->add_att(GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERTAGS, $atts, 'load-itemobject-value', true);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_ProfileFormInners();