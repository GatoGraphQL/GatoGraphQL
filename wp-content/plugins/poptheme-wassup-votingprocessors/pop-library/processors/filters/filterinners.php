<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('filterinner-opinionatedvotes'));
define ('GD_TEMPLATE_FILTERINNER_AUTHOROPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('filterinner-authoropinionatedvotes'));
define ('GD_TEMPLATE_FILTERINNER_MYOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('filterinner-myopinionatedvotes'));
define ('GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES_AUTHORROLE', PoP_ServerUtils::get_template_definition('filterinner-opinionatedvotes-authorrole'));
define ('GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('filterinner-opinionatedvotes-stance'));
define ('GD_TEMPLATE_FILTERINNER_AUTHOROPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('filterinner-authoropinionatedvotes-stance'));
define ('GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES_GENERALSTANCE', PoP_ServerUtils::get_template_definition('filterinner-opinionatedvotes-generalstance'));

class VotingProcessors_Template_Processor_CustomFilterInners extends GD_Template_Processor_FilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES,
			GD_TEMPLATE_FILTERINNER_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_FILTERINNER_MYOPINIONATEDVOTES,
			GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES_AUTHORROLE,
			GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_FILTERINNER_AUTHOROPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES_GENERALSTANCE,
		);
	}
	
	function get_filter($template_id) {

		$filters = array(
			GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES => GD_FILTER_OPINIONATEDVOTES,
			GD_TEMPLATE_FILTERINNER_AUTHOROPINIONATEDVOTES => GD_FILTER_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_FILTERINNER_MYOPINIONATEDVOTES => GD_FILTER_MYOPINIONATEDVOTES,
			GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES_AUTHORROLE => GD_FILTER_OPINIONATEDVOTES_AUTHORROLE,
			GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES_STANCE => GD_FILTER_OPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_FILTERINNER_AUTHOROPINIONATEDVOTES_STANCE => GD_FILTER_AUTHOROPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_FILTERINNER_OPINIONATEDVOTES_GENERALSTANCE => GD_FILTER_OPINIONATEDVOTES_GENERALSTANCE,
		);
		if ($filter = $filters[$template_id]) {

			return $filter;
		}
		
		return parent::get_filter($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomFilterInners();