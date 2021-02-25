<?php
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\LocationPosts\TypeResolvers\LocationPostTypeResolver;

class PoP_LocationPostsCreation_Multilayout_Processor extends PoP_Application_Multilayout_ProcessorBase
{
    public function addLayoutModules(&$layouts, $handle, $format = '')
    {
        switch ($handle) {
            case POP_MULTILAYOUT_HANDLE_POSTCONTENT:
                $location_modules = array();
                if ($handle == POP_MULTILAYOUT_HANDLE_POSTCONTENT) {
                    $location_modules = array(
                        POP_FORMAT_TABLE => [PoP_LocationPostsCreation_Module_Processor_CustomPreviewPostLayouts::class, PoP_LocationPostsCreation_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_EDIT],
                    );
                }

                if ($layout = $location_modules[$format] ?? null) {
                    $instanceManager = InstanceManagerFacade::getInstance();
                    /** @var TypeResolverInterface */
                    $locationPostTypeResolver = $instanceManager->getInstance(LocationPostTypeResolver::class);
                    $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
                    $field = $fieldQueryInterpreter->getField(
                        'isType',
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
