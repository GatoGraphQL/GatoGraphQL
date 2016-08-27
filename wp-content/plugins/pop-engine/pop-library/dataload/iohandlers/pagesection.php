<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_PAGESECTION', 'pagesection');

class GD_DataLoad_IOHandler_PageSection extends GD_DataLoad_IOHandler {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_PAGESECTION;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_PageSection();