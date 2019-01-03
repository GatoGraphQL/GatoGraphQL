<?php
namespace PoP\Engine\FileStorage;

class FileStorage extends FileStorageBase {

	function __construct() {

		FileStorage_Factory::set_instance($this);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new FileStorage();
