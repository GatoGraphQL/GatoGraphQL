<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENTINNER_WEBSITEFRAMEWORK', PoP_ServerUtils::get_template_definition('contentinner-websiteframework'));
define ('GD_TEMPLATE_CONTENTINNER_DESIGNPRINCIPLES_API', PoP_ServerUtils::get_template_definition('contentinner-designprinciples-api'));
define ('GD_TEMPLATE_CONTENTINNER_DESIGNPRINCIPLES_DESCENTRALIZATION', PoP_ServerUtils::get_template_definition('contentinner-designprinciples-decentralization'));
// define ('GD_TEMPLATE_CONTENTINNER_WEBSITEFRAMEWORK_GETPOST', PoP_ServerUtils::get_template_definition('contentinner-websiteframework-getpost'));
// define ('GD_TEMPLATE_CONTENTINNER_WEBSITEFRAMEWORK_MICROSERVICES', PoP_ServerUtils::get_template_definition('contentinner-websiteframework-microservices'));
define ('GD_TEMPLATE_CONTENTINNER_DEMODOWNLOADS', PoP_ServerUtils::get_template_definition('contentinner-demodownloads'));
define ('GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_PAGECONTAINERS', PoP_ServerUtils::get_template_definition('contentinner-websitefeatures-pagecontainers'));
define ('GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_THEMEMODES', PoP_ServerUtils::get_template_definition('contentinner-websitefeatures-thememodes'));
define ('GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_ADDITIONALS', PoP_ServerUtils::get_template_definition('contentinner-websitefeatures-additionals'));
define ('GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_UNDERTHEHOOD', PoP_ServerUtils::get_template_definition('contentinner-websitefeatures-underthehood'));
define ('GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_IDEALFORIMPLEMENTING', PoP_ServerUtils::get_template_definition('contentinner-websitefeatures-idealforimplementing'));
define ('GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_TODOS', PoP_ServerUtils::get_template_definition('contentinner-websitefeatures-todos'));

class GetPoP_Template_Processor_SingleContentInners extends GD_Template_Processor_ContentSingleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENTINNER_WEBSITEFRAMEWORK,
			GD_TEMPLATE_CONTENTINNER_DESIGNPRINCIPLES_API,
			GD_TEMPLATE_CONTENTINNER_DESIGNPRINCIPLES_DESCENTRALIZATION,
			// GD_TEMPLATE_CONTENTINNER_WEBSITEFRAMEWORK_GETPOST,
			// GD_TEMPLATE_CONTENTINNER_WEBSITEFRAMEWORK_MICROSERVICES,
			GD_TEMPLATE_CONTENTINNER_DEMODOWNLOADS,
			GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_PAGECONTAINERS,
			GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_THEMEMODES,
			GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_ADDITIONALS,
			GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_UNDERTHEHOOD,
			GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_IDEALFORIMPLEMENTING,
			GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_TODOS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_CONTENTINNER_WEBSITEFRAMEWORK => GD_TEMPLATE_CODE_WEBSITEFRAMEWORK,
			GD_TEMPLATE_CONTENTINNER_DEMODOWNLOADS => GD_TEMPLATE_CODE_DEMODOWNLOADS,
			GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_PAGECONTAINERS => GD_TEMPLATE_CODE_WEBSITEFEATURES_PAGECONTAINERS,
			GD_TEMPLATE_CONTENTINNER_DESIGNPRINCIPLES_API => GD_TEMPLATE_CODE_DESIGNPRINCIPLES_API,
			GD_TEMPLATE_CONTENTINNER_DESIGNPRINCIPLES_DESCENTRALIZATION => GD_TEMPLATE_CODE_DESIGNPRINCIPLES_DESCENTRALIZATION,
			// GD_TEMPLATE_CONTENTINNER_WEBSITEFRAMEWORK_GETPOST => GD_TEMPLATE_CODE_WEBSITEFRAMEWORK_GETPOST,
			// GD_TEMPLATE_CONTENTINNER_WEBSITEFRAMEWORK_MICROSERVICES => GD_TEMPLATE_CODE_WEBSITEFRAMEWORK_MICROSERVICES,
			GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_THEMEMODES => GD_TEMPLATE_CODE_WEBSITEFEATURES_THEMEMODES,
			GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_ADDITIONALS => GD_TEMPLATE_CODE_WEBSITEFEATURES_ADDITIONALS,
			GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_UNDERTHEHOOD => GD_TEMPLATE_CODE_WEBSITEFEATURES_UNDERTHEHOOD,
			GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_IDEALFORIMPLEMENTING => GD_TEMPLATE_CODE_WEBSITEFEATURES_IDEALFORIMPLEMENTING,
			GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_TODOS => GD_TEMPLATE_CODE_WEBSITEFEATURES_TODOS,
		);
		if ($layout = $layouts[$template_id]) {
			$ret[] = $layout;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_Template_Processor_SingleContentInners();