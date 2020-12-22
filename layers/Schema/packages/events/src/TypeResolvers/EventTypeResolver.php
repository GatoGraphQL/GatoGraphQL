<?php

declare(strict_types=1);

namespace PoPSchema\Events\TypeResolvers;

// use PoPSchema\Events\Facades\EventTypeAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Events\TypeDataLoaders\EventTypeDataLoader;
use PoPSchema\CustomPosts\TypeResolvers\AbstractCustomPostTypeResolver;

class EventTypeResolver extends AbstractCustomPostTypeResolver
{
    public const NAME = 'Event';

    public function getTypeName(): string
    {
        return self::NAME;
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of an event', 'events');
    }

    // public function getID(object $resultItem)
    // {
    //     $eventTypeAPI = EventTypeAPIFacade::getInstance();
    //     return $eventTypeAPI->getID($resultItem);
    // }

    public function getTypeDataLoaderClass(): string
    {
        return EventTypeDataLoader::class;
    }
}
