<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Processor_BlocksBase extends PoP_ProcessorBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	function get_filter_template($template_id) {

		return null;
	}

	function integrate_execution_bag($template_id, $atts, &$data_settings, $execution_bag) {	

		// Do nothing by default (function to overrided)
	}

	function get_query_url($template_id, $atts) {		
		
		return $this->get_dataload_source($template_id, $atts);

		// $url = $this->get_dataload_source($template_id, $atts);

		// if ($proxy_domain = $this->get_dataloadsource_domain($template_id, $atts)) {

		// 	$url = str_replace(get_site_url(), $proxy_domain, $url);
		// }

		// return $url;
	}

	function get_query_multidomain_urls($template_id, $atts) {		
		
		return $this->get_dataload_multidomain_sources($template_id, $atts);
	}

	function is_blockgroup($template_id) {

		return false;
	}

	// function get_dataloadsource_domain($template_id, $atts) {

	// 	if ($proxy_domain = $this->get_att($template_id, $atts, 'dataloadsource-domain')) {

	// 		return $proxy_domain;
	// 	}

	// 	return null;
	// }

	function get_dataload_source($template_id, $atts) {

		if ($page = $this->get_block_page($template_id)) {

			return get_permalink($page);
		}
	
		return null;
	}

	function get_dataload_multidomain_sources($template_id, $atts) {

		if ($domains = $this->get_att($template_id, $atts, 'dataload-multidomain-sources')) {

			return $domains;
		}
	
		// By default, return one element, that being the dataload-source itself
		if ($dataload_source = $this->get_dataload_source($template_id, $atts)) {
			
			return array(
				$dataload_source,
			);
		}

		return array();
	}

	function queries_external_domain($template_id, $atts) {

		if ($sources = $this->get_dataload_multidomain_sources($template_id, $atts)) {
			
			$domain = get_site_url();
			foreach ($sources as $source) {

				if (substr($source, 0, strlen($domain)) != $domain) {

					return true;
				}
			}
		}

		return false;
	}
	
	//-------------------------------------------------
	// PUBLIC Overriding Functions
	//-------------------------------------------------

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		if ($block_inners = $this->get_block_inner_templates($template_id)) {
			
			$ret = array_merge(
				$ret,
				$block_inners
			);
		}

		if ($messagefeedback = $this->get_messagefeedback($template_id)) {				
			$ret[] = $messagefeedback;
		}
				
		return $ret;
	}

	function get_data_setting($template_id, $atts) {

		$ret = parent::get_data_setting($template_id, $atts);
	
		if ($dataloader = $this->get_dataloader($template_id)) {

			$ret['dataloader'] = $dataloader;
		}

		$dataload_atts = $this->get_dataload_query_args($template_id, $atts);
		$ret['dataload-atts'] = $dataload_atts;
	
		// validate-checkpoints will actually return the validation type (static/datafromserver), convert it to boolean
		if ($checkpointvalidation_type = $this->get_checkpointvalidation_type($template_id)) {
			$ret[GD_DATALOAD_VALIDATECHECKPOINTS] = $checkpointvalidation_type;
			$ret['iohandler-atts']['validate-checkpoints'] = true;
		}
	
		$ret[GD_DATALOAD_LOAD] = $this->get_att($template_id, $atts, 'data-load');

		// Do not load Lazy or PoP Blocks or Search (initially)
		$ret[GD_DATALOAD_CONTENTLOADED] = $this->get_att($template_id, $atts, 'content-loaded');
		$ret[GD_DATALOAD_VALIDATECONTENTLOADED] = $this->get_att($template_id, $atts, 'validate-content-loaded');

		// if ($dataload_source = $this->get_dataload_source($template_id, $atts)) {
		if ($dataload_source = $this->get_query_url($template_id, $atts)) {
			$ret['dataload-source'] = $dataload_source;
		}

		if ($iohandler = $this->get_iohandler($template_id)) {

			$ret['iohandler'] = $iohandler;
		}

		if ($actionexecuter = $this->get_actionexecuter($template_id)) {

			$ret['actionexecuter'] = $actionexecuter;
		}
		
		return $ret;
	}

	function get_runtime_datasetting($template_id, $atts) {

		$ret = parent::get_runtime_datasetting($template_id, $atts);
	
		if ($dataload_atts = $this->get_runtime_dataload_query_args($template_id, $atts)) {
			$ret['dataload-atts'] = $dataload_atts;
		}

		return $ret;
	}

	function get_database_key($template_id, $atts) {

		$ret = parent::get_database_key($template_id, $atts);

		if ($dataloader_name = $this->get_dataloader($template_id)) {
			
			global $gd_dataload_manager;
			$dataloader = $gd_dataload_manager->get($dataloader_name);

			$ret['db-key'] = $dataloader->get_database_key();
		}

		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		$ret[GD_JS_SETTINGSID/*'settings-id'*/] = $this->get_settings_id($template_id);

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$vars = GD_TemplateManager_Utils::get_vars();

		if (GD_DATALOADER_STATIC == $this->get_dataloader($template_id)) {
			$this->add_att($template_id, $atts, 'data-load', false);
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Aggregator / Lazy Load / Content Loaded
		 * ---------------------------------------------------------------------------------------------------------------*/

		// Allow is_proxy and is_aggregator to set 'content-loaded' before setting the default value
		// if ($this->get_dataloadsource_domain($template_id, $atts)) {

		// 	// If proxy => Content not loaded
		// 	$this->add_att($template_id, $atts, 'content-loaded', false);
		// }
		if ($this->queries_external_domain($template_id, $atts)) {

			// If proxy => Content not loaded
			$this->add_att($template_id, $atts, 'content-loaded', false);
		}

		// If it is multidomain, add a flag for inner layouts to know and react
		$multidomain_urls = $this->get_query_multidomain_urls($template_id, $atts);
		if (is_array($multidomain_urls) && count($multidomain_urls) >= 2) {
		
			$this->add_general_att($atts, 'is-multidomain', true);
			$this->append_att($template_id, $atts, 'class', 'pop-multidomain');
		}

		// Content Loaded?
		// If data-load = false, make content loaded always true. This means, since the block does not need to load content,
		// then its no content is always already loaded. Needed for Search Blockgroups, so that it can still load only the current
		// active block and not all of them
		// If doing fetching-json-data, then content-loaded = false makes no sense, then force it to true
		$this->add_att($template_id, $atts, 'data-load', true);
		$data_load = $this->get_att($template_id, $atts, 'data-load');
		if ($data_load === false || $vars['fetching-json-data']) {
			$this->force_att($template_id, $atts, 'content-loaded', true);
		}
		else {
			// Load data by default
			$this->add_att($template_id, $atts, 'content-loaded', true);
		}

		// Always validate if content-loaded by default
		// This can be overridden by the blocks-base in baseprocessors
		$this->add_att($template_id, $atts, 'validate-content-loaded', true);
				
		return parent::init_atts($template_id, $atts);
	}

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	protected function get_checkpointvalidation_type($template_id) {

		if ($page = $this->get_block_page($template_id)) {

			global $gd_template_settingsmanager;
			$checkpoint_settings = $gd_template_settingsmanager->get_page_checkpoints($page);
			$type = $checkpoint_settings['type'];

			// Important: do NOT add GD_DATALOAD_NOCHECKPOINTVALIDATION_TYPE_DATAFROMSERVER, since this never needs checkpoint validation
			if ($type == GD_DATALOAD_NOCHECKPOINTVALIDATION_TYPE_DATAFROMSERVER) {
				return null;
			}
			if ((doing_post() && $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC) || $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER || $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATELESS) {

				return $type;
			}
		}

		return null;
	}
	
	protected function get_block_inner_templates($template_id) {

		return array();
	}
	function get_dataloader($template_id) {

		return GD_DATALOADER_STATIC;
	}
	protected function get_actionexecuter($template_id) {

		return null;
	}
	protected function get_dataload_query_args($template_id, $atts) {

		return array();
	}
	protected function get_runtime_dataload_query_args($template_id, $atts) {

		return array();
	}
	protected function get_iohandler($template_id) {
	
		// By default, use the bare Block IOHandler
		return GD_DATALOAD_IOHANDLER_BLOCK;
	}
	protected function get_messagefeedback($template_id) {

		return null;
	}
	protected function get_block_page($template_id) {

		global $gd_template_settingsmanager;

		if ($page = $gd_template_settingsmanager->get_block_page($template_id, $this->get_block_hierarchy($template_id))) {

			return $page;
		}
		return null;
	}
	protected function get_block_hierarchy($template_id) {

		// Consider a page as the default
		return GD_SETTINGS_HIERARCHY_PAGE;
	}
}
