<?php
class PoP_Engine_TemplateIDConstantDBFileGenerator extends PoP_Engine_FileLocationBase {

	function get_dir() {

		return POP_TEMPLATEIDS_DIR;
	}

	function get_filename() {

		return 'template-ids.json';
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_engine_templateidconstantdbfilegenerator;
$pop_engine_templateidconstantdbfilegenerator = new PoP_Engine_TemplateIDConstantDBFileGenerator();
