<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_FileGenerator_BundleFiles_Utils {

    public static function get_filegenerator($enqueuefile_type, $type, $subtype) {

        if ($enqueuefile_type == 'bundlegroup' && $type == POP_RESOURCELOADER_RESOURCETYPE_JS && $subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC) {

            global $pop_resourceloader_singlethememode_jsbundlegroupfilegenerator;
            return $pop_resourceloader_singlethememode_jsbundlegroupfilegenerator;
        }
        elseif ($enqueuefile_type == 'bundlegroup' && $type == POP_RESOURCELOADER_RESOURCETYPE_JS/* && $subtype != POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC*/) {

            global $pop_resourceloader_acrossthememodes_jsbundlegroupfilegenerator;
            return $pop_resourceloader_acrossthememodes_jsbundlegroupfilegenerator;
        }
        elseif ($enqueuefile_type == 'bundlegroup' && $type == POP_RESOURCELOADER_RESOURCETYPE_CSS && $subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC) {

            global $pop_resourceloader_singlethememode_cssbundlegroupfilegenerator;
            return $pop_resourceloader_singlethememode_cssbundlegroupfilegenerator;
        }
        elseif ($enqueuefile_type == 'bundlegroup' && $type == POP_RESOURCELOADER_RESOURCETYPE_CSS/* && $subtype != POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC*/) {

            global $pop_resourceloader_acrossthememodes_cssbundlegroupfilegenerator;
            return $pop_resourceloader_acrossthememodes_cssbundlegroupfilegenerator;
        }
        elseif ($enqueuefile_type == 'bundle' && $type == POP_RESOURCELOADER_RESOURCETYPE_JS && $subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC) {

            global $pop_resourceloader_singlethememode_jsbundlefilegenerator;
            return $pop_resourceloader_singlethememode_jsbundlefilegenerator;
        }
        elseif ($enqueuefile_type == 'bundle' && $type == POP_RESOURCELOADER_RESOURCETYPE_JS/* && $subtype != POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC*/) {

            global $pop_resourceloader_acrossthememodes_jsbundlefilegenerator;
            return $pop_resourceloader_acrossthememodes_jsbundlefilegenerator;
        }
        elseif ($enqueuefile_type == 'bundle' && $type == POP_RESOURCELOADER_RESOURCETYPE_CSS && $subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC) {

            global $pop_resourceloader_singlethememode_cssbundlefilegenerator;
            return $pop_resourceloader_singlethememode_cssbundlefilegenerator;
        }
        elseif ($enqueuefile_type == 'bundle' && $type == POP_RESOURCELOADER_RESOURCETYPE_CSS/* && $subtype != POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC*/) {

            global $pop_resourceloader_acrossthememodes_cssbundlefilegenerator;
            return $pop_resourceloader_acrossthememodes_cssbundlefilegenerator;
        }

        return null;
    }
}
