<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MULTIPLEUSER_POPOVER', PoP_TemplateIDUtils::get_template_definition('layout-multipleuser-popover'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEUSER_POSTAUTHOR', PoP_TemplateIDUtils::get_template_definition('layout-multipleuser-postauthor'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR', PoP_TemplateIDUtils::get_template_definition('layout-multipleuser-contextualpostauthor'));

define ('GD_TEMPLATE_LAYOUT_MULTIPLEUSER_NAVIGATOR', PoP_TemplateIDUtils::get_template_definition('layout-multipleuser-navigator'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEUSER_ADDONS', PoP_TemplateIDUtils::get_template_definition('layout-multipleuser-addons'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEUSER_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-multipleuser-details'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEUSER_THUMBNAIL', PoP_TemplateIDUtils::get_template_definition('layout-multipleuser-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEUSER_LIST', PoP_TemplateIDUtils::get_template_definition('layout-multipleuser-list'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEUSER_FULLUSER', PoP_TemplateIDUtils::get_template_definition('layout-multipleuser-fulluser'));
define ('GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-authormultipleuser-details'));
define ('GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_THUMBNAIL', PoP_TemplateIDUtils::get_template_definition('layout-authormultipleuser-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_LIST', PoP_TemplateIDUtils::get_template_definition('layout-authormultipleuser-list'));
define ('GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_FULLUSER', PoP_TemplateIDUtils::get_template_definition('layout-authormultipleuser-fulluser'));
define ('GD_TEMPLATE_LAYOUT_SINGLEMULTIPLEUSER_FULLUSER', PoP_TemplateIDUtils::get_template_definition('layout-singlemultipleuser-fulluser'));

class GD_Template_Processor_MultipleUserLayouts extends GD_Template_Processor_MultipleUserLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MULTIPLEUSER_POPOVER,
			GD_TEMPLATE_LAYOUT_MULTIPLEUSER_POSTAUTHOR,
			GD_TEMPLATE_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR,

			GD_TEMPLATE_LAYOUT_MULTIPLEUSER_NAVIGATOR,
			GD_TEMPLATE_LAYOUT_MULTIPLEUSER_ADDONS,
			GD_TEMPLATE_LAYOUT_MULTIPLEUSER_DETAILS,
			GD_TEMPLATE_LAYOUT_MULTIPLEUSER_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_MULTIPLEUSER_LIST,
			GD_TEMPLATE_LAYOUT_MULTIPLEUSER_FULLUSER,
			GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_DETAILS,
			GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_LIST,
			GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_FULLUSER,
			GD_TEMPLATE_LAYOUT_SINGLEMULTIPLEUSER_FULLUSER,
		);
	}

	function get_default_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_POPOVER:
			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_POSTAUTHOR:
			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_ADDONS:
			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_DETAILS:
			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_LIST:
			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_FULLUSER:
			case GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_LIST:
			case GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_FULLUSER:
			case GD_TEMPLATE_LAYOUT_SINGLEMULTIPLEUSER_FULLUSER:

				return GD_TEMPLATE_LAYOUT_PREVIEWUSER_SUBSCRIBER;

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR:

				return GD_TEMPLATE_LAYOUT_POPOVER_USER;
		}

		return parent::get_default_layout($template_id);
	}

	function get_multiple_layouts($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_POPOVER:

				return apply_filters(
					'GD_Template_Processor_MultipleUserLayouts:layouts:popover',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_POSTAUTHOR:

				return apply_filters(
					'GD_Template_Processor_MultipleUserLayouts:layouts:postauthor',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR:

				return apply_filters(
					'GD_Template_Processor_MultipleUserLayouts:layouts:contextualpostauthor',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_NAVIGATOR:

				return apply_filters(
					'GD_Template_Processor_MultipleUserLayouts:layouts:navigator',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_ADDONS:

				return apply_filters(
					'GD_Template_Processor_MultipleUserLayouts:layouts:addons',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_DETAILS:

				return apply_filters(
					'GD_Template_Processor_MultipleUserLayouts:layouts:details',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_THUMBNAIL:

				return apply_filters(
					'GD_Template_Processor_MultipleUserLayouts:layouts:thumbnail',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_LIST:

				return apply_filters(
					'GD_Template_Processor_MultipleUserLayouts:layouts:list',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEUSER_FULLUSER:

				return apply_filters(
					'GD_Template_Processor_MultipleUserLayouts:layouts:fulluser',
					array()
				);

			case GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_DETAILS:

				return apply_filters(
					'GD_Template_Processor_MultipleUserLayouts:layouts:authordetails',
					array()
				);

			case GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_THUMBNAIL:

				return apply_filters(
					'GD_Template_Processor_MultipleUserLayouts:layouts:authorthumbnail',
					array()
				);

			case GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_LIST:

				return apply_filters(
					'GD_Template_Processor_MultipleUserLayouts:layouts:authorlist',
					array()
				);

			case GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEUSER_FULLUSER:

				return apply_filters(
					'GD_Template_Processor_MultipleUserLayouts:layouts:authorfulluser',
					array()
				);

			case GD_TEMPLATE_LAYOUT_SINGLEMULTIPLEUSER_FULLUSER:

				return apply_filters(
					'GD_Template_Processor_MultipleUserLayouts:layouts:singlefulluser',
					array()
				);
		}

		return parent::get_multiple_layouts($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MultipleUserLayouts();