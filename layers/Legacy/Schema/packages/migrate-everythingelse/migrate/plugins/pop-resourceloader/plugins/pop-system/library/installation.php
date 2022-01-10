<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\FileStore\Facades\FileRendererFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPWebPlatform_Installation
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction('PoP:system-build', array($this, 'systemBuild'));

        // Depending on PoP Theme active or not, generate the resources on "theme" or standard hook
        if (defined('POP_THEME_INITIALIZED')) {
            HooksAPIFacade::getInstance()->addAction('PoP:system-generate:theme', array($this, 'systemGenerateTheme'));
        } else {
            HooksAPIFacade::getInstance()->addAction('PoP:system-generate', array($this, 'systemGenerate'));
        }
    }

    public function systemBuild()
    {
        if (PoP_ResourceLoader_ServerUtils::generateCodeSplittingFiles()) {
            global $pop_webplatform_resourceloader_mappingfile, $pop_webplatform_resourceloader_mappinggenerator;

            // Code splitting: extract all the mappings of functions calling other functions from all the .js files
            $pop_webplatform_resourceloader_mappinggenerator->generate($pop_webplatform_resourceloader_mappingfile);
        }
    }

    public function systemGenerate()
    {
        $this->generateResources();
    }

    public function systemGenerateTheme()
    {
        $vars = ApplicationState::getVars();
        $acrossThememodes = \PoP\Root\App::getState('thememode-isdefault');
        $acrossThememodes = HooksAPIFacade::getInstance()->applyFilters('PoPWebPlatform_Installation:systemGenerateTheme:delete-across-thememodes', $acrossThememodes);
        $this->generateResources($acrossThememodes);
    }

    private function generateResources($delete_current_mapping = true)
    {

        // ResourceLoader Config files
        if (PoP_ResourceLoader_ServerUtils::generateCodeSplittingFiles()) {
            // Delete the file containing the cached entries (or "abbreviations") from the ResourceLoader
            // Delete the "shared across thememodes" mapping (i.e. bundle and bundlegroup mapping) the first time we execute the process
            // We define that we always execute first the "default" thememode. So if this is the case, then delete the mapping
            // Set hooks so this value can be overriden by pop-cluster-resourceloader, in which the mapping can not be deleted since it will also
            // be shared across websites, and deleted manually in the deployment process
            PoP_ResourceLoaderProcessorUtils::deleteEntries($delete_current_mapping);

            // Delete the file containing what resources/bundle/bundlegroups were generated for each model_instance_id
            global $pop_resourceloader_generatedfilesmanager;
            $pop_resourceloader_generatedfilesmanager->delete();
            
            // This hook is for PoP SPA Resource Loader to generate the config files
            HooksAPIFacade::getInstance()->doAction('PoPWebPlatform_Installation:generateResources');

            // Important: run this function below at the end, so by then we will have created all dynamic resources (eg: initialresources.js)
            // Generate the bundle(group) file with all the resources inside
            if (PoP_ResourceLoader_ServerUtils::generateLoadingframeResourceMapping()) {
                if (PoP_ResourceLoader_ServerUtils::generateBundleFiles() || PoP_ResourceLoader_ServerUtils::generateBundlegroupFiles()) {
                    global $pop_resourceloader_allroutes_filegenerator_bundlefiles;
                    // $pop_resourceloader_allroutes_filegenerator_bundlefiles->generate();
                    FileRendererFacade::getInstance()->renderAndSave($pop_resourceloader_allroutes_filegenerator_bundlefiles);
                }

                // Generate and Save the file containing what resources/bundle/bundlegroups were generated for each model_instance_id
                global $pop_resourceloader_storagegenerator;
                $pop_resourceloader_storagegenerator->generate();

                // $pop_resourceloader_generatedfilesmanager->save();
            }

            // // Save a new file containing the cached entries (or "abbreviations") from the ResourceLoader
            // PoP_ResourceLoaderProcessorUtils::saveEntries();
        }
    }
}

/**
 * Initialization
 */
new PoPWebPlatform_Installation();
