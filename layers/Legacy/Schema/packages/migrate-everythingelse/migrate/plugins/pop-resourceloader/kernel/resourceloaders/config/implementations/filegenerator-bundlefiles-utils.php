<?php

class PoP_ResourceLoader_FileGenerator_BundleFiles_Utils {

    public static function getFile($enqueuefile_type, $type, $subtype) {

        if ($enqueuefile_type == 'bundlegroup' && $type == POP_RESOURCELOADER_RESOURCETYPE_JS && $subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC) {

            global $pop_resourceloader_singlethememode_jsbundlegroupfile;
            return $pop_resourceloader_singlethememode_jsbundlegroupfile;
        }
        elseif ($enqueuefile_type == 'bundlegroup' && $type == POP_RESOURCELOADER_RESOURCETYPE_JS/* && $subtype != POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC*/) {

            global $pop_resourceloader_acrossthememodes_jsbundlegroupfile;
            return $pop_resourceloader_acrossthememodes_jsbundlegroupfile;
        }
        elseif ($enqueuefile_type == 'bundlegroup' && $type == POP_RESOURCELOADER_RESOURCETYPE_CSS && $subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC) {

            global $pop_resourceloader_singlethememode_cssbundlegroupfile;
            return $pop_resourceloader_singlethememode_cssbundlegroupfile;
        }
        elseif ($enqueuefile_type == 'bundlegroup' && $type == POP_RESOURCELOADER_RESOURCETYPE_CSS/* && $subtype != POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC*/) {

            global $pop_resourceloader_acrossthememodes_cssbundlegroupfile;
            return $pop_resourceloader_acrossthememodes_cssbundlegroupfile;
        }
        elseif ($enqueuefile_type == 'bundle' && $type == POP_RESOURCELOADER_RESOURCETYPE_JS && $subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC) {

            global $pop_resourceloader_singlethememode_jsbundlefile;
            return $pop_resourceloader_singlethememode_jsbundlefile;
        }
        elseif ($enqueuefile_type == 'bundle' && $type == POP_RESOURCELOADER_RESOURCETYPE_JS/* && $subtype != POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC*/) {

            global $pop_resourceloader_acrossthememodes_jsbundlefile;
            return $pop_resourceloader_acrossthememodes_jsbundlefile;
        }
        elseif ($enqueuefile_type == 'bundle' && $type == POP_RESOURCELOADER_RESOURCETYPE_CSS && $subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC) {

            global $pop_resourceloader_singlethememode_cssbundlefile;
            return $pop_resourceloader_singlethememode_cssbundlefile;
        }
        elseif ($enqueuefile_type == 'bundle' && $type == POP_RESOURCELOADER_RESOURCETYPE_CSS/* && $subtype != POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC*/) {

            global $pop_resourceloader_acrossthememodes_cssbundlefile;
            return $pop_resourceloader_acrossthememodes_cssbundlefile;
        }

        return null;
    }
}
