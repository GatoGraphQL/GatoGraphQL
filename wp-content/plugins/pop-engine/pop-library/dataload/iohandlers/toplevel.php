<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_TOPLEVEL', 'toplevel');

class GD_DataLoad_IOHandler_TopLevel extends GD_DataLoad_TopLevelIOHandler {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_TOPLEVEL;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_TopLevel();