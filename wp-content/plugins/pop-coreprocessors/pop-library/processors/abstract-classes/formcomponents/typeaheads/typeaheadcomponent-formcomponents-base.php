<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TypeaheadComponentFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

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

		return $results[0];
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
	
	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		$label = $this->get_label($template_id, $atts);
		
		// Dataset
		$ret['dataset'] = array(
			'layout' => $this->get_template_source($template_id, $atts),//$this->get_layout($template_id),
			'name' => $template_id,
			'header' => $label ? '<strong class="menu-text">'.$label.'</strong>' : '',
			'pending' => '<p class="clearfix menu-text text-warning"><em>'.GD_CONSTANT_LOADING_SPINNER.' '.$this->get_pending_msg($template_id).'</em></p>',
			'notFound' => '<p class="clearfix menu-text"><em>'.$this->get_notfound_msg($template_id).'</em></p>',
			'valueKey' => $this->get_value_key($template_id, $atts),
			'limit' => $this->get_limit($template_id, $atts),
			'tokenizerKeys' => $this->get_tokenizer_keys($template_id, $atts),
			'thumbprint' => $this->get_thumbprint($template_id, $atts)
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
