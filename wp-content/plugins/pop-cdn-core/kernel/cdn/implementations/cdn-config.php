<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_CDNCore_FileReproduction_ThumbprintsConfig extends PoP_CDNCore_FileReproduction {

    public function get_js_path() {
        
        return POP_CDNCORE_ASSETS_DIR.'/js/jobs/cdn-config.js';
    }
    
	public function get_configuration() {
        
        $configuration = parent::get_configuration();

        global $pop_cdncore_thumbprint_manager;
        $thumbprints = $pop_cdncore_thumbprint_manager->get_thumbprints();
        $configuration['$thumbprints'] = $thumbprints;
        $configuration['$criteria_rejected'] = $this->get_rejected_criteriaitems();
        $configuration['$criteria_thumbprints'] = array();
        foreach ($thumbprints as $thumbprint) {

            $configuration['$criteria_thumbprints'][$thumbprint] = $this->get_thumbprints_criteriaitems($thumbprint);
        }

        return $configuration;
    }

    protected function get_rejected_criteriaitems() {

        return array(
            'startsWith' => array(
                'full' => apply_filters(
                    'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:startsWith:full',
                    array()
                ),
                'partial' => apply_filters(
                    'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:startsWith:partial',
                    array()
                ),
            ),
            // Array of Arrays: elem[0] = URL param, elem[1] = value
            'hasParamValues' => apply_filters(
                'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:hasParamValues',
                array()
            ),
            // Array of Arrays: elem[0] = URL param, elem[1] = value
            'noParamValues' => apply_filters(
                'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:noParamValues',
                array()
            ),
            'isHome' => apply_filters(
                'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:isHome',
                false
            )
        );
    }

    protected function get_thumbprints_criteriaitems($thumbprint) {

        return array(
            'startsWith' => array(
                'full' => apply_filters(
                    'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:full',
                    array(),
                    $thumbprint
                ),
                'partial' => apply_filters(
                    'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
                    array(),
                    $thumbprint
                ),
            ),
            'hasParamValues' => apply_filters(
                'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:hasParamValues',
                array(),
                $thumbprint
            ),
            'noParamValues' => apply_filters(
                'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:noParamValues',
                array(),
                $thumbprint
            ),
            'isHome' => apply_filters(
                'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:isHome',
                false,
                $thumbprint
            )
        );
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDNCore_FileReproduction_ThumbprintsConfig();
