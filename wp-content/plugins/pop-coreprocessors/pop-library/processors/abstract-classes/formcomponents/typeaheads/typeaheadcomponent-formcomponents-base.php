<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TypeaheadComponentFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	private $resources;

	function __construct() {

		parent::__construct();

		// Comment Leo 20/10/2017: Important! Because the template will be rendered on runtime in the front-end,
		// we must make sure that this template is delivered on the ResourceLoader when doing code-splitting
		$this->resources = array();
		add_filter(
			'PoP_CoreProcessors_ResourceLoaderProcessor:typeahead:templates',
			array($this, 'get_dependencies')
		);
	}

	function get_dependencies($resources) {

		if ($this->resources) {

			$resources = array_merge(
				$resources,
				$this->resources
			);

			// Reset state
			$this->resources = array();
		}
		return $resources;
	}

	function get_template_source($template_id, $atts) {

		return null;
	}

	protected function get_value_key($template_id, $atts) {

		return 'id';
	}
	protected function get_tokenizer_keys($template_id, $atts) {

		return array();
	}
	protected function get_limit($template_id, $atts) {

		return 5;
	}

	protected function get_thumbprint_query($template_id, $atts) {

		return array();
	}
	protected function execute_thumbprint($query) {

		return array();
	}	
	protected function get_thumbprint($template_id, $atts) {

		$query = $this->get_thumbprint_query($template_id, $atts);
		$results = $this->execute_thumbprint($query);

		return (int) $results[0];
	}
	protected function get_typeahead_dataload_source($template_id, $atts) {
		
		return null;
	}
	protected function get_source_filter($template_id, $atts) {

		return null;
	}
	protected function get_source_filter_params($template_id, $atts) {

		return array();
	}
	protected function get_source_url($template_id, $atts) {

		$url = $this->get_typeahead_dataload_source($template_id, $atts);

		// Add the output=json params, typeahead datastructure
		$url = GD_TemplateManager_Utils::add_jsonoutput_results_params($url, GD_TEMPLATEFORMAT_TYPEAHEAD);
		
		// Add the filter and the filtering params
		if ($filtername = $this->get_source_filter($template_id, $atts)) {
			
			global $gd_filter_manager;
			$filter_object = $gd_filter_manager->get_filter($filtername);
			$filter_params = $this->get_source_filter_params($template_id, $atts);
			$url = $gd_filter_manager->add_filter_params($url, $filter_object, $filter_params);		
		}

		// // Hooks to allow pop-cdn to replace the domain with the CDN
		// $url = apply_filters(
		// 	'GD_Template_Processor_TypeaheadComponentFormComponentsBase:source-url', 
		// 	$url,
		// 	$template_id
		// );

		return $url;
	}
	protected function get_prefetch_url($template_id, $atts) {

		$url = $this->get_source_url($template_id, $atts);
		
		// Bring 10 times the pre-defined result set
		$posts_per_page = get_option('posts_per_page');
		$limit = $posts_per_page * 10;
		$url = add_query_arg(GD_URLPARAM_LIMIT, $limit, $url);

		return $url;
	}
	protected function get_remote_url($template_id, $atts) {

		$url = $this->get_source_url($template_id, $atts);
		$url = add_query_arg(GD_URLPARAM_LIMIT, 12, $url);

		return $url;
	}
	protected function get_static_suggestions($template_id, $atts) {

		return array();
	}
	protected function get_pending_msg($template_id) {

		return __('Loading Suggestions', 'pop-coreprocessors');
	}
	protected function get_notfound_msg($template_id) {

		return __('No Results', 'pop-coreprocessors');
	}

	function get_js_runtimesetting($template_id, $atts) {

		$ret = parent::get_js_runtimesetting($template_id, $atts);

		// Comment Leo 10/08/2017: the thumbprint value will make the ETag for a page to change whenever there is a new post,
		// even if this new post is show in that page
		// Then, this 'thumbprint' key+value will need to be removed before doing wp_hash
		$ret['dataset'] = array(
			POP_KEYS_THUMBPRINT/*'thumbprint'*/ => $this->get_thumbprint($template_id, $atts)
		);

		return $ret;
	}
	
	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		$label = $this->get_label($template_id, $atts);

		// Comment Leo 20/10/2017: Important! Because the template will be rendered on runtime in the front-end,
		// we must make sure that this template is delivered on the ResourceLoader when doing code-splitting
		$template_source = $this->get_template_source($template_id, $atts);
		$this->resources[] = PoP_ResourceLoaderProcessorUtils::get_template_resource_name($template_source);
		
		// Dataset
		$ret['dataset'] = array(
			'layout' => $template_source,
			'name' => $template_id,
			'header' => $label ? '<strong class="menu-text">'.$label.'</strong>' : '',
			'pending' => '<p class="clearfix menu-text text-warning"><em>'.GD_CONSTANT_LOADING_SPINNER.' '.$this->get_pending_msg($template_id).'</em></p>',
			'notFound' => '<p class="clearfix menu-text"><em>'.$this->get_notfound_msg($template_id).'</em></p>',
			'valueKey' => $this->get_value_key($template_id, $atts),
			'limit' => $this->get_limit($template_id, $atts),
			'tokenizerKeys' => $this->get_tokenizer_keys($template_id, $atts),
			// 'thumbprint' => $this->get_thumbprint($template_id, $atts)
		);

		// Static suggestions: no need for remote/prefetch
		if ($staticSuggestions = $this->get_static_suggestions($template_id, $atts)) {
			
			$ret['dataset']['staticSuggestions'] = $staticSuggestions;
			$ret['dataset']['local'] = array(); // local attribute is mandatory if no remove/prefetch provided
		}
		else {

			$ret['dataset']['remote'] = $this->get_remote_url($template_id, $atts);
			$ret['dataset']['prefetch'] = $this->get_prefetch_url($template_id, $atts);
		}

		return $ret;
	}
}
