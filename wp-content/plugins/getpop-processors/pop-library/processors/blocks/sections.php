<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/*********************************************
 * Website Features
 *********************************************/
define ('GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK', PoP_ServerUtils::get_template_definition('block-websiteframework'));
define ('GD_TEMPLATE_BLOCK_DESIGNPRINCIPLES_API', PoP_ServerUtils::get_template_definition('block-designprinciples-api'));
define ('GD_TEMPLATE_BLOCK_DESIGNPRINCIPLES_DESCENTRALIZATION', PoP_ServerUtils::get_template_definition('block-designprinciples-descentralization'));
// define ('GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK_GETPOST', PoP_ServerUtils::get_template_definition('block-websiteframework-getpost'));
// define ('GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK_MICROSERVICES', PoP_ServerUtils::get_template_definition('block-websiteframework-microservices'));
define ('GD_TEMPLATE_BLOCK_DEMODOWNLOADS', PoP_ServerUtils::get_template_definition('block-demodownloads'));
define ('GD_TEMPLATE_BLOCK_WEBSITEFEATURES_PAGECONTAINERS', PoP_ServerUtils::get_template_definition('block-websitefeatures-pagecontainers'));
define ('GD_TEMPLATE_BLOCK_WEBSITEFEATURES_THEMEMODES', PoP_ServerUtils::get_template_definition('block-websitefeatures-thememodes'));
define ('GD_TEMPLATE_BLOCK_WEBSITEFEATURES_ADDITIONALS', PoP_ServerUtils::get_template_definition('block-websitefeatures-additionals'));
define ('GD_TEMPLATE_BLOCK_WEBSITEFEATURES_UNDERTHEHOOD', PoP_ServerUtils::get_template_definition('block-websitefeatures-underthehood'));
define ('GD_TEMPLATE_BLOCK_WEBSITEFEATURES_IDEALFORIMPLEMENTING', PoP_ServerUtils::get_template_definition('block-websitefeatures-idealforimplementing'));
define ('GD_TEMPLATE_BLOCK_WEBSITEFEATURES_TODOS', PoP_ServerUtils::get_template_definition('block-websitefeatures-todos'));
define ('GD_TEMPLATE_BLOCK_CONTACTABOUTUS', PoP_ServerUtils::get_template_definition('block-contact-aboutus'));

