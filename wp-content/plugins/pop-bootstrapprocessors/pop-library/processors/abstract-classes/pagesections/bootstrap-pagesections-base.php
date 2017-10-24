<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_BootstrapPageSectionsBase extends GD_Template_Processor_InterceptablePageSectionsBase {

	function get_pagesection_insideextensions($template_id) {

		return array(
			GD_TEMPLATEEXTENSION_PAGESECTIONFRAME
		);
	}

	function get_replicate_blocksettingsids($template_id, $atts) {

		$ret = parent::get_replicate_blocksettingsids($template_id, $atts);

		$block_frames = apply_filters(
			'GD_Template_Processor_BootstrapPageSectionsBase:replicate_blocksettingsids',
			array()
		);
		foreach ($ret as $blockunit => $blocksettingsids) {

			// Add the frame
			if ($block_frame = $block_frames[$blockunit]) {
				$ret[$blockunit][GD_JS_BLOCKUNITSFRAME/*'blockunits-frame'*/] = array($block_frame);
			}
		}

		return $ret;
	}
	
	function get_template_extra_sources($template_id, $atts) {

		// Add the inside extension templates
		$ret = parent::get_template_extra_sources($template_id, $atts);
		$ret['insideextensions'] = $this->get_pagesection_insideextensions($template_id);
		return $ret;
	}

	// function get_template_configuration($template_id, $atts) {
	
	// 	global $gd_template_processor_manager;

	// 	$ret = parent::get_template_configuration($template_id, $atts);	

	// 	// Add the inside extension templates
	// 	$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['insideextensions'] = $this->get_pagesection_insideextensions($template_id);		

	// 	return $ret;
	// }
}

