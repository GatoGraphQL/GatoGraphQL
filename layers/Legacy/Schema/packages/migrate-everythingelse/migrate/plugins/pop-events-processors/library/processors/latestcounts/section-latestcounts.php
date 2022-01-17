<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchemaPRO\Events\Constants\Scopes;
use PoPCMSSchemaPRO\Events\Facades\EventTypeAPIFacade;

class GD_EM_Module_Processor_SectionLatestCounts extends PoP_Module_Processor_SectionLatestCountsBase
{
    public const MODULE_LATESTCOUNT_EVENTS = 'latestcount-events';
    public const MODULE_LATESTCOUNT_AUTHOR_EVENTS = 'latestcount-author-events';
    public const MODULE_LATESTCOUNT_TAG_EVENTS = 'latestcount-tag-events';
    public const MODULE_LATESTCOUNT_PASTEVENTS = 'latestcount-pastevents';
    public const MODULE_LATESTCOUNT_AUTHOR_PASTEVENTS = 'latestcount-author-pastevents';
    public const MODULE_LATESTCOUNT_TAG_PASTEVENTS = 'latestcount-tag-pastevents';

    public function getModulesToProcess(): array
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

    public function getObjectName(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LATESTCOUNT_EVENTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_EVENTS:
            case self::MODULE_LATESTCOUNT_TAG_EVENTS:
                return TranslationAPIFacade::getInstance()->__('event', 'poptheme-wassup');

            case self::MODULE_LATESTCOUNT_PASTEVENTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_PASTEVENTS:
            case self::MODULE_LATESTCOUNT_TAG_PASTEVENTS:
                return TranslationAPIFacade::getInstance()->__('past event', 'poptheme-wassup');
        }

        return parent::getObjectName($module, $props);
    }

    public function getObjectNames(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LATESTCOUNT_EVENTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_EVENTS:
            case self::MODULE_LATESTCOUNT_TAG_EVENTS:
                return TranslationAPIFacade::getInstance()->__('events', 'poptheme-wassup');

            case self::MODULE_LATESTCOUNT_PASTEVENTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_PASTEVENTS:
            case self::MODULE_LATESTCOUNT_TAG_PASTEVENTS:
                return TranslationAPIFacade::getInstance()->__('past events', 'poptheme-wassup');
        }

        return parent::getObjectNames($module, $props);
    }

    public function getSectionClasses(array $module, array &$props)
    {
        $ret = parent::getSectionClasses($module, $props);

        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $event_post_type = $eventTypeAPI->getEventCustomPostType();
        switch ($module[1]) {
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

    public function isAuthor(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LATESTCOUNT_AUTHOR_EVENTS:
            case self::MODULE_LATESTCOUNT_AUTHOR_PASTEVENTS:
                return true;
        }

        return parent::isAuthor($module, $props);
    }

    public function isTag(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LATESTCOUNT_TAG_EVENTS:
            case self::MODULE_LATESTCOUNT_TAG_PASTEVENTS:
                return true;
        }

        return parent::isTag($module, $props);
    }
}


