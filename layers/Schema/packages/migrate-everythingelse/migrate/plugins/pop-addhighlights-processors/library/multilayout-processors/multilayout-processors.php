<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPSchema\Highlights\TypeResolvers\HighlightTypeResolver;

class PoP_AddHighlights_Multilayout_Processor extends PoP_Application_Multilayout_ProcessorBase
{
    public function addLayoutModules(&$layouts, $handle, $format = '')
    {
        // Only if this post type is shown in All Content
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (in_array(POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT, $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            switch ($handle) {
                case POP_MULTILAYOUT_HANDLE_POSTCONTENT:
                case POP_MULTILAYOUT_HANDLE_AUTHORPOSTCONTENT:
                case POP_MULTILAYOUT_HANDLE_SINGLEPOSTCONTENT:
                    $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
                    $field = $fieldQueryInterpreter->getField(
                        'isType',
                        [
                            'type' => HighlightTypeResolver::NAME,
                        ]
                    );
                    $layouts[$field] = [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT];
                    break;
            }
        }
    }
}

/**
 * Initialization
 */
new PoP_AddHighlights_Multilayout_Processor();
