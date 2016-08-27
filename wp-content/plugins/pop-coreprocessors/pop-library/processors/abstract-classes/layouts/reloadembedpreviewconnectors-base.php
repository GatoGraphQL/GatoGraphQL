<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ReloadEmbedPreviewConnectorsBase extends GD_Template_Processor_MarkersBase {

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'reloadEmbedPreview');
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Bind the Embed iframe and the input together. When the input value changes, the iframe
		// will update itself with the URL in the input
		$iframe = $this->get_att($template_id, $atts, 'iframe-template');
		$this->append_att($iframe, $atts, 'class', $iframe.' pop-merge');
		$this->add_att($iframe, $atts, 'template-cb', true);
		
		// $this->merge_att($template_id, $atts, 'params', array(
		// 	'data-iframe-template' => $iframe,
		// ));
		$input = $this->get_att($template_id, $atts, 'input-template');
		$this->merge_att($template_id, $atts, 'previoustemplates-ids', array(
			'data-iframe-target' => $iframe,
			'data-input-target' => $input,
		));
		
		return parent::init_atts($template_id, $atts);
	}
}