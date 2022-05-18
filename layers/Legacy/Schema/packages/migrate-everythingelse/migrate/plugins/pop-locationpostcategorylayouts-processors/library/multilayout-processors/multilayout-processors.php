<?php
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\LocationPosts\TypeResolvers\ObjectType\LocationPostObjectTypeResolver;

class PoP_LocationPostCategoryLayouts_Multilayout_Processor extends PoP_Application_Multilayout_ProcessorBase
{
    public function addLayoutComponents(&$layouts, $handle, $format = '')
    {
        switch ($handle) {
            case POP_MULTILAYOUT_HANDLE_POSTABOVECONTENT:
                if (POP_POSTCATEGORYLAYOUTS_CATEGORIES_LAYOUTFEATUREIMAGE) {
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
                    $layouts[$field] = [PoP_LocationPostCategoryLayouts_Module_Processor_MultipleComponents::class, PoP_LocationPostCategoryLayouts_Module_Processor_MultipleComponents::MODULE_MULTICOMPONENT_LOCATIONMAP];
                    // $layouts[POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST.'-map'] = [PoP_LocationPostCategoryLayouts_Module_Processor_MultipleComponents::class, PoP_LocationPostCategoryLayouts_Module_Processor_MultipleComponents::MODULE_MULTICOMPONENT_LOCATIONMAP];
                }
                break;
        }
    }
}

/**
 * Initialization
 */
new PoP_LocationPostCategoryLayouts_Multilayout_Processor();
