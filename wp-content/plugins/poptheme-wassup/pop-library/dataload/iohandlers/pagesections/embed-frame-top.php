<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_PAGESECTION_FRAMETOPSIMPLE', 'pagesection-frametopsimple');

class GD_DataLoad_IOHandler_FrameTopSimplePageSection extends GD_DataLoad_IOHandler_PageSection {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_PAGESECTION_FRAMETOPSIMPLE;
	}

	function get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $data_settings, $executed = null, $atts) {
	
		$ret = parent::get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $data_settings, $executed, $atts);

		// Allow Organik Fundraising to override it
		$ret[GD_URLPARAM_TITLE] = apply_filters(
			'GD_DataLoad_IOHandler_FrameTopSimplePageSection:document_title',
			gd_get_document_title()
		);

		return $ret;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_FrameTopSimplePageSection();