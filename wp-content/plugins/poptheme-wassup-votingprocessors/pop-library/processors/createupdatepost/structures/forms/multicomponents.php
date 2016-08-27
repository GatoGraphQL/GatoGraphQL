<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENT_FORM_OPINIONATEDVOTE_MAYBELEFTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-opinionatedvote-maybeleftside'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_OPINIONATEDVOTE_MAYBERIGHTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-opinionatedvote-mayberightside'));

class VotingProcessors_Template_Processor_FormMultipleComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENT_FORM_OPINIONATEDVOTE_MAYBELEFTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_OPINIONATEDVOTE_MAYBERIGHTSIDE,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_FORM_OPINIONATEDVOTE_MAYBELEFTSIDE:

				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_OPINIONATEDVOTEEDPOST;
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_OPINIONATEDVOTEEDITOR;
				break;

			case GD_TEMPLATE_MULTICOMPONENT_FORM_OPINIONATEDVOTE_MAYBERIGHTSIDE:

				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_BUTTONGROUP_STANCE;
				$ret[] = GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_UNMODERATEDPUBLISH;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_FormMultipleComponents();