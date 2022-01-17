<?php
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchemaPRO\Events\Constants\Scopes;
use PoPCMSSchemaPRO\Events\Facades\EventTypeAPIFacade;
use PoPCMSSchemaPRO\Events\TypeResolvers\ObjectType\EventObjectTypeResolver;

class PoP_EventsCreation_Multilayout_Processor extends PoP_Application_Multilayout_ProcessorBase
{
    public function addLayoutModules(&$layouts, $handle, $format = '')
    {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $event_post_type = $eventTypeAPI->getEventCustomPostType();

        // Only if this post type is shown in All Content
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (in_array($event_post_type, $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            switch ($handle) {
                case POP_MULTILAYOUT_HANDLE_POSTCONTENT:
                    $pasts = array(
                        POP_FORMAT_TABLE => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT],
                    );
                    $defaults = array( // <= Future and Current Events
                        POP_FORMAT_TABLE => [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_EDIT],
                    );

                    // TODO: Split past/non-past on a level below, using the conditionalOnDataFieldSubmodule
                    // Temporarily commented (code `$event_post_type.'-'.Scopes::PAST` belongs to the old way of doing things, doesn't work anymore)
                    // if ($layout = $pasts[$format] ?? null) {
                    //     $layouts[$event_post_type.'-'.Scopes::PAST] = $layout;
                    // }
                    if ($layout = $defaults[$format] ?? null) {
                        $instanceManager = InstanceManagerFacade::getInstance();
                        /** @var RelationalTypeResolverInterface */
                        $eventObjectTypeResolver = $instanceManager->getInstance(EventObjectTypeResolver::class);
                        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
                        $field = $fieldQueryInterpreter->getField(
                            'isType',
                            [
                                'type' => $eventObjectTypeResolver->getTypeName(),
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
new PoP_EventsCreation_Multilayout_Processor();
