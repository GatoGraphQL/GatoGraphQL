<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('simplefilterinnet-opinionatedvotes'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOROPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('simplefilterinnet-authoropinionatedvote'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_MYOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('simplefilterinnet-myopinionatedvotes'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES_AUTHORROLE', PoP_ServerUtils::get_template_definition('simplefilterinnet-opinionatedvotes-authorrole'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('simplefilterinnet-opinionatedvotes-stance'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOROPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('simplefilterinnet-authoropinionatedvotes-stance'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES_GENERALSTANCE', PoP_ServerUtils::get_template_definition('simplefilterinnet-opinionatedvotes-generalstance'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_TAGOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('simplefilterinnet-tagopinionatedvotes'));
define ('GD_TEMPLATE_SIMPLEFILTERINNER_TAGOPINIONATEDVOTES_STANCE', PoP_ServerUtils::get_template_definition('simplefilterinnet-tagopinionatedvotes-stance'));

class PoPVP_Template_Processor_CustomSimpleFilterInners extends GD_Template_Processor_SimpleFilterInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYOPINIONATEDVOTES,
			GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES_AUTHORROLE,
			GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOROPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES_GENERALSTANCE,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGOPINIONATEDVOTES,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGOPINIONATEDVOTES_STANCE,
		);
	}
	
	function get_filter($template_id) {

		$filters = array(
			GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES => GD_FILTER_OPINIONATEDVOTES,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOROPINIONATEDVOTES => GD_FILTER_AUTHOROPINIONATEDVOTES,
			GD_TEMPLATE_SIMPLEFILTERINNER_MYOPINIONATEDVOTES => GD_FILTER_MYOPINIONATEDVOTES,
			GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES_AUTHORROLE => GD_FILTER_OPINIONATEDVOTES_AUTHORROLE,
			GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES_STANCE => GD_FILTER_OPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_SIMPLEFILTERINNER_AUTHOROPINIONATEDVOTES_STANCE => GD_FILTER_AUTHOROPINIONATEDVOTES_STANCE,
			GD_TEMPLATE_SIMPLEFILTERINNER_OPINIONATEDVOTES_GENERALSTANCE => GD_FILTER_OPINIONATEDVOTES_GENERALSTANCE,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGOPINIONATEDVOTES => GD_FILTER_OPINIONATEDVOTES,
			GD_TEMPLATE_SIMPLEFILTERINNER_TAGOPINIONATEDVOTES_STANCE => GD_FILTER_OPINIONATEDVOTES_STANCE,
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
new PoPVP_Template_Processor_CustomSimpleFilterInners();