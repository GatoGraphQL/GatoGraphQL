<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TABLE_MYCONTENT', PoP_ServerUtils::get_template_definition('table-mycontent'));
define ('GD_TEMPLATE_TABLE_MYLINKS', PoP_ServerUtils::get_template_definition('table-mylinks'));
define ('GD_TEMPLATE_TABLE_MYHIGHLIGHTS', PoP_ServerUtils::get_template_definition('table-myhighlights'));
define ('GD_TEMPLATE_TABLE_MYWEBPOSTS', PoP_ServerUtils::get_template_definition('table-mywebposts'));
// define ('GD_TEMPLATE_TABLE_MYRESOURCES', PoP_ServerUtils::get_template_definition('table-myresources'));

class GD_Template_Processor_Tables extends GD_Template_Processor_TablesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TABLE_MYCONTENT,
			GD_TEMPLATE_TABLE_MYLINKS,
			GD_TEMPLATE_TABLE_MYHIGHLIGHTS,
			GD_TEMPLATE_TABLE_MYWEBPOSTS,
			// GD_TEMPLATE_TABLE_MYRESOURCES,
		);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_TABLE_MYCONTENT:
			case GD_TEMPLATE_TABLE_MYLINKS:
			case GD_TEMPLATE_TABLE_MYHIGHLIGHTS:
			case GD_TEMPLATE_TABLE_MYWEBPOSTS:
			// case GD_TEMPLATE_TABLE_MYRESOURCES:

				$inners = array(
					GD_TEMPLATE_TABLE_MYCONTENT => GD_TEMPLATE_TABLEINNER_MYCONTENT,
					GD_TEMPLATE_TABLE_MYLINKS => GD_TEMPLATE_TABLEINNER_MYLINKS,
					GD_TEMPLATE_TABLE_MYHIGHLIGHTS => GD_TEMPLATE_TABLEINNER_MYHIGHLIGHTS,
					GD_TEMPLATE_TABLE_MYWEBPOSTS => GD_TEMPLATE_TABLEINNER_MYWEBPOSTS,
					// GD_TEMPLATE_TABLE_MYRESOURCES => GD_TEMPLATE_TABLEINNER_MYRESOURCES,
				);

				return $inners[$template_id];
		}

		return parent::get_inner_template($template_id);
	}

	function get_header_titles($template_id) {

		$ret = parent::get_header_titles($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_TABLE_MYCONTENT:

				$ret[] = __('Content', 'poptheme-wassup');
				$ret[] = __('Status', 'poptheme-wassup');
				break;

			case GD_TEMPLATE_TABLE_MYLINKS:

				$ret[] = __('Link', 'poptheme-wassup');
				$ret[] = __('Date', 'poptheme-wassup');
				$ret[] = __('Status', 'poptheme-wassup');
				break;

			case GD_TEMPLATE_TABLE_MYHIGHLIGHTS:

				$ret[] = __('Highlight', 'poptheme-wassup');
				$ret[] = __('Date', 'poptheme-wassup');
				$ret[] = __('Status', 'poptheme-wassup');
				break;

			case GD_TEMPLATE_TABLE_MYWEBPOSTS:

				$ret[] = __('Post', 'poptheme-wassup');
				$ret[] = __('Date', 'poptheme-wassup');
				$ret[] = __('Status', 'poptheme-wassup');
				break;
		}
	
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_TABLE_MYHIGHLIGHTS:
			
				$this->append_att($template_id, $atts, 'class', 'table-myhighlights');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_Tables();