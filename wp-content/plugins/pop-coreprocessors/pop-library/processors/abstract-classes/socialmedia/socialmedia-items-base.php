<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SocialMediaItemsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_SOCIALMEDIA_ITEM;
	}

	function get_name($template_id) {

		return null;
	}
	function get_shortname($template_id) {

		return null;
	}
	function get_fontawesome($template_id, $atts) {

		return null;
	}
	function get_shareurl($template_id) {

		$settings = gd_socialmedia_provider_settings();
		$provider = $this->get_provider($template_id);

		return $settings[$provider]['share-url'];
	}
	function get_url_field($template_id) {

		return 'url';
	}
	function get_title_field($template_id) {

		return null;
	}
	function get_provider($template_id) {

		return null;
	}

	function get_data_fields($template_id, $atts) {

		return array($this->get_url_field($template_id));
	}

	function get_template_path($template_id, $atts) {
	
		return true;
	}

	// function disabled($template_id) {

	// 	return false;
	// }

	function get_replacestr_from_itemobject($template_id, $atts) {

		$ret = parent::get_replacestr_from_itemobject($template_id, $atts);		

		$ret[] = array(
			'replace-from-field' => 'share-url-original', 
			'replace-where-field' => 'share-url', 
			'replacements' => array(
				array(
					// 'replace-str' => '%1$s', 
					'replace-str' => '%url%', 
					'replace-with-field' => $this->get_url_field($template_id),
					'encode-uri-component' => true
				),
				array(
					// 'replace-str' => '%2$s', 
					'replace-str' => '%title%', 
					'replace-with-field' => $this->get_title_field($template_id),
					'encode-uri-component' => true
				)
			)
		);

		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);

		$name = $this->get_name($template_id);
		$short_name = $this->get_shortname($template_id);
		$shareon = sprintf(__('Share on %s', 'pop-coreprocessors'), $name);
		$ret['name'] = $shareon; //$name;
		$ret['short-name'] = $short_name;
		$ret['targets']['socialmedia'] = GD_URLPARAM_TARGET_SOCIALMEDIA;
		$ret[GD_JS_TITLES/*'titles'*/]['share'] = $shareon;
		$ret[GD_JS_FONTAWESOME/*'fontawesome'*/] = $this->get_fontawesome($template_id, $atts);
		
		$share_url = $this->get_shareurl($template_id);
		$ret['share-url'] = $share_url;
		$ret['share-url-original'] = $share_url;
		if ($provider = $this->get_provider($template_id)) {
			$ret['provider'] = $provider;
		}

		return $ret;
	}	
}