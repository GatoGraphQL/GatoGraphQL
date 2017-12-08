<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_EVERYTHING_QUICKLINKS', PoP_TemplateIDUtils::get_template_definition('block-everything-quicklinks'));

class GD_Template_Processor_QuicklinksBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_EVERYTHING_QUICKLINKS
		);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_EVERYTHING_QUICKLINKS:

				$ret[] = GD_TEMPLATE_FORM_EVERYTHINGQUICKLINKS;
				// $ret[] = GD_TEMPLATE_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING;
				break;
		}
	
		return $ret;
	}

	function get_dataloader($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_EVERYTHING_QUICKLINKS:

				return GD_DATALOADER_STATIC;
		}

		return parent::get_dataloader($template_id);
	}

	function get_iohandler($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_EVERYTHING_QUICKLINKS:

				return GD_DATALOAD_IOHANDLER_FORM;
		}

		return parent::get_iohandler($template_id);
	}

	function get_dataload_source($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_EVERYTHING_QUICKLINKS:

				// Dataload source: search posts (it will never every trigger to fetch this url, it is just a placeholder
				// to trigger using js, eg: typeaheadSearch)
				global $gd_filter_manager, $gd_filtercomponent_search;
				$gd_filter_wildcardposts = $gd_filter_manager->get_filter(GD_FILTER_WILDCARDPOSTS);

				$searchcontent_url = get_permalink(POP_WPAPI_PAGE_SEARCHPOSTS);
				$filter_params = array();
				// Comment Leo 08/12/2017: add the *QUERY* search param only if JS is disabled
				if (!PoP_Frontend_ServerUtils::disable_js()) {
					
					$filter_params = array(
						$gd_filtercomponent_search->get_name() => GD_JSPLACEHOLDER_QUERY/*'%QUERY'*/
					);
				}
				$searchcontent_url = $gd_filter_manager->add_filter_params($searchcontent_url, $gd_filter_wildcardposts, $filter_params);

				return $searchcontent_url;
		}

		return parent::get_dataload_source($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_QuicklinksBlocks();