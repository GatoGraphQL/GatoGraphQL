<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_CHECKPOINT', 'checkpoint');

class GD_DataLoad_IOHandler_Checkpoint extends GD_DataLoad_CheckpointIOHandler {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_CHECKPOINT;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_Checkpoint();