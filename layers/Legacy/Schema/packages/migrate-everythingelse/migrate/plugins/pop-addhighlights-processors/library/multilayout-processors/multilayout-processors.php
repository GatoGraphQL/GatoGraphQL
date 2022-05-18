<?php

use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Highlights\TypeResolvers\ObjectType\HighlightObjectTypeResolver;

class PoP_AddHighlights_Multilayout_Processor extends PoP_Application_Multilayout_ProcessorBase
{
    public function addLayoutComponentVariations(&$layouts, $handle, $format = '')
    {
        // Only if this post type is shown in All Content
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (in_array(POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT, $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            switch ($handle) {
                case POP_MULTILAYOUT_HANDLE_POSTCONTENT:
                case POP_MULTILAYOUT_HANDLE_AUTHORPOSTCONTENT:
                case POP_MULTILAYOUT_HANDLE_SINGLEPOSTCONTENT:
                    $instanceManager = InstanceManagerFacade::getInstance();
                    /** @var RelationalTypeResolverInterface */
                    $highlightTypeResolver = $instanceManager->getInstance(HighlightObjectTypeResolver::class);
                    $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
                    $field = $fieldQueryInterpreter->getField(
                        'isObjectType',
                        [
                            'type' => $highlightTypeResolver->getTypeName(),
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
