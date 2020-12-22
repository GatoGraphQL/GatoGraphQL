<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPSchema\LocationPosts\TypeResolvers\LocationPostTypeResolver;

class PoP_LocationPostCategoryLayouts_Multilayout_Processor extends PoP_Application_Multilayout_ProcessorBase
{
    public function addLayoutModules(&$layouts, $handle, $format = '')
    {
        switch ($handle) {
            case POP_MULTILAYOUT_HANDLE_POSTABOVECONTENT:
                if (POP_POSTCATEGORYLAYOUTS_CATEGORIES_LAYOUTFEATUREIMAGE) {
                    $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
                    $field = $fieldQueryInterpreter->getField(
                        'isType',
                        [
                            'type' => LocationPostTypeResolver::NAME,
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
