<?php
class PoP_CDNCore_Installation {

	function system_generate() {

		global $pop_cdncore_configfile_generator;
		$pop_cdncore_configfile_generator->generate();
	}
}