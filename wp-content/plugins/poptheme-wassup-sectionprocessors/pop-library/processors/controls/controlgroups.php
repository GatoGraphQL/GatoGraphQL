<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTROLGROUP_MYLOCATIONPOSTLIST', PoP_TemplateIDUtils::get_template_definition('controlgroup-mylocationpostlist'));
define ('GD_TEMPLATE_CONTROLGROUP_MYSTORYLIST', PoP_TemplateIDUtils::get_template_definition('controlgroup-mystorylist'));
define ('GD_TEMPLATE_CONTROLGROUP_MYANNOUNCEMENTLIST', PoP_TemplateIDUtils::get_template_definition('controlgroup-myannouncementlist'));
define ('GD_TEMPLATE_CONTROLGROUP_MYDISCUSSIONLIST', PoP_TemplateIDUtils::get_template_definition('controlgroup-mydiscussionlist'));

class SectionProcessors_Template_Processor_ControlGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTROLGROUP_MYLOCATIONPOSTLIST,
			GD_TEMPLATE_CONTROLGROUP_MYSTORYLIST,
			GD_TEMPLATE_CONTROLGROUP_MYANNOUNCEMENTLIST,
			GD_TEMPLATE_CONTROLGROUP_MYDISCUSSIONLIST,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		global $gd_template_processor_manager;

		switch ($template_id) {
				
			case GD_TEMPLATE_CONTROLGROUP_MYLOCATIONPOSTLIST:
			case GD_TEMPLATE_CONTROLGROUP_MYSTORYLIST:
			case GD_TEMPLATE_CONTROLGROUP_MYANNOUNCEMENTLIST:
			case GD_TEMPLATE_CONTROLGROUP_MYDISCUSSIONLIST:

				$addposts = array(
					GD_TEMPLATE_CONTROLGROUP_MYLOCATIONPOSTLIST => GD_TEMPLATE_CONTROLBUTTONGROUP_ADDLOCATIONPOST,
					GD_TEMPLATE_CONTROLGROUP_MYSTORYLIST => GD_TEMPLATE_CONTROLBUTTONGROUP_ADDSTORY,
					GD_TEMPLATE_CONTROLGROUP_MYANNOUNCEMENTLIST => GD_TEMPLATE_CONTROLBUTTONGROUP_ADDANNOUNCEMENT,
					GD_TEMPLATE_CONTROLGROUP_MYDISCUSSIONLIST => GD_TEMPLATE_CONTROLBUTTONGROUP_ADDDISCUSSION,
				);

				$ret[] = $addposts[$template_id];
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP;
				$ret[] = GD_TEMPLATE_CONTROLBUTTONGROUP_FILTER;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new SectionProcessors_Template_Processor_ControlGroups();