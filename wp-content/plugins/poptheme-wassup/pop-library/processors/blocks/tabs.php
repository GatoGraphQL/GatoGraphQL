<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_PAGETABS_HOME', PoP_TemplateIDUtils::get_template_definition('block-tabs-home'));
define ('GD_TEMPLATE_BLOCK_PAGETABS_TAG', PoP_TemplateIDUtils::get_template_definition('block-tabs-tag'));
define ('GD_TEMPLATE_BLOCK_PAGETABS_PAGE', PoP_TemplateIDUtils::get_template_definition('block-tabs-page'));
define ('GD_TEMPLATE_BLOCK_PAGETABS_SINGLE', PoP_TemplateIDUtils::get_template_definition('block-tabs-single'));
define ('GD_TEMPLATE_BLOCK_PAGETABS_AUTHOR', PoP_TemplateIDUtils::get_template_definition('block-tabs-author'));
define ('GD_TEMPLATE_BLOCK_PAGETABS_404', PoP_TemplateIDUtils::get_template_definition('block-tabs-404'));

define ('GD_TEMPLATE_BLOCK_PAGETABS_WEBPOSTLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('block-pagetabs-webpostlink-create'));
define ('GD_TEMPLATE_BLOCK_PAGETABS_HIGHLIGHT_CREATE', PoP_TemplateIDUtils::get_template_definition('block-pagetabs-highlight-create'));
define ('GD_TEMPLATE_BLOCK_PAGETABS_WEBPOST_CREATE', PoP_TemplateIDUtils::get_template_definition('block-pagetabs-webpost-create'));

define ('GD_TEMPLATE_BLOCK_ADDONTABS_ADDCOMMENT', PoP_TemplateIDUtils::get_template_definition('block-addontabs-addcomment'));
define ('GD_TEMPLATE_BLOCK_ADDONTABS_CONTACTUSER', PoP_TemplateIDUtils::get_template_definition('block-addontabs-contactuser'));
define ('GD_TEMPLATE_BLOCK_ADDONTABS_VOLUNTEER', PoP_TemplateIDUtils::get_template_definition('block-addontabs-volunteer'));
define ('GD_TEMPLATE_BLOCK_ADDONTABS_FLAG', PoP_TemplateIDUtils::get_template_definition('block-addontabs-flag'));

class GD_Template_Processor_CustomTabBlocks extends GD_Template_Processor_TabBlocksBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_PAGETABS_HOME,
			GD_TEMPLATE_BLOCK_PAGETABS_TAG,
			GD_TEMPLATE_BLOCK_PAGETABS_PAGE,
			GD_TEMPLATE_BLOCK_PAGETABS_SINGLE,
			GD_TEMPLATE_BLOCK_PAGETABS_AUTHOR,
			GD_TEMPLATE_BLOCK_PAGETABS_404,

			GD_TEMPLATE_BLOCK_PAGETABS_WEBPOSTLINK_CREATE,
			GD_TEMPLATE_BLOCK_PAGETABS_HIGHLIGHT_CREATE,
			GD_TEMPLATE_BLOCK_PAGETABS_WEBPOST_CREATE,

			GD_TEMPLATE_BLOCK_ADDONTABS_ADDCOMMENT,
			GD_TEMPLATE_BLOCK_ADDONTABS_CONTACTUSER,
			GD_TEMPLATE_BLOCK_ADDONTABS_VOLUNTEER,
			GD_TEMPLATE_BLOCK_ADDONTABS_FLAG,
		);
	}

	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_ADDONTABS_ADDCOMMENT:
			case GD_TEMPLATE_BLOCK_ADDONTABS_CONTACTUSER:
			case GD_TEMPLATE_BLOCK_ADDONTABS_VOLUNTEER:
			case GD_TEMPLATE_BLOCK_ADDONTABS_FLAG:
			case GD_TEMPLATE_BLOCK_PAGETABS_WEBPOSTLINK_CREATE:
			case GD_TEMPLATE_BLOCK_PAGETABS_HIGHLIGHT_CREATE:
			case GD_TEMPLATE_BLOCK_PAGETABS_WEBPOST_CREATE:

				$pages = array(
					GD_TEMPLATE_BLOCK_ADDONTABS_ADDCOMMENT => POP_WPAPI_PAGE_ADDCOMMENT,
					GD_TEMPLATE_BLOCK_ADDONTABS_CONTACTUSER => POP_GENERICFORMS_PAGE_CONTACTUSER,
					GD_TEMPLATE_BLOCK_ADDONTABS_VOLUNTEER => POP_GENERICFORMS_PAGE_VOLUNTEER,
					GD_TEMPLATE_BLOCK_ADDONTABS_FLAG => POP_GENERICFORMS_PAGE_FLAG,
					GD_TEMPLATE_BLOCK_PAGETABS_WEBPOSTLINK_CREATE => POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK,
					GD_TEMPLATE_BLOCK_PAGETABS_HIGHLIGHT_CREATE => POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT,
					GD_TEMPLATE_BLOCK_PAGETABS_WEBPOST_CREATE => POPTHEME_WASSUP_PAGE_ADDWEBPOST,
				);
				return get_the_title($pages[$template_id]);
		}
		
		return parent::get_title($template_id);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_PAGETABS_HOME:
			
				return GD_DATALOAD_IOHANDLER_TABS_HOME;

			case GD_TEMPLATE_BLOCK_PAGETABS_TAG:
			
				return GD_DATALOAD_IOHANDLER_TABS_TAG;
			
			case GD_TEMPLATE_BLOCK_PAGETABS_PAGE:
			
				return GD_DATALOAD_IOHANDLER_TABS_PAGE;
			
			case GD_TEMPLATE_BLOCK_PAGETABS_SINGLE:
			
				return GD_DATALOAD_IOHANDLER_TABS_SINGLE;
			
			case GD_TEMPLATE_BLOCK_PAGETABS_AUTHOR:
				
				return GD_DATALOAD_IOHANDLER_TABS_AUTHOR;
			
			case GD_TEMPLATE_BLOCK_PAGETABS_404:

				return GD_DATALOAD_IOHANDLER_TABS_404;

			default:

				$iohandlers = array(
					GD_TEMPLATE_BLOCK_ADDONTABS_ADDCOMMENT => GD_DATALOAD_IOHANDLER_TABS_ADDON_ADDCOMMENT,
					GD_TEMPLATE_BLOCK_ADDONTABS_CONTACTUSER => GD_DATALOAD_IOHANDLER_TABS_ADDON_CONTACTUSER,
					GD_TEMPLATE_BLOCK_ADDONTABS_VOLUNTEER => GD_DATALOAD_IOHANDLER_TABS_ADDON_VOLUNTEER,
					GD_TEMPLATE_BLOCK_ADDONTABS_FLAG => GD_DATALOAD_IOHANDLER_TABS_ADDON_FLAG,
					GD_TEMPLATE_BLOCK_PAGETABS_WEBPOSTLINK_CREATE => GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDWEBPOSTLINK,
					GD_TEMPLATE_BLOCK_PAGETABS_WEBPOST_CREATE => GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDWEBPOST,
					GD_TEMPLATE_BLOCK_PAGETABS_HIGHLIGHT_CREATE => GD_DATALOAD_IOHANDLER_TABS_PAGE_ADDHIGHLIGHT,
				);
				if ($iohandler = $iohandlers[$template_id]) {
					return $iohandler;
				}
				break;
		}
		
		return parent::get_iohandler($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomTabBlocks();