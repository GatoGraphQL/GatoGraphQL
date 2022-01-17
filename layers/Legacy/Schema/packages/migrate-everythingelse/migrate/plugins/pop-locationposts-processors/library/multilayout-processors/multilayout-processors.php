<?php
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\LocationPosts\TypeResolvers\ObjectType\LocationPostObjectTypeResolver;

class PoP_LocationPosts_Multilayout_Processor extends PoP_Application_Multilayout_ProcessorBase
{
    protected function useSimpleviewLayout()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_LocationPosts_Multilayout_Processor:use-simpleview-layout',
            false
        );
    }

    public function addLayoutModules(&$layouts, $handle, $format = '')
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var RelationalTypeResolverInterface */
        $locationPostTypeResolver = $instanceManager->getInstance(LocationPostObjectTypeResolver::class);
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $field = $fieldQueryInterpreter->getField(
            'isType',
            [
                'type' => $locationPostTypeResolver->getTypeName(),
            ]
        );
        switch ($handle) {
            case POP_MULTILAYOUT_HANDLE_POSTCONTENT:
            case POP_MULTILAYOUT_HANDLE_AUTHORPOSTCONTENT:
            case POP_MULTILAYOUT_HANDLE_SINGLEPOSTCONTENT:
                $location_modules = array();
                if ($handle == POP_MULTILAYOUT_HANDLE_POSTCONTENT) {
                    $location_modules = array(
                        POP_FORMAT_NAVIGATOR => [GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR],
                        POP_FORMAT_ADDONS => [GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS],
                        POP_FORMAT_DETAILS => [GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS],
                        POP_FORMAT_THUMBNAIL => [GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL],
                        POP_FORMAT_LIST => [GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST],
                        POP_FORMAT_FULLVIEW => [GD_Custom_EM_Module_Processor_CustomFullViewLayouts::class, GD_Custom_EM_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_LOCATIONPOST],
                    );

                    // These layouts are needed only if using the Event SimpleView layout. Otherwise, "abovecontent" layout from the default post layout will be used (through handle POP_MULTILAYOUT_HANDLE_POSTABOVECONTENT)
                    if ($this->useSimpleviewLayout()) {
                        $location_modules[POP_FORMAT_SIMPLEVIEW] = [PoPSFEM_Module_Processor_SimpleViewPreviewPostLayouts::class, PoPSFEM_Module_Processor_SimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_SIMPLEVIEW];
                    }
                } elseif ($handle == POP_MULTILAYOUT_HANDLE_AUTHORPOSTCONTENT) {
                    $location_modules = array(
                        POP_FORMAT_FULLVIEW => [GD_Custom_EM_Module_Processor_CustomFullViewLayouts::class, GD_Custom_EM_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_LOCATIONPOST],
                    );
                } elseif ($handle == POP_MULTILAYOUT_HANDLE_SINGLEPOSTCONTENT) {
                    $location_modules = array(
                        POP_FORMAT_FULLVIEW => [GD_Custom_EM_Module_Processor_CustomFullViewLayouts::class, GD_Custom_EM_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_LOCATIONPOST],
                    );
                }

                if ($layout = $location_modules[$format] ?? null) {
                    $layouts[$field] = $layout;
                }
                break;

            case POP_MULTILAYOUT_HANDLE_POSTABOVECONTENT:
                $layouts[$field] = [GD_EM_Module_Processor_EventMultipleComponents::class, GD_EM_Module_Processor_EventMultipleComponents::MODULE_MULTICOMPONENT_LOCATION];
                break;
        }
    }
}

/**
 * Initialization
 */
new PoP_LocationPosts_Multilayout_Processor();
