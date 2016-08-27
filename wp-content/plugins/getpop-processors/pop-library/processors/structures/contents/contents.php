<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENT_WEBSITEFRAMEWORK', PoP_ServerUtils::get_template_definition('content-websiteframework'));
define ('GD_TEMPLATE_CONTENT_DESIGNPRINCIPLES_API', PoP_ServerUtils::get_template_definition('content-designprinciples-api'));
define ('GD_TEMPLATE_CONTENT_DESIGNPRINCIPLES_DESCENTRALIZATION', PoP_ServerUtils::get_template_definition('content-designprinciples-decentralization'));
// define ('GD_TEMPLATE_CONTENT_WEBSITEFRAMEWORK_GETPOST', PoP_ServerUtils::get_template_definition('content-websiteframework-getpost'));
// define ('GD_TEMPLATE_CONTENT_WEBSITEFRAMEWORK_MICROSERVICES', PoP_ServerUtils::get_template_definition('content-websiteframework-microservices'));
define ('GD_TEMPLATE_CONTENT_DEMODOWNLOADS', PoP_ServerUtils::get_template_definition('content-demodownloads'));
define ('GD_TEMPLATE_CONTENT_WEBSITEFEATURES_PAGECONTAINERS', PoP_ServerUtils::get_template_definition('content-websitefeatures-pagecontainers'));
define ('GD_TEMPLATE_CONTENT_WEBSITEFEATURES_THEMEMODES', PoP_ServerUtils::get_template_definition('content-websitefeatures-thememodes'));
define ('GD_TEMPLATE_CONTENT_WEBSITEFEATURES_ADDITIONALS', PoP_ServerUtils::get_template_definition('content-websitefeatures-additionals'));
define ('GD_TEMPLATE_CONTENT_WEBSITEFEATURES_UNDERTHEHOOD', PoP_ServerUtils::get_template_definition('content-websitefeatures-underthehood'));
define ('GD_TEMPLATE_CONTENT_WEBSITEFEATURES_IDEALFORIMPLEMENTING', PoP_ServerUtils::get_template_definition('content-websitefeatures-idealforimplementing'));
define ('GD_TEMPLATE_CONTENT_WEBSITEFEATURES_TODOS', PoP_ServerUtils::get_template_definition('content-websitefeatures-todos'));

class GetPoP_Template_Processor_Contents extends GD_Template_Processor_ContentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENT_WEBSITEFRAMEWORK,
			GD_TEMPLATE_CONTENT_DESIGNPRINCIPLES_API,
			GD_TEMPLATE_CONTENT_DESIGNPRINCIPLES_DESCENTRALIZATION,
			// GD_TEMPLATE_CONTENT_WEBSITEFRAMEWORK_GETPOST,
			// GD_TEMPLATE_CONTENT_WEBSITEFRAMEWORK_MICROSERVICES,
			GD_TEMPLATE_CONTENT_DEMODOWNLOADS,
			GD_TEMPLATE_CONTENT_WEBSITEFEATURES_PAGECONTAINERS,
			GD_TEMPLATE_CONTENT_WEBSITEFEATURES_THEMEMODES,
			GD_TEMPLATE_CONTENT_WEBSITEFEATURES_ADDITIONALS,
			GD_TEMPLATE_CONTENT_WEBSITEFEATURES_UNDERTHEHOOD,
			GD_TEMPLATE_CONTENT_WEBSITEFEATURES_IDEALFORIMPLEMENTING,
			GD_TEMPLATE_CONTENT_WEBSITEFEATURES_TODOS,
		);
	}
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_CONTENT_WEBSITEFRAMEWORK => GD_TEMPLATE_CONTENTINNER_WEBSITEFRAMEWORK,
			GD_TEMPLATE_CONTENT_DEMODOWNLOADS => GD_TEMPLATE_CONTENTINNER_DEMODOWNLOADS,
			GD_TEMPLATE_CONTENT_WEBSITEFEATURES_PAGECONTAINERS => GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_PAGECONTAINERS,
			GD_TEMPLATE_CONTENT_DESIGNPRINCIPLES_API => GD_TEMPLATE_CONTENTINNER_DESIGNPRINCIPLES_API,
			GD_TEMPLATE_CONTENT_DESIGNPRINCIPLES_DESCENTRALIZATION => GD_TEMPLATE_CONTENTINNER_DESIGNPRINCIPLES_DESCENTRALIZATION,
			// GD_TEMPLATE_CONTENT_WEBSITEFRAMEWORK_GETPOST => GD_TEMPLATE_CONTENTINNER_WEBSITEFRAMEWORK_GETPOST,
			// GD_TEMPLATE_CONTENT_WEBSITEFRAMEWORK_MICROSERVICES => GD_TEMPLATE_CONTENTINNER_WEBSITEFRAMEWORK_MICROSERVICES,
			GD_TEMPLATE_CONTENT_WEBSITEFEATURES_THEMEMODES => GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_THEMEMODES,
			GD_TEMPLATE_CONTENT_WEBSITEFEATURES_ADDITIONALS => GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_ADDITIONALS,
			GD_TEMPLATE_CONTENT_WEBSITEFEATURES_UNDERTHEHOOD => GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_UNDERTHEHOOD,
			GD_TEMPLATE_CONTENT_WEBSITEFEATURES_IDEALFORIMPLEMENTING => GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_IDEALFORIMPLEMENTING,
			GD_TEMPLATE_CONTENT_WEBSITEFEATURES_TODOS => GD_TEMPLATE_CONTENTINNER_WEBSITEFEATURES_TODOS,
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
new GetPoP_Template_Processor_Contents();