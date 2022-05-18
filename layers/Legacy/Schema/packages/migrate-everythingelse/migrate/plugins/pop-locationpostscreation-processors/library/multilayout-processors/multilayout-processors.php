<?php
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\LocationPosts\TypeResolvers\ObjectType\LocationPostObjectTypeResolver;

class PoP_LocationPostsCreation_Multilayout_Processor extends PoP_Application_Multilayout_ProcessorBase
{
    public function addLayoutComponents(&$layouts, $handle, $format = '')
    {
        switch ($handle) {
            case POP_MULTILAYOUT_HANDLE_POSTCONTENT:
                $location_components = array();
                if ($handle == POP_MULTILAYOUT_HANDLE_POSTCONTENT) {
                    $location_components = array(
                        POP_FORMAT_TABLE => [PoP_LocationPostsCreation_Module_Processor_CustomPreviewPostLayouts::class, PoP_LocationPostsCreation_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT],
                    );
                }

                if ($layout = $location_components[$format] ?? null) {
                    $instanceManager = InstanceManagerFacade::getInstance();
                    /** @var RelationalTypeResolverInterface */
                    $locationPostTypeResolver = $instanceManager->getInstance(LocationPostObjectTypeResolver::class);
                    $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
                    $field = $fieldQueryInterpreter->getField(
                        'isObjectType',
                        [
                            'type' => $locationPostTypeResolver->getTypeName(),
                        ]
                    );
                    $layouts[$field] = $layout;
                }
                break;
        }
    }
}

/**
 * Initialization
 */
new PoP_LocationPostsCreation_Multilayout_Processor();
