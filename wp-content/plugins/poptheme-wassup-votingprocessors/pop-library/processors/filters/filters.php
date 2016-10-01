<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTER_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('filter-opinionatedvotes'));
define ('GD_TEMPLATE_FILTER_AUTHOROPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('filter-authoropinionatedvote'));
define ('GD_TEMPLATE_FILTER_MYOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('filter-myopinionatedvotes'));
define ('GD_TEMPLATE_FILTER_OPINIONATEDVOTES_AUTHORROLE', PoP_ServerUtils::get_template_definition('filter-opinionatedvotes-authorrole'));
define ('GD_TEMPLATE_FILTER_OPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('filter-opinionatedvotes-stance'));
define ('GD_TEMPLATE_FILTER_AUTHOROPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('filter-authoropinionatedvotes-stance'));
define ('GD_TEMPLATE_FILTER_OPINIONATEDVOTES_GENERALSTANCE', PoP_ServerUtils::get_template_definition('filter-opinionatedvotes-generalstance'));
define ('GD_TEMPLATE_FILTER_TAGOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('filter-tagopinionatedvotes'));
define ('GD_TEMPLATE_FILTER_TAGOPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('filter-tagopinionatedvotes-stance'));

class VotingProcessors_Template_Processor_CustomFilters extends GD_Template_Processor_FiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTER_OPINIONATEDVOTES,
			GD_TEMPLATE_FILTER_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_FILTER_MYOPINIONATEDVOTES,
			GD_TEMPLATE_FILTER_OPINIONATEDVOTES_AUTHORROLE,
			GD_TEMPLATE_FILTER_OPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_FILTER_AUTHOROPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_FILTER_OPINIONATEDVOTES_GENERALSTANCE,
			GD_TEMPLATE_FILTER_TAGOPINIONATEDVOTES,
			GD_TEMPLATE_FILTER_TAGOPINIONATEDVOTES_STANCE,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_FILTER_OPINIONATEDVOTES => GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES,
			GD_TEMPLATE_FILTER_AUTHOROPINIONATEDVOTES => GD_TEMPLATE_FILTERINNER_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_FILTER_MYOPINIONATEDVOTES => GD_TEMPLATE_FILTERINNER_MYOPINIONATEDVOTES,
			GD_TEMPLATE_FILTER_OPINIONATEDVOTES_AUTHORROLE => GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES_AUTHORROLE,
			GD_TEMPLATE_FILTER_OPINIONATEDVOTES_STANCE => GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_FILTER_AUTHOROPINIONATEDVOTES_STANCE => GD_TEMPLATE_FILTERINNER_AUTHOROPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_FILTER_OPINIONATEDVOTES_GENERALSTANCE => GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES_GENERALSTANCE,
			GD_TEMPLATE_FILTER_TAGOPINIONATEDVOTES => GD_TEMPLATE_FILTERINNER_TAGOPINIONATEDVOTES,
			GD_TEMPLATE_FILTER_TAGOPINIONATEDVOTES_STANCE => GD_TEMPLATE_FILTERINNER_TAGOPINIONATEDVOTES_STANCE,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}
	
		return parent::get_inner_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomFilters();