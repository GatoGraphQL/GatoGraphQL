<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_OPINIONATEDVOTEEDIT', PoP_ServerUtils::get_template_definition('quicklinkbuttongroup-opinionatedvoteedit'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_OPINIONATEDVOTEVIEW', PoP_ServerUtils::get_template_definition('quicklinkbuttongroup-opinionatedvoteview'));
define ('GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTSTANCE', PoP_ServerUtils::get_template_definition('quicklinkbuttongroup-poststance'));

class VotingProcessors_Template_Processor_QuicklinkButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_OPINIONATEDVOTEEDIT,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_OPINIONATEDVOTEVIEW,
			GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTSTANCE,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {
		
			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_OPINIONATEDVOTEEDIT:

				$ret[] = GD_TEMPLATE_BUTTON_OPINIONATEDVOTEEDIT;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_OPINIONATEDVOTEVIEW:

				$ret[] = GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTEVIEW;
				break;

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTSTANCE:

				$ret[] = GD_TEMPLATE_CODE_POSTSTANCE;
				$ret[] = GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_PRO;
				$ret[] = GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_NEUTRAL;
				$ret[] = GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_AGAINST;
				break;
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTSTANCE:

				$this->append_att($template_id, $atts, 'class', 'pop-stance-count');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_QuicklinkButtonGroups();