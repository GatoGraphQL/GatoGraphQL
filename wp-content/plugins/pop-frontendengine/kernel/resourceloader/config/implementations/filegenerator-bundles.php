<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_MultipleFileGenerator_Bundles {

    public function generate() {
        
        global $pop_resourceloader_jsbundlefilegenerator, $pop_resourceloader_cssbundlefilegenerator;
        $resource_mapping = PoP_ResourceLoader_FileReproduction_Utils::get_resource_mapping(false);

        // Generate the bundle file with all the resources inside?
        // $type = 'js' or 'css'
        foreach ($resource_mapping['bundles'] as $type => $bundles) {

            if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                $filegenerator = $pop_resourceloader_jsbundlefilegenerator;
            }
            elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                $filegenerator = $pop_resourceloader_cssbundlefilegenerator;
            }
            foreach ($bundles as $bundleId => $resources_item) {

                $filegenerator->set_filename($bundleId);
                $filegenerator->set_extension('.'.$type);
                $filegenerator->set_resources($resources_item);
                $filegenerator->generate();
            }
        }
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_multiplefilegenerator_bundles;
$pop_resourceloader_multiplefilegenerator_bundles = new PoP_ResourceLoader_MultipleFileGenerator_Bundles();
