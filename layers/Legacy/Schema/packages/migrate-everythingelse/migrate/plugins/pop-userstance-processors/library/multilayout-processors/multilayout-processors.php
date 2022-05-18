<?php
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Stances\TypeResolvers\ObjectType\StanceObjectTypeResolver;

class PoP_UserStance_Multilayout_Processor extends PoP_Application_Multilayout_ProcessorBase
{
    public function addLayoutComponents(&$layouts, $handle, $format = '')
    {
        // Only if this post type is shown in All Content
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (in_array(POP_USERSTANCE_POSTTYPE_USERSTANCE, $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            switch ($handle) {
                case POP_MULTILAYOUT_HANDLE_POSTCONTENT:
                case POP_MULTILAYOUT_HANDLE_AUTHORPOSTCONTENT:
                case POP_MULTILAYOUT_HANDLE_SINGLEPOSTCONTENT:
                    $stance_layouts = array();
                    if ($handle == POP_MULTILAYOUT_HANDLE_POSTCONTENT) {
                        $stance_layouts = array(
                            POP_FORMAT_NAVIGATOR => [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR],
                            POP_FORMAT_ADDONS => [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED],
                            POP_FORMAT_THUMBNAIL => [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL],
                            POP_FORMAT_LIST => [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED],
                            POP_FORMAT_SIMPLEVIEW => [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED],
                            POP_FORMAT_FULLVIEW => [UserStance_Module_Processor_CustomFullViewLayouts::class, UserStance_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_STANCE],
                        );
                    } elseif ($handle == POP_MULTILAYOUT_HANDLE_AUTHORPOSTCONTENT) {
                        $stance_layouts = array(
                            POP_FORMAT_FULLVIEW => [UserStance_Module_Processor_CustomFullViewLayouts::class, UserStance_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_STANCE],
                        );
                    } elseif ($handle == POP_MULTILAYOUT_HANDLE_SINGLEPOSTCONTENT) {
                        $stance_layouts = array(
                            POP_FORMAT_FULLVIEW => [UserStance_Module_Processor_CustomFullViewLayouts::class, UserStance_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_STANCE],
                        );
                    }

                    if ($layout = $stance_layouts[$format] ?? null) {
                        $instanceManager = InstanceManagerFacade::getInstance();
                        /** @var RelationalTypeResolverInterface */
                        $stanceTypeResolver = $instanceManager->getInstance(StanceObjectTypeResolver::class);
                        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
                        $field = $fieldQueryInterpreter->getField(
                            'isObjectType',
                            [
                                'type' => $stanceTypeResolver->getTypeName(),
                            ]
                        );
                        $layouts[$field] = $layout;
                    }
                    break;
            }
        }
    }
}

/**
 * Initialization
 */
new PoP_UserStance_Multilayout_Processor();
