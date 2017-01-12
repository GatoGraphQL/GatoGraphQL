<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_HOOK_BLOCKSBASE_FILTERINGBYSHOWFILTER', 'hook-blocksbase-filteringbyshowfilter');

class PoPFrontend_Processor_BlocksBase extends GD_Template_ProcessorBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_filter_template($template_id) {

		return null;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function integrate_execution_bag($template_id, $atts, &$data_settings, $execution_bag) {	

		// Do nothing by default (function to overrided)
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_query_url($template_id, $atts) {		
		
		$url = $this->get_dataload_source($template_id, $atts);

		if ($proxy_domain = $this->get_dataloadsource_domain($template_id, $atts)) {

			$url = str_replace(get_site_url(), $proxy_domain, $url);
		}

		return $url;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function is_blockgroup($template_id) {

		return false;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_dataloadsource_domain($template_id, $atts) {

		if ($proxy_domain = $this->get_att($template_id, $atts, 'dataloadsource-domain')) {

			return $proxy_domain;
		}

		return null;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_dataload_source($template_id, $atts) {

		if ($page = $this->get_block_page($template_id)) {

			return get_permalink($page);
		}
	
		return null;
	}

	function get_title($template_id) {

		if ($page = $this->get_block_page($template_id)) {

			return get_the_title($page);
		}
		return null;
	}

	protected function get_block_title($template_id, $atts) {

		// If the title has been set in the $atts by a parent, use it
		// Otherwise, use the local template level. This bizarre solution is used, instead of directly
		// overriding the value of 'title' in the $atts, since the title is dynamic (eg: get_permalink($page))
		// however it is saved in the static cache. So then the assumption is that, if the title is set
		// from above, then it shall be static, otherwise this same level can be runtime
		$title = $this->get_att($template_id, $atts, 'title');
		if (!is_null($title)) { // $title = '' is valid

			return $title;
		}
		return $this->get_title($template_id);
	}

	
	//-------------------------------------------------
	// PUBLIC Overriding Functions
	//-------------------------------------------------

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		/***********************************************************/
		/** Repeated from "parent" class! */
		/***********************************************************/
		if ($block_inners = $this->get_block_inner_templates($template_id)) {
			
			$ret = array_merge(
				$ret,
				$block_inners
			);
		}

		if ($messagefeedback = $this->get_messagefeedback($template_id)) {				
			$ret[] = $messagefeedback;
		}
		/***********************************************************/

		
		if ($show_status = $this->show_status($template_id)) {
			$ret[] = GD_TEMPLATE_STATUS;
		}

		if ($filter = $this->get_filter_template($template_id)) {
			$ret[] = $filter;
		}

		$ret[] = GD_TEMPLATE_FETCHMORE;
				
		return $ret;
	}

	function get_data_setting($template_id, $atts) {

		$ret = parent::get_data_setting($template_id, $atts);
	
		/***********************************************************/
		/** Repeated from "parent" class! */
		/***********************************************************/
		if ($dataloader = $this->get_dataloader($template_id)) {

			$ret['dataloader'] = $dataloader;
		}

		$dataload_atts = $this->get_dataload_query_args($template_id, $atts);
		$ret['dataload-atts'] = $dataload_atts;

		// Limit set?
		// if ($limit = $dataload_atts['limit']) {
			
		// 	$ret['iohandler-atts'][GD_URLPARAM_LIMIT] = $limit;
		// }

		// validate-checkpoints will actually return the validation type (static/datafromserver), convert it to boolean
		if ($checkpointvalidation_type = $this->get_checkpointvalidation_type($template_id)) {
			$ret[GD_DATALOAD_VALIDATECHECKPOINTS] = $checkpointvalidation_type;
			$ret['iohandler-atts']['validate-checkpoints'] = true;
		}
	
		$ret[GD_DATALOAD_LOAD] = $this->get_att($template_id, $atts, 'data-load');

		// Do not load Lazy or PoP Blocks or Search (initially)
		$ret[GD_DATALOAD_CONTENTLOADED] = $this->get_att($template_id, $atts, 'content-loaded');

		if ($dataload_source = $this->get_dataload_source($template_id, $atts)) {
			$ret['dataload-source'] = $dataload_source;
		}

		if ($iohandler = $this->get_iohandler($template_id)) {

			$ret['iohandler'] = $iohandler;
		}

		if ($actionexecuter = $this->get_actionexecuter($template_id)) {

			$ret['actionexecuter'] = $actionexecuter;
		}
		/***********************************************************/

		// IOHandler atts
		$ret['iohandler-atts'][GD_URLPARAM_HIDDENIFEMPTY] = $this->get_att($template_id, $atts, 'hidden-if-empty');

		// Comment Leo 12/01/2017: moved to function get_runtime_datasetting($template_id, $atts) below
		// if ($title = $this->get_att($template_id, $atts, 'title')) {
			
		// 	$ret['iohandler-atts']['title'] = $title;

		// 	if ($this->get_att($template_id, $atts, 'add-titlelink')) {

		// 		if ($title_link = $this->get_title_link($template_id)) { 

		// 			$ret['iohandler-atts']['title-link'] = $title_link;
		// 		}
		// 	}
		// }
		
		return $ret;
	}

	function get_runtime_datasetting($template_id, $atts) {

		$ret = parent::get_runtime_datasetting($template_id, $atts);

		/***********************************************************/
		/** Repeated from "parent" class! */
		/***********************************************************/
		if ($dataload_atts = $this->get_runtime_dataload_query_args($template_id, $atts)) {
			$ret['dataload-atts'] = $dataload_atts;
		}
	
		// Comment Leo 12/01/2017: This should ideally belong under a function `init_runtime_atts`, since
		// the title can depend on the page (eg: get_permalink($page)). This was not done, under the assumption that,
		// when setting the title from above, it will be static (eg: '', or 'Calendar', but never get_permalink($page))
		// In any case, we can't use the $atts to set the title on this same template level, so change the logic
		// in function get_title
		// if ($title = $this->get_att($template_id, $atts, 'title')) {
		if ($title = $this->get_block_title($template_id, $atts)) {
			
			$ret['iohandler-atts']['title'] = $title;

			if ($this->get_att($template_id, $atts, 'add-titlelink')) {

				if ($title_link = $this->get_title_link($template_id)) { 

					$ret['iohandler-atts']['title-link'] = $title_link;
				}
			}
		}
		
		return $ret;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_database_key($template_id, $atts) {

		$ret = parent::get_database_key($template_id, $atts);

		if ($dataloader_name = $this->get_dataloader($template_id)) {
			
			global $gd_dataload_manager;
			$dataloader = $gd_dataload_manager->get($dataloader_name);

			$ret['db-key'] = $dataloader->get_database_key();
		}

		return $ret;
	}

	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		if ($branches = $this->get_att($template_id, $atts, 'initjs-blockbranches')) {
			$ret['initjs-blockbranches'] = $branches;
		}
		if ($children = $this->get_att($template_id, $atts, 'initjs-blockchildren')) {
			$ret['initjs-blockchildren'] = $children;
		}

		return $ret;
	}

	// function get_js_setting_key($template_id, $atts) {

	// 	return $this->get_settings_id($template_id);
	// }

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		/***********************************************************/
		/** Repeated from "parent" class! */
		/***********************************************************/

		$ret[GD_JS_SETTINGSID/*'settings-id'*/] = $this->get_settings_id($template_id);

		/***********************************************************/


		global $gd_template_processor_manager;

		if ($this->get_att($template_id, $atts, 'show-filter')) {
			$ret['show-filter'] = true;
		}

		if ($this->show_disabled_layer($template_id)) {
			$ret['show-disabled-layer'] = true;
			$ret[GD_JS_TITLES/*'titles'*/]['loading'] = GD_CONSTANT_LOADING_SPINNER.' '.GD_CONSTANT_LOADING_MSG;
		}
		
		if ($block_inners = $this->get_block_inner_templates($template_id)) {
			
			$block_inner_settings_ids = array();
			foreach ($block_inners as $block_inner) {
				$block_inner_settings_ids[] = $gd_template_processor_manager->get_processor($block_inner)->get_settings_id($block_inner);
			}
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['block-inners'] = $block_inner_settings_ids;
		}
		if ($block_extensions = $this->get_block_extension_templates($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['block-extensions'] = $block_extensions;
		}
		if ($show_status = $this->show_status($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['status'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_STATUS)->get_settings_id(GD_TEMPLATE_STATUS);
		}
		if ($filter = $this->get_filter_template($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['filter'] = $gd_template_processor_manager->get_processor($filter)->get_settings_id($filter);
		}
		if ($fetchmore = $this->get_att($template_id, $atts, 'show-fetchmore')) {
			
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['fetchmore'] = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FETCHMORE)->get_settings_id(GD_TEMPLATE_FETCHMORE);
		}
		
		if ($messagefeedback = $this->get_messagefeedback($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['messagefeedback'] = $gd_template_processor_manager->get_processor($messagefeedback)->get_settings_id($messagefeedback);

			$messagefeedback_pos = $this->get_messagefeedback_position($template_id);
			if ($messagefeedback_pos == 'top') {

				$ret['messagefeedback-top'] = true;
			}
			elseif ($messagefeedback_pos == 'bottom') {

				$ret['messagefeedback-bottom'] = true;
			}					
		}

		if ($classes = $this->get_blocksections_classes($template_id)) {

			$ret[GD_JS_CLASSES/*'classes'*/] = $classes;
		}

		// This still needs to be printed for the replicable elements, eg: Login inside of hover offcanvas
		// This is because we can't access bs.feedback.title inside of pagesection-tabpane-source.tmpl for each of the contained blocks
		if ($title = $this->get_att($template_id, $atts, 'title')) {
			$ret['title'] = $title;
		}
				
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		$this->append_att($template_id, $atts, 'class', 'pop-block');

		/***********************************************************/
		/** Repeated from "parent" class! */
		/***********************************************************/
		$vars = GD_TemplateManager_Utils::get_vars();
		
		if (GD_DATALOADER_STATIC == $this->get_dataloader($template_id)) {
			$this->add_att($template_id, $atts, 'data-load', false);
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Aggregator / Lazy Load / Content Loaded
		 * ---------------------------------------------------------------------------------------------------------------*/

		// Allow is_proxy and is_aggregator to set 'content-loaded' before setting the default value
		if ($this->get_dataloadsource_domain($template_id, $atts)) {

			// If proxy => Content not loaded
			$this->add_att($template_id, $atts, 'content-loaded', false);
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
		/***********************************************************/


		// Allow is_proxy and is_aggregator to set 'content-loaded' before setting the default value
		if ($this->get_dataloadsource_domain($template_id, $atts)) {

			$this->append_att($template_id, $atts, 'class', 'template-proxy');

			// Always hidden
			$this->add_att($template_id, $atts, 'hidden', true);
		}

		// Add the settings_id as a block class
		$this->append_att($template_id, $atts, 'class', $this->get_settings_id($template_id));
		
		$this->add_att($template_id, $atts, 'show-filter', true);
		// $this->add_att($template_id, $atts, 'show-controls', true);

		// Comment Leo 12/01/2017: This should ideally belong under a function `init_runtime_atts`, since
		// the title can depend on the page (eg: get_permalink($page)). This was not done, under the assumption that,
		// when setting the title from above, it will be static (eg: '', or 'Calendar', but never get_permalink($page))
		// In any case, we can't use the $atts to set the title on this same template level, so change the logic
		// in function get_title
		// if ($title = $this->get_title($template_id)) {

		// 	$this->add_att($template_id, $atts, 'title', $title);
		// }
		
		// Initialize leaves and branches to init JS
		$this->add_att($template_id, $atts, 'initjs-blockbranches', array());
				
		if ($filter = $this->get_filter_template($template_id)) {

			$filter_object = $gd_template_processor_manager->get_processor($filter)->get_filter_object($filter);

			// Class needed for the proxyForm's selector when proxying this one block
			$this->append_att($template_id, $atts, 'class', 'withfilter');

			// Class needed for the proxyForm's selector when proxying this one form
			$class = 'pop-blockfilter';
			$class .= ' collapse alert alert-info form-horizontal';
			$runtimeclass = '';

			// Filter visible: if explicitly defined, or if currently filtering with it
			// Filter hidden: always hide it, eg: for Full Post
			if ($show_filter = $this->get_att($template_id, $atts, 'show-filter')) {
				
				$filter_visible = $this->get_att($template_id, $atts, 'filter-visible');
				$filter_hidden = $this->get_att($template_id, $atts, 'filter-hidden');

				global $gd_filter_manager;
				// Comment Leo 31/10/2014: don't show the filter open when filtering by anymore for MESYM v4,
				// it takes so much space specially in the embed, and in some case, eg:
				// http://m3l.localhost/calendar/?calendaryear=2014&calendarmonth=7&searchfor&filter=events-calendar
				// it doesn't even show any param being filtered (month or year not chosen in filter)
				// Comment Leo 15/04/2015: do not show the filter even if filtering for EMBED and PRINT
				// $theme = GD_TemplateManager_Utils::get_theme();
				// if ($theme->filteringby_showfilter()) {
				if (apply_filters(POP_HOOK_BLOCKSBASE_FILTERINGBYSHOWFILTER, true)) {
					
					if ($filter_visible || $gd_filter_manager->filteringby($filter_object)) {

						// Filter will be open depending on URL params, so make this class a runtime one
						$runtimeclass .= ' in';
					}
				}
				if ($filter_hidden) {
					$class .= ' hidden';
				}

				$this->append_att($filter, $atts, 'class', $class);
				$this->append_att($filter, $atts, 'runtime-class', $runtimeclass);

				// Comment Leo 07/07/2016: Commented below since allowing the BlockGroup to override att 'form-type' in the filter, with value GD_SUBMITFORMTYPE_FILTERBLOCKGROUP
				// // Set the jsmethod for the filter (can be overriden by BlockGroupBase)
				// $this->add_att($template_id, $atts, 'filter-jsmethod', 'initBlockFilter');
				// $this->merge_block_jsmethod_att($filter, $atts, array($this->get_att($template_id, $atts, 'filter-jsmethod')));					
			}
		}

		if ($checkpointvalidation_type = $this->get_checkpointvalidation_type($template_id)) {
			
			$this->append_att($template_id, $atts, 'class', 'template-validate-checkpoints '.$checkpointvalidation_type);
		}

		// Initialize the show-fetchmore property
		$this->add_att($template_id, $atts, 'show-fetchmore', $this->show_fetchmore($template_id));

		// Don't hide when no results
		$this->add_att($template_id, $atts, 'hidden-if-empty', false);
				
		return parent::init_atts($template_id, $atts);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		if ($this->get_att($template_id, $atts, 'hidden-if-empty')) {
			$this->add_jsmethod($ret, 'hideIfEmptyBlock');
		}

		return $ret;
	}

	function is_frontend_id_unique($template_id, $atts) {
	
		if ($this->is_unique($template_id, $atts)) {

			return true;
		}

		return parent::is_frontend_id_unique($template_id, $atts);
	}

	function get_id($template_id, $atts) {

		$settings_id = $this->get_settings_id($template_id);
		if ($this->is_unique($template_id, $atts)) {

			// Use the customized_settings_id so that
			// 1. We don't depend on $atts (uniqueblock ids cannot depend on $atts, or they can't be referenced across pageSections)
			// 2. We can have 'virtual' uniqueblocks: non-unique-blocks which also behave like uniqueblocks by sharing the same customized_settings_id
			// Example: 
			// GD_TEMPLATE_BLOCK_ADDCOMMENT => uniqueblock
			// GD_TEMPLATE_ACTION_ADDCOMMENT => 'virtual' uniqueblock
			return $settings_id;
		}

		$pagesection_settings_id = $atts['pagesection-settings-id'];
		return $pagesection_settings_id.'_'.$settings_id;
	}

	function fixed_id($template_id, $atts) {

		return true;
	}

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_checkpointvalidation_type($template_id) {

		if ($page = $this->get_block_page($template_id)) {

			global $gd_template_settingsmanager;
			$checkpoint_settings = $gd_template_settingsmanager->get_page_checkpoints($page);
			$type = $checkpoint_settings['type'];
			
			// Important: do NOT add GD_DATALOAD_NOCHECKPOINTVALIDATION_TYPE_DATAFROMSERVER, since this never needs checkpoint validation
			if ($type == GD_DATALOAD_NOCHECKPOINTVALIDATION_TYPE_DATAFROMSERVER) {
				return null;
			}
			if ((doing_post() && $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC) || $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER) {

				return $type;
			}
		}

		return null;
	}
	protected function show_disabled_layer($template_id) {

		return true;
	}
	protected function show_status($template_id) {

		return true;
	}
	protected function show_fetchmore($template_id) {

		return false;
	}
	protected function filter_hidden($template_id) {

		return false;
	}
	protected function filter_visible($template_id) {

		return false;
	}	

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_block_inner_templates($template_id) {

		return array();
	}
	protected function get_block_extension_templates($template_id) {

		return array();
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	function get_dataloader($template_id) {

		return GD_DATALOADER_STATIC;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_actionexecuter($template_id) {

		return null;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_dataload_query_args($template_id, $atts) {

		return array();
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_runtime_dataload_query_args($template_id, $atts) {

		return array();
	}	

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_iohandler($template_id) {
	
		// By default, use the bare Block IOHandler
		return GD_DATALOAD_IOHANDLER_BLOCK;
	}

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_messagefeedback($template_id) {

		return null;
	}
	// protected function add_title_link($template_id) {

	// 	return false;
	// }

	/***********************************************************/
	/** Repeated from "parent" class! */
	/***********************************************************/
	protected function get_block_page($template_id) {

		global $gd_template_settingsmanager;

		if ($page = $gd_template_settingsmanager->get_block_page($template_id)) {

			return $page;
		}
		return null;
	}
	protected function get_title_link($template_id) {
	
		if ($page = $this->get_block_page($template_id)) {

			return get_permalink($page);
		}

		return null;
	}
	protected function get_blocksections_classes($template_id) {
	
		return array();
	}

	//-------------------------------------------------
	// PRIVATE Functions
	//-------------------------------------------------

	private function is_unique($template_id, $atts) {

		// Some Blocks (eg: Modals implemented in GD_TEMPLATE_UNIQUEBLOCKS) are unique
		// so give them a unique ID independently of the hierarchy_id where they are located
		
		$settings_id = $this->get_settings_id($template_id);
		return in_array($settings_id, GD_TemplateManager_Utils::get_uniqueblockunits_settingids());
	}
}
