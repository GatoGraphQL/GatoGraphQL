<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('delegatorfilter-opinionatedvotes'));
define ('GD_TEMPLATE_DELEGATORFILTER_AUTHOROPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('delegatorfilter-authoropinionatedvote'));
define ('GD_TEMPLATE_DELEGATORFILTER_MYOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('delegatorfilter-myopinionatedvotes'));
define ('GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES_AUTHORROLE', PoP_ServerUtils::get_template_definition('delegatorfilter-opinionatedvotes-authorrole'));
define ('GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('delegatorfilter-opinionatedvotes-stance'));
define ('GD_TEMPLATE_DELEGATORFILTER_AUTHOROPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('delegatorfilter-authoropinionatedvotes-stance'));
define ('GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES_GENERALSTANCE', PoP_ServerUtils::get_template_definition('delegatorfilter-opinionatedvotes-generalstance'));

class PoPVP_Template_Processor_CustomDelegatorFilters extends GD_Template_Processor_CustomDelegatorFiltersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES,
			GD_TEMPLATE_DELEGATORFILTER_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_DELEGATORFILTER_MYOPINIONATEDVOTES,
			GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES_AUTHORROLE,
			GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_DELEGATORFILTER_AUTHOROPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES_GENERALSTANCE,
		);
	}
	
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES => GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES,
			GD_TEMPLATE_DELEGATORFILTER_AUTHOROPINIONATEDVOTES => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_DELEGATORFILTER_MYOPINIONATEDVOTES => GD_TEMPLATE_SIMPLEFILTERINNER_MYOPINIONATEDVOTES,
			GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES_AUTHORROLE => GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES_AUTHORROLE,
			GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES_STANCE => GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_DELEGATORFILTER_AUTHOROPINIONATEDVOTES_STANCE => GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOROPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_DELEGATORFILTER_OPINIONATEDVOTES_GENERALSTANCE => GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES_GENERALSTANCE,
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
new PoPVP_Template_Processor_CustomDelegatorFilters();