<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_MARKALLNOTIFICATIONSASREAD', PoP_TemplateIDUtils::get_template_definition('blockgroup-markallnotificationsasread'));
define ('GD_TEMPLATE_BLOCKGROUP_MARKNOTIFICATIONASREAD', PoP_TemplateIDUtils::get_template_definition('blockgroup-marknotificationasread'));
define ('GD_TEMPLATE_BLOCKGROUP_MARKNOTIFICATIONASUNREAD', PoP_TemplateIDUtils::get_template_definition('blockgroup-marknotificationasunread'));

class GD_AAL_Template_Processor_FunctionsBlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_MARKALLNOTIFICATIONSASREAD,
			GD_TEMPLATE_BLOCKGROUP_MARKNOTIFICATIONASREAD,
			GD_TEMPLATE_BLOCKGROUP_MARKNOTIFICATIONASUNREAD,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_MARKALLNOTIFICATIONSASREAD:

				$ret[] = GD_TEMPLATE_ACTION_MARKALLNOTIFICATIONSASREAD;
				$ret[] = GD_TEMPLATE_BLOCKDATA_MARKALLNOTIFICATIONSASREAD;
				break;

			case GD_TEMPLATE_BLOCKGROUP_MARKNOTIFICATIONASREAD:

				$ret[] = GD_TEMPLATE_ACTION_MARKNOTIFICATIONASREAD;
				$ret[] = GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASREAD;
				break;

			case GD_TEMPLATE_BLOCKGROUP_MARKNOTIFICATIONASUNREAD:

				$ret[] = GD_TEMPLATE_ACTION_MARKNOTIFICATIONASUNREAD;
				$ret[] = GD_TEMPLATE_BLOCKDATA_MARKNOTIFICATIONASUNREAD;
				break;
		}

		return $ret;
	}

}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_AAL_Template_Processor_FunctionsBlockGroups();
