<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CustomPreviewPostLayoutsBase extends GD_Template_Processor_PreviewPostLayoutsBase {

	protected function get_detailsfeed_bottom_layouts($template_id) {

		$layouts = array();

		// Add the highlights and the referencedby. Lazy or not lazy?
		if (PoPTheme_Wassup_Utils::feed_details_lazyload()) {
			$layouts[] = GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER;
			$layouts[] = GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_DETAILS;
			$layouts[] = GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_DETAILS;
		}
		else {
			$layouts[] = GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_DETAILS;
			$layouts[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_DETAILS;
		}

		// Allow to override. Eg: TPP Debate website adds the Stance Counter
		$layouts = apply_filters('GD_Template_Processor_CustomPreviewPostLayoutsBase:detailsfeed_bottom_layouts', $layouts, $template_id);

		return $layouts;
	}

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

		$ret[GD_JS_CLASSES/*'classes'*/]['title'] = 'media-heading';
		if ($this->horizontal_layout($template_id)) {

			$ret[GD_JS_CLASSES/*'classes'*/]['wrapper'] = 'row';
			$ret[GD_JS_CLASSES/*'classes'*/]['thumb-wrapper'] = 'col-xsm-4';
			$ret[GD_JS_CLASSES/*'classes'*/]['content-body'] = 'col-xsm-8';
		}
		elseif ($this->horizontal_media_layout($template_id)) {

			$ret[GD_JS_CLASSES/*'classes'*/]['wrapper'] = 'media'; //' overflow-visible';
			$ret[GD_JS_CLASSES/*'classes'*/]['thumb-wrapper'] = 'media-left';
			$ret[GD_JS_CLASSES/*'classes'*/]['content-body'] = 'media-body';
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Make the thumb image responsive
		if ($this->horizontal_layout($template_id)) {
			if ($thumb = $this->get_post_thumb_template($template_id)) {
				$this->append_att($thumb, $atts, 'img-class', 'img-responsive');
			}
		}

		return parent::init_atts($template_id, $atts);
	}
}