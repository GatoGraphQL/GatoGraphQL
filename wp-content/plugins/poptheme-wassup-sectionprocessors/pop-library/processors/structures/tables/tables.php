<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TABLE_MYANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('table-myannouncements'));
define ('GD_TEMPLATE_TABLE_MYDISCUSSIONS', PoP_ServerUtils::get_template_definition('table-mydiscussions'));
define ('GD_TEMPLATE_TABLE_MYLOCATIONPOSTS', PoP_ServerUtils::get_template_definition('table-mylocationposts'));
define ('GD_TEMPLATE_TABLE_MYSTORIES', PoP_ServerUtils::get_template_definition('table-mystories'));

class GD_Custom_Template_Processor_Tables extends GD_Template_Processor_TablesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TABLE_MYANNOUNCEMENTS,
			GD_TEMPLATE_TABLE_MYDISCUSSIONS,
			GD_TEMPLATE_TABLE_MYLOCATIONPOSTS,
			GD_TEMPLATE_TABLE_MYSTORIES,
		);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_TABLE_MYANNOUNCEMENTS:
			case GD_TEMPLATE_TABLE_MYDISCUSSIONS:
			case GD_TEMPLATE_TABLE_MYLOCATIONPOSTS:
			case GD_TEMPLATE_TABLE_MYSTORIES:

				$inners = array(
					GD_TEMPLATE_TABLE_MYANNOUNCEMENTS => GD_TEMPLATE_TABLEINNER_MYANNOUNCEMENTS,
					GD_TEMPLATE_TABLE_MYDISCUSSIONS => GD_TEMPLATE_TABLEINNER_MYDISCUSSIONS,
					GD_TEMPLATE_TABLE_MYLOCATIONPOSTS => GD_TEMPLATE_TABLEINNER_MYLOCATIONPOSTS,
					GD_TEMPLATE_TABLE_MYSTORIES => GD_TEMPLATE_TABLEINNER_MYSTORIES,
				);

				return $inners[$template_id];
		}

		return parent::get_inner_template($template_id);
	}

	function get_header_titles($template_id) {

		$ret = parent::get_header_titles($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_TABLE_MYANNOUNCEMENTS:

				$ret[] = __('Announcement', 'poptheme-wassup-sectionprocessors');
				$ret[] = __('Date', 'poptheme-wassup-sectionprocessors');
				$ret[] = __('Status', 'poptheme-wassup-sectionprocessors');
				break;

			case GD_TEMPLATE_TABLE_MYDISCUSSIONS:

				$ret[] = __('Article', 'poptheme-wassup-sectionprocessors');
				$ret[] = __('Date', 'poptheme-wassup-sectionprocessors');
				$ret[] = __('Status', 'poptheme-wassup-sectionprocessors');
				break;

			case GD_TEMPLATE_TABLE_MYLOCATIONPOSTS:

				$ret[] = gd_get_categoryname(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS); //__('Location post', 'poptheme-wassup-sectionprocessors');
				$ret[] = __('Date', 'poptheme-wassup-sectionprocessors');
				$ret[] = __('Status', 'poptheme-wassup-sectionprocessors');
				break;

			case GD_TEMPLATE_TABLE_MYSTORIES:

				$ret[] = __('Story', 'poptheme-wassup-sectionprocessors');
				$ret[] = __('Date', 'poptheme-wassup-sectionprocessors');
				$ret[] = __('Status', 'poptheme-wassup-sectionprocessors');
				break;
		}
	
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_Tables();