class GetPoP_Template_Processor_CustomSectionBlocks extends GD_Template_Processor_BlocksBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK,
			GD_TEMPLATE_BLOCK_DESIGNPRINCIPLES_API,
			GD_TEMPLATE_BLOCK_DESIGNPRINCIPLES_DESCENTRALIZATION,
			// GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK_GETPOST,
			// GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK_MICROSERVICES,
			GD_TEMPLATE_BLOCK_DEMODOWNLOADS,
			GD_TEMPLATE_BLOCK_WEBSITEFEATURES_PAGECONTAINERS,
			GD_TEMPLATE_BLOCK_WEBSITEFEATURES_THEMEMODES,
			GD_TEMPLATE_BLOCK_WEBSITEFEATURES_ADDITIONALS,
			GD_TEMPLATE_BLOCK_WEBSITEFEATURES_UNDERTHEHOOD,
			GD_TEMPLATE_BLOCK_WEBSITEFEATURES_IDEALFORIMPLEMENTING,
			GD_TEMPLATE_BLOCK_WEBSITEFEATURES_TODOS,
			GD_TEMPLATE_BLOCK_CONTACTABOUTUS,
		);
	}

	protected function get_block_inner_templates($template_id) {

		global $gd_template_processor_manager;

		$ret = parent::get_block_inner_templates($template_id);

		$inners = array(			
			GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK => GD_TEMPLATE_CONTENT_WEBSITEFRAMEWORK,
			GD_TEMPLATE_BLOCK_DEMODOWNLOADS => GD_TEMPLATE_POSTCODE_DEMODOWNLOADS, //GD_TEMPLATE_CONTENT_DEMODOWNLOADS,
			GD_TEMPLATE_BLOCK_WEBSITEFEATURES_PAGECONTAINERS => GD_TEMPLATE_CONTENT_WEBSITEFEATURES_PAGECONTAINERS,
			GD_TEMPLATE_BLOCK_DESIGNPRINCIPLES_API => GD_TEMPLATE_CONTENT_DESIGNPRINCIPLES_API,
			GD_TEMPLATE_BLOCK_DESIGNPRINCIPLES_DESCENTRALIZATION => GD_TEMPLATE_CONTENT_DESIGNPRINCIPLES_DESCENTRALIZATION,
			// GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK_GETPOST => GD_TEMPLATE_CONTENT_WEBSITEFRAMEWORK_GETPOST,
			// GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK_MICROSERVICES => GD_TEMPLATE_CONTENT_WEBSITEFRAMEWORK_MICROSERVICES,
			GD_TEMPLATE_BLOCK_WEBSITEFEATURES_THEMEMODES => GD_TEMPLATE_CONTENT_WEBSITEFEATURES_THEMEMODES,
			GD_TEMPLATE_BLOCK_WEBSITEFEATURES_ADDITIONALS => GD_TEMPLATE_CONTENT_WEBSITEFEATURES_ADDITIONALS,
			GD_TEMPLATE_BLOCK_WEBSITEFEATURES_UNDERTHEHOOD => GD_TEMPLATE_CONTENT_WEBSITEFEATURES_UNDERTHEHOOD,
			GD_TEMPLATE_BLOCK_WEBSITEFEATURES_IDEALFORIMPLEMENTING => GD_TEMPLATE_CONTENT_WEBSITEFEATURES_IDEALFORIMPLEMENTING,
			GD_TEMPLATE_BLOCK_WEBSITEFEATURES_TODOS => GD_TEMPLATE_CONTENT_WEBSITEFEATURES_TODOS,
			GD_TEMPLATE_BLOCK_CONTACTABOUTUS => GD_TEMPLATE_POSTCODE_CONTACTABOUTUS,
		);
		if ($inner = $inners[$template_id]) {
			$ret[] = $inner;
		}

		return $ret;
	}

	function get_title($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK:

				return __('The framework', 'getpop-processors');

			case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_PAGECONTAINERS:

				return __('Different page containers', 'getpop-processors');

			case GD_TEMPLATE_BLOCK_DESIGNPRINCIPLES_API:
			case GD_TEMPLATE_BLOCK_DESIGNPRINCIPLES_DESCENTRALIZATION:
			// case GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK_GETPOST:
			// case GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK_MICROSERVICES:

				return '';

			// case GD_TEMPLATE_BLOCK_DESIGNPRINCIPLES_API:

			// 	return __('The website is an API', 'getpop-processors');

			// case GD_TEMPLATE_BLOCK_DESIGNPRINCIPLES_DESCENTRALIZATION:

			// 	return __('Decentralized', 'getpop-processors');

			// case GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK_GETPOST:

			// 	return __('Both GET and POST operations are supported', 'getpop-processors');

			// case GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK_MICROSERVICES:

			// 	return __('Microservices', 'getpop-processors');

			case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_THEMEMODES:

				return __('Multiple presentation styles', 'getpop-processors');

			case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_ADDITIONALS:

				return __('More features', 'getpop-processors');

			case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_UNDERTHEHOOD:

				return __('Under the hood', 'getpop-processors');

			case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_IDEALFORIMPLEMENTING:

				return __('PoP is ideal for implementing...', 'getpop-processors');

			case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_TODOS:

				return __('TODOs', 'getpop-processors');
		}
		
		return parent::get_title($template_id);
	}

	protected function get_title_htmltag($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK:
			case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_PAGECONTAINERS:
			case GD_TEMPLATE_BLOCK_DESIGNPRINCIPLES_API:
			case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_THEMEMODES:
			case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_ADDITIONALS:
			// case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_UNDERTHEHOOD:
			case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_IDEALFORIMPLEMENTING:
			case GD_TEMPLATE_BLOCK_WEBSITEFEATURES_TODOS:

				return 'h2';
		}
		
		return parent::get_title_htmltag($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_Template_Processor_CustomSectionBlocks();