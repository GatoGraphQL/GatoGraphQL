<?php
class PoP_CoreProcessors_UserLoggedInStyles_FileGenerator extends PoP_Engine_RendererFileGeneratorBase {

	function get_dir() {

		return POP_USERSTATE_ASSETDESTINATION_DIR;
	}
	function get_url() {

		return POP_USERSTATE_ASSETDESTINATION_URL;
	}

	function get_filename() {

		return 'user-loggedin.css';
	}

	function get_renderer() {

		global $popcore_userloggedinstyles_filerenderer;
		return $popcore_userloggedinstyles_filerenderer;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $popcore_userloggedinstyles_filegenerator;
$popcore_userloggedinstyles_filegenerator = new PoP_CoreProcessors_UserLoggedInStyles_FileGenerator();