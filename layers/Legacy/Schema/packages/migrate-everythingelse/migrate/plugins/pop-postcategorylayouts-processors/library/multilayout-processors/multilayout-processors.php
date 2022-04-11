<?php
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;

class PoP_PostCategoryLayouts_Multilayout_Processor extends PoP_Application_Multilayout_ProcessorBase
{
    public function addLayoutModules(&$layouts, $handle, $format = '')
    {
        switch ($handle) {
            case POP_MULTILAYOUT_HANDLE_POSTCONTENT:
            case POP_MULTILAYOUT_HANDLE_AUTHORPOSTCONTENT:
            case POP_MULTILAYOUT_HANDLE_SINGLEPOSTCONTENT:
                $extra_layouts = array(
                    POP_FORMAT_SIMPLEVIEW => [PoP_PostCategoryLayouts_Module_Processor_SimpleViewPreviewPostLayouts::class, PoP_PostCategoryLayouts_Module_Processor_SimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_SIMPLEVIEW_FEATUREIMAGE],
                );
                if ($layout = $extra_layouts[$format] ?? null) {
                    if (POP_POSTCATEGORYLAYOUTS_CATEGORIES_LAYOUTFEATUREIMAGE) {
                        $instanceManager = InstanceManagerFacade::getInstance();
                        /** @var RelationalTypeResolverInterface */
                        $postObjectTypeResolver = $instanceManager->getInstance(PostObjectTypeResolver::class);
                        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
                        $field = $fieldQueryInterpreter->getField(
                            'isObjectType',
                            [
                                'type' => $postObjectTypeResolver->getTypeName(),
                            ]
                        );
                        $layouts[$field] = $layout;
                        // $layouts['post-featureimage'] = $layout;
                    }
                }
                break;
        }
    }
}

/**
 * Initialization
 */
new PoP_PostCategoryLayouts_Multilayout_Processor();
