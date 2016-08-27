<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TABLE_MYOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('table-myopinionatedvotes'));

class VotingProcessors_Template_Processor_Tables extends GD_Template_Processor_TablesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TABLE_MYOPINIONATEDVOTES,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_TABLE_MYOPINIONATEDVOTES => GD_TEMPLATE_TABLEINNER_MYOPINIONATEDVOTES,
		);

		if ($inner = $inners[$template_id]) {
			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function get_header_titles($template_id) {

		$ret = parent::get_header_titles($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_TABLE_MYOPINIONATEDVOTES:

				$ret[] = gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES);//__('Thought', 'poptheme-wassup-votingprocessors');
				$ret[] = __('Date', 'poptheme-wassup-votingprocessors');
				$ret[] = __('Status', 'poptheme-wassup-votingprocessors');
				break;
		}
	
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_TABLE_MYOPINIONATEDVOTES:
			
				$this->append_att($template_id, $atts, 'class', 'table-myopinionatedvotes');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_Tables();