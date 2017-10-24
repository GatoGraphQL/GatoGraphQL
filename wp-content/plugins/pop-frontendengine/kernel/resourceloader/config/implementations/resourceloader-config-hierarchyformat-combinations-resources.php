<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_FileReproduction_HierarchyFormatCombinationResourcesConfig extends PoP_ResourceLoader_FileReproduction_AddResourcesConfigBase {

	protected $hierarchy, $format, $fileurl;

    function get_renderer() {

        global $pop_resourceloader_hierarchyformatcombinationresources_configfile_renderer;
        return $pop_resourceloader_hierarchyformatcombinationresources_configfile_renderer;
    }

    public function setHierarchy($hierarchy) {
        
        return $this->hierarchy = $hierarchy;
    }

    public function setFormat($format) {
        
        return $this->format = $format;
    }

    protected function match_hierarchy() {
        
        return $this->hierarchy;
    }

    protected function match_format() {
        
        return $this->format;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ResourceLoader_FileReproduction_HierarchyFormatCombinationResourcesConfig();
