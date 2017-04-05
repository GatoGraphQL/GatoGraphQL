<?php
class PoP_CDNCore_Installation {

	function system_install(){

		global $pop_cdncore_manager;
		$pop_cdncore_manager->generate_files();
	}
}