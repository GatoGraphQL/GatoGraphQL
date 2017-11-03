<?php
class PoP_Engine_TemplateIDConstantDBFileGenerator extends PoP_Engine_FileGeneratorBase {

	function get_dir() {

		return POP_TEMPLATEIDS_DIR;
	}

	function get_filename() {

		return 'template-ids.json';
	}

	// public function generate() {

	// 	// Save the DB in the hard disk
	// 	global $pop_templateidmanager, $pop_engine_filejsonstorage;
	// 	$pop_engine_filejsonstorage->save($this->get_filepath(), $pop_templateidmanager->get_database());
	// }
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_engine_templateidconstantdbfilegenerator;
$pop_engine_templateidconstantdbfilegenerator = new PoP_Engine_TemplateIDConstantDBFileGenerator();
