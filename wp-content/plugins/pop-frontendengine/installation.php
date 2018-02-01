<?php
class PoPFrontend_Installation {

	function system_build() {

		global $pop_frontend_resourceloader_mappinggenerator, $pop_frontend_conversiongenerator;

		// CSS to Styles: generate the database
		$pop_frontend_conversiongenerator->generate();

		// Code splitting: extract all the mappings of functions calling other functions from all the .js files
		$pop_frontend_resourceloader_mappinggenerator->generate();
	}

	function system_generate_theme() {

		// ResourceLoader Config files
		if (PoP_Frontend_ServerUtils::use_code_splitting()) {

			// Delete the file containing the cached entries (or "abbreviations") from the ResourceLoader
			// Delete the "shared across thememodes" mapping (i.e. bundle and bundlegroup mapping) the first time we execute the process
			// We define that we always execute first the "default" thememode. So if this is the case, then delete the mapping
			// Set hooks so this value can be overriden by pop-cluster-resourceloader, in which the mapping can not be deleted since it will also
			// be shared across websites, and deleted manually in the deployment process
			$vars = GD_TemplateManager_Utils::get_vars();
			$across_thememodes = $vars['thememode-isdefault'];
			$across_thememodes = apply_filters('PoPFrontend_Installation:system_generate_theme:delete-across-thememodes', $across_thememodes);
			PoP_ResourceLoaderProcessorUtils::delete_entries($across_thememodes);

			// Delete the file containing what resources/bundle/bundlegroups were generated for each vars_hash_id
			global $pop_resourceloader_generatedfilesmanager;
			$pop_resourceloader_generatedfilesmanager->delete();
			
			global $pop_resourceloader_configfile_generator, $pop_resourceloader_resources_configfile_generator/*, $pop_resourceloader_initialresources_configfile_generator*/, $pop_resourceloader_hierarchyformatcombinationresources_configfile_generator;
			$pop_resourceloader_configfile_generator->generate();
			$pop_resourceloader_resources_configfile_generator->generate();
			
			// Comment Leo 20/11/2017: since making the backgroundLoad execute in window.addEventListener('load', function() {,
			// we can just wait to load resources.js, so no need for initialresources.js anymore
			// $pop_resourceloader_initialresources_configfile_generator->generate();

			$pop_resourceloader_hierarchyformatcombinationresources_configfile_generator->generate();

	        // Important: run this function below at the end, so by then we will have created all dynamic resources (eg: initialresources.js)
	        // Generate the bundle(group) file with all the resources inside
	        if (PoP_Frontend_ServerUtils::generate_loadingframe_resource_mapping()) {
	        	
	        	if (PoP_Frontend_ServerUtils::generate_bundle_files() || PoP_Frontend_ServerUtils::generate_bundlegroup_files()) {

			        global $pop_resourceloader_allroutes_filegenerator_bundlefiles;
					$pop_resourceloader_allroutes_filegenerator_bundlefiles->generate();
				}

				// Generate and Save the file containing what resources/bundle/bundlegroups were generated for each vars_hash_id
				global $pop_resourceloader_storagegenerator;
				$pop_resourceloader_storagegenerator->generate();
				$pop_resourceloader_generatedfilesmanager->save();
			}

			// // Save a new file containing the cached entries (or "abbreviations") from the ResourceLoader
			// PoP_ResourceLoaderProcessorUtils::save_entries();
		}
		
	}
}