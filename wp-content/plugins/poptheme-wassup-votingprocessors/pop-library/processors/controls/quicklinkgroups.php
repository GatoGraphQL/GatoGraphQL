<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_QUICKLINKGROUP_OPINIONATEDVOTEEDIT', PoP_ServerUtils::get_template_definition('quicklinkgroup-opinionatedvoteedit'));
define ('GD_TEMPLATE_QUICKLINKGROUP_OPINIONATEDVOTECONTENT', PoP_ServerUtils::get_template_definition('quicklinkgroup-opinionatedvotecontent'));

class VotingProcessors_Template_Processor_CustomQuicklinkGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_QUICKLINKGROUP_OPINIONATEDVOTEEDIT,
			GD_TEMPLATE_QUICKLINKGROUP_OPINIONATEDVOTECONTENT,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_QUICKLINKGROUP_OPINIONATEDVOTEEDIT:

				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_OPINIONATEDVOTEEDIT;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_OPINIONATEDVOTEVIEW;
				break;

			case GD_TEMPLATE_QUICKLINKGROUP_OPINIONATEDVOTECONTENT:

				$ret[] = GD_TEMPLATE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS;
				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTPERMALINK;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_QUICKLINKGROUP_OPINIONATEDVOTECONTENT:

				// Make the level below also a 'btn-group' so it shows inline
				$downlevels = array(
					GD_TEMPLATE_QUICKLINKGROUP_OPINIONATEDVOTECONTENT => GD_TEMPLATE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST,
				);			
				// $this->append_att($downlevels[$template_id], $atts, 'class', 'btn-group bg-warning');
				$this->append_att($downlevels[$template_id], $atts, 'class', 'btn-group');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomQuicklinkGroups();