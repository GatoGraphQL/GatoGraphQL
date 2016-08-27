<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CustomPreviewUserLayoutsBase extends GD_Template_Processor_PreviewUserLayoutsBase {

	function horizontal_layout($template_id) {

		return false;
	}

	function horizontal_media_layout($template_id) {

		return false;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($this->get_quicklinkgroup_top($template_id)) {
			$ret[GD_JS_CLASSES/*'classes'*/]['quicklinkgroup-top'] = 'icon-only pull-right';
		}

		if ($this->horizontal_layout($template_id)) {

			$ret[GD_JS_CLASSES/*'classes'*/]['name'] = 'media-heading';
			$ret[GD_JS_CLASSES/*'classes'*/]['wrapper'] = 'row';
			$ret[GD_JS_CLASSES/*'classes'*/]['avatar-wrapper'] = 'col-xsm-3';
			$ret[GD_JS_CLASSES/*'classes'*/]['content-body'] = 'col-xsm-9';
		}
		elseif ($this->horizontal_media_layout($template_id)) {

			$ret[GD_JS_CLASSES/*'classes'*/]['name'] = 'media-heading';
			$ret[GD_JS_CLASSES/*'classes'*/]['wrapper'] = 'media'; //' overflow-visible';
			$ret[GD_JS_CLASSES/*'classes'*/]['avatar-wrapper'] = 'pull-left';
			$ret[GD_JS_CLASSES/*'classes'*/]['avatar'] = 'media-object';
			$ret[GD_JS_CLASSES/*'classes'*/]['content-body'] = 'media-body';
		}

		return $ret;
	}
}