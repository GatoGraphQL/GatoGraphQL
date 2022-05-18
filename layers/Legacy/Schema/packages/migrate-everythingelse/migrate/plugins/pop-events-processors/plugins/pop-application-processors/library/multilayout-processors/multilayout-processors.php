<?php
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Events\Constants\Scopes;
use PoPCMSSchema\Events\Facades\EventTypeAPIFacade;
use PoPCMSSchema\Events\TypeResolvers\ObjectType\EventObjectTypeResolver;

class PoP_Events_Multilayout_Processor extends PoP_Application_Multilayout_ProcessorBase
{
    protected function useSimpleviewLayout()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_Events_Multilayout_Processor:use-simpleview-layout',
            false
        );
    }

    public function addLayoutComponents(&$layouts, $handle, $format = '')
    {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $event_post_type = $eventTypeAPI->getEventCustomPostType();
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var RelationalTypeResolverInterface */
        $eventObjectTypeResolver = $instanceManager->getInstance(EventObjectTypeResolver::class);
        $field = $fieldQueryInterpreter->getField(
            'isObjectType',
            [
                'type' => $eventObjectTypeResolver->getTypeName(),
            ]
        );

        // Only if this post type is shown in All Content
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (in_array($event_post_type, $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            switch ($handle) {
                case POP_MULTILAYOUT_HANDLE_POSTCONTENT:
                case POP_MULTILAYOUT_HANDLE_AUTHORPOSTCONTENT:
                case POP_MULTILAYOUT_HANDLE_SINGLEPOSTCONTENT:
                    $pasts = $defaults = array();
                    if ($handle == POP_MULTILAYOUT_HANDLE_POSTCONTENT) {
                        $pasts = array(
                            POP_FORMAT_NAVIGATOR => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR],
                            POP_FORMAT_ADDONS => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS],
                            POP_FORMAT_DETAILS => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS],
                            POP_FORMAT_THUMBNAIL => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL],
                            POP_FORMAT_LIST => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_LIST],
                            POP_FORMAT_FULLVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_PASTEVENT],
                        );
                        $defaults = array( // <= Future and Current Events
                            POP_FORMAT_NAVIGATOR => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR],
                            POP_FORMAT_ADDONS => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_ADDONS],
                            POP_FORMAT_DETAILS => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS],
                            POP_FORMAT_THUMBNAIL => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL],
                            POP_FORMAT_LIST => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_LIST],
                            POP_FORMAT_FULLVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_EVENT],
                        );

                        // These layouts are needed only if using the Event SimpleView layout. Otherwise, "abovecontent" layout from the default post layout will be used (through handle POP_MULTILAYOUT_HANDLE_POSTABOVECONTENT)
                        if ($this->useSimpleviewLayout()) {
                            $defaults[POP_FORMAT_SIMPLEVIEW] = [GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, GD_EM_Module_Processor_CustomSimpleViewPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_SIMPLEVIEW];
                        }
                    } elseif ($handle == POP_MULTILAYOUT_HANDLE_AUTHORPOSTCONTENT) {
                        $pasts = array(
                            POP_FORMAT_FULLVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_PASTEVENT],
                        );
                        $defaults = array( // <= Future and Current Events
                            POP_FORMAT_FULLVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_EVENT],
                        );
                    } elseif ($handle == POP_MULTILAYOUT_HANDLE_SINGLEPOSTCONTENT) {
                        $pasts = array(
                            POP_FORMAT_FULLVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_PASTEVENT],
                        );
                        $defaults = array( // <= Future and Current Events
                            POP_FORMAT_FULLVIEW => [GD_EM_Module_Processor_CustomFullViewLayouts::class, GD_EM_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_EVENT],
                        );
                    }

                    // TODO: Split past/non-past on a level below, using the conditionalOnDataFieldSubmodule
                    // Temporarily commented (code `$event_post_type.'-'.Scopes::PAST` belongs to the old way of doing things, doesn't work anymore)
                    // if ($layout = $pasts[$format] ?? null) {
                    //     $layouts[$event_post_type.'-'.Scopes::PAST] = $layout;
                    // }
                    if ($layout = $defaults[$format] ?? null) {
                        $layouts[$field] = $layout;
                    }
                    break;

                case POP_MULTILAYOUT_HANDLE_POSTABOVECONTENT:
                    $layouts[$field] = [GD_EM_Module_Processor_EventMultipleComponents::class, GD_EM_Module_Processor_EventMultipleComponents::COMPONENT_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS];
                    break;
            }
        }
    }
}

/**
 * Initialization
 */
new PoP_Events_Multilayout_Processor();
