<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPSchema\Events\TypeResolvers\EventTypeResolver;
use PoPSchema\Events\Facades\EventTypeAPIFacade;

class PoP_CommonAutomatedEmails_Events_Multilayout_Processor extends PoP_Application_Multilayout_ProcessorBase
{
    public function addLayoutModules(&$layouts, $handle, $format = '')
    {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $event_post_type = $eventTypeAPI->getEventCustomPostType();

        // Only if this post type is shown in All Content
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (in_array($event_post_type, $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            switch ($handle) {
                case POP_MULTILAYOUT_HANDLE_AUTOMATEDEMAILS_POSTCONTENT:
                    $event_layouts = array(
                        POP_FORMAT_DETAILS => [PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS],
                        POP_FORMAT_THUMBNAIL => [PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL],
                        POP_FORMAT_LIST => [PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST],
                        POP_FORMAT_SIMPLEVIEW => [PoPTheme_Wassup_EM_AE_Module_Processor_SimpleViewPreviewPostLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_SimpleViewPreviewPostLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_SIMPLEVIEW],
                        POP_FORMAT_FULLVIEW => [PoPTheme_Wassup_EM_AE_Module_Processor_FullViewLayouts::class, PoPTheme_Wassup_EM_AE_Module_Processor_FullViewLayouts::MODULE_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_EVENT],
                    );
                    if ($layout = $event_layouts[$format] ?? null) {
                        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
                        $field = $fieldQueryInterpreter->getField(
                            'isType',
                            [
                                'type' => EventTypeResolver::NAME,
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
new PoP_CommonAutomatedEmails_Events_Multilayout_Processor();
