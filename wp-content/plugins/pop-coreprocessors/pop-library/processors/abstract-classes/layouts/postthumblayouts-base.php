<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PostThumbLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_POSTTHUMB;
	}

	function get_extra_thumb_layouts($template_id) {

		// Add the MultiLayout item always, since the layouts will also be referenced by the MultLayout
		// If not on the MultiLayout page (eg: All Content) this will be hidden using css
		return array(
			GD_TEMPLATE_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL
		);
	}

	function get_data_fields($template_id, $atts) {

		$ret = parent::get_data_fields($template_id, $atts);

		$ret[] = $this->get_thumb_name($template_id, $atts);
		$ret[] = $this->get_url_field($template_id);

		return $ret;
	}

	function get_url_field($template_id) {

		return 'url';
	}

	function get_thumb_name($template_id, $atts) {

		return 'thumb-sm';
	}

	function get_thumb_img_class($template_id) {

		return 'img-responsive';
	}

	function get_thumb_link_class($template_id) {

		return '';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$ret['url-field'] = $this->get_url_field($template_id);
		$ret['thumb'] = array(
			'name' => $this->get_thumb_name($template_id, $atts)
		);
		$ret[GD_JS_CLASSES/*'classes'*/]['img'] = $this->get_thumb_img_class($template_id);
		if ($link_class = $this->get_thumb_link_class($template_id)) {
			$ret[GD_JS_CLASSES/*'classes'*/]['link'] = $link_class;
		}

		if ($thumb_extras = $this->get_extra_thumb_layouts($template_id)) {

			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['thumb-extras'] = $thumb_extras;
			foreach ($thumb_extras as $thumb_extra) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$thumb_extra] = $gd_template_processor_manager->get_processor($thumb_extra)->get_settings_id($thumb_extra);
			}
		}

		return $ret;
	}
	
	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		if ($thumb_extras = $this->get_extra_thumb_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$thumb_extras
			);
		}

		return $ret;
	}
}