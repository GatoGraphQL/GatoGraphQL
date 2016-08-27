<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ControlBlocksBase extends GD_Template_Processor_BlocksBase {

	protected function get_blocksections_classes($template_id) {

		$ret = parent::get_blocksections_classes($template_id);
		$ret['controlgroup-top'] = 'top pull-right';
		return $ret;
	}
}