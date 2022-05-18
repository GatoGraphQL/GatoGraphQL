<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Events\Constants\Scopes;
use PoPCMSSchema\Events\Facades\EventTypeAPIFacade;

class GD_EM_Module_Processor_SectionLatestCounts extends PoP_Module_Processor_SectionLatestCountsBase
{
    public final const MODULE_LATESTCOUNT_EVENTS = 'latestcount-events';
    public final const MODULE_LATESTCOUNT_AUTHOR_EVENTS = 'latestcount-author-events';
    public final const MODULE_LATESTCOUNT_TAG_EVENTS = 'latestcount-tag-events';
    public final const MODULE_LATESTCOUNT_PASTEVENTS = 'latestcount-pastevents';
    public final const MODULE_LATESTCOUNT_AUTHOR_PASTEVENTS = 'latestcount-author-pastevents';
    public final const MODULE_LATESTCOUNT_TAG_PASTEVENTS = 'latestcount-tag-pastevents';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LATESTCOUNT_EVENTS],
            [self::class, self::MODULE_LATESTCOUNT_AUTHOR_EVENTS],
            [self::class, self::MODULE_LATESTCOUNT_TAG_EVENTS],
            [self::class, self::MODULE_LATESTCOUNT_PASTEVENTS],
            [self::class, self::MODULE_LATESTCOUNT_AUTHOR_PASTEVENTS],
            [self::class, self::MODULE_LATESTCOUNT_TAG_PASTEVENTS],
        );
    }

    public function getObjectName(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_LATESTCOUNT_EVENTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_EVENTS:
            case self::MODULE_LATESTCOUNT_TAG_EVENTS:
                return TranslationAPIFacade::getInstance()->__('event', 'poptheme-wassup');

            case self::MODULE_LATESTCOUNT_PASTEVENTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_PASTEVENTS:
            case self::MODULE_LATESTCOUNT_TAG_PASTEVENTS:
                return TranslationAPIFacade::getInstance()->__('past event', 'poptheme-wassup');
        }

        return parent::getObjectName($component, $props);
    }

    public function getObjectNames(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_LATESTCOUNT_EVENTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_EVENTS:
            case self::MODULE_LATESTCOUNT_TAG_EVENTS:
                return TranslationAPIFacade::getInstance()->__('events', 'poptheme-wassup');

            case self::MODULE_LATESTCOUNT_PASTEVENTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_PASTEVENTS:
            case self::MODULE_LATESTCOUNT_TAG_PASTEVENTS:
                return TranslationAPIFacade::getInstance()->__('past events', 'poptheme-wassup');
        }

        return parent::getObjectNames($component, $props);
    }

    public function getSectionClasses(array $component, array &$props)
    {
        $ret = parent::getSectionClasses($component, $props);

        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $event_post_type = $eventTypeAPI->getEventCustomPostType();
        switch ($component[1]) {
            case self::MODULE_LATESTCOUNT_EVENTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_EVENTS:
            case self::MODULE_LATESTCOUNT_TAG_EVENTS:
                $ret[] = $event_post_type . '-' . Scopes::FUTURE;
                $ret[] = $event_post_type . '-' . Scopes::CURRENT;
                break;

            case self::MODULE_LATESTCOUNT_PASTEVENTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_PASTEVENTS:
            case self::MODULE_LATESTCOUNT_TAG_PASTEVENTS:
                $ret[] = $event_post_type . '-' . Scopes::PAST;
                break;
        }

        return $ret;
    }

    public function isAuthor(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_LATESTCOUNT_AUTHOR_EVENTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_PASTEVENTS:
                return true;
        }

        return parent::isAuthor($component, $props);
    }

    public function isTag(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_LATESTCOUNT_TAG_EVENTS:
            case self::MODULE_LATESTCOUNT_TAG_PASTEVENTS:
                return true;
        }

        return parent::isTag($component, $props);
    }
}


