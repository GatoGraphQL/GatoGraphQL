<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTWRAPPER_USEROPINIONATEDVOTEPOSTINTERACTION', PoP_ServerUtils::get_template_definition('layoutwrapper-useropinionatedvotepostinteraction'));
define ('VOTINGPROCESSORS_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION', PoP_ServerUtils::get_template_definition('votingprocessors-layoutwrapper-userpostinteraction'));
define ('VOTINGPROCESSORS_TEMPLATE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION', PoP_ServerUtils::get_template_definition('votingprocessors-layoutwrapper-userfullviewinteraction'));

class VotingProcessors_Template_Processor_CustomWrapperLayouts extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTWRAPPER_USEROPINIONATEDVOTEPOSTINTERACTION,
			VOTINGPROCESSORS_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION,
			VOTINGPROCESSORS_TEMPLATE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTWRAPPER_USEROPINIONATEDVOTEPOSTINTERACTION:

				$ret[] = GD_TEMPLATE_LAYOUT_USEROPINIONATEDVOTEPOSTINTERACTION;
				break;

			case VOTINGPROCESSORS_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION:

				$ret[] = VOTINGPROCESSORS_TEMPLATE_LAYOUT_USERPOSTINTERACTION;
				break;

			case VOTINGPROCESSORS_TEMPLATE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION:

				$ret[] = VOTINGPROCESSORS_TEMPLATE_LAYOUT_USERFULLVIEWINTERACTION;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTWRAPPER_USEROPINIONATEDVOTEPOSTINTERACTION:
			case VOTINGPROCESSORS_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION:
			case VOTINGPROCESSORS_TEMPLATE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION:
			
				return 'published';
		}

		return parent::get_condition_field($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_LAYOUTWRAPPER_USEROPINIONATEDVOTEPOSTINTERACTION:
			case VOTINGPROCESSORS_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION:
			case VOTINGPROCESSORS_TEMPLATE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION:

				$this->append_att($template_id, $atts, 'class', 'userpostinteraction clearfix');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomWrapperLayouts();