<?php
class PoPFrontend_Installation {

	function system_build() {

		global $pop_frontend_resourceloader_mappinggenerator, $pop_frontend_conversiongenerator;
		
		// Code splitting: extract all the mappings of functions calling other functions from all the .js files
		$pop_frontend_resourceloader_mappinggenerator->generate();

		// CSS to Styles: generate the database
		$pop_frontend_conversiongenerator->generate();
	}

	function system_generate_theme() {

		// Delete the file containing the cached "abbreviations" from the ResourceLoader
		PoP_ResourceLoaderProcessorUtils::delete_abbreviations();

		// ResourceLoader Config files
		global $pop_resourceloader_configfile_generator, $pop_resourceloader_resources_configfile_generator, $pop_resourceloader_initialresources_configfile_generator, $pop_resourceloader_hierarchyformatcombinationresources_configfile_generator;
		$pop_resourceloader_configfile_generator->generate();
		$pop_resourceloader_resources_configfile_generator->generate();
		$pop_resourceloader_initialresources_configfile_generator->generate();
		$pop_resourceloader_hierarchyformatcombinationresources_configfile_generator->generate();

		// Save a new file containing the cached "abbreviations" from the ResourceLoader
		global $pop_resourceloader_abbreviationsstorage_manager;
		PoP_ResourceLoaderProcessorUtils::save_abbreviations();
		
	}
}