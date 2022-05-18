<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Events\TypeResolvers\ObjectType\EventObjectTypeResolver;

class PoPTheme_Wassup_EM_AE_Module_Processor_SectionDataloads extends PoP_CommonAutomatedEmails_Module_Processor_SectionDataloadsBase
{
    public final const MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS = 'dataload-automatedemails-events-scroll-details';
    public final const MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW = 'dataload-automatedemails-events-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW = 'dataload-automatedemails-events-scroll-fullview';
    public final const MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL = 'dataload-automatedemails-events-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST = 'dataload-automatedemails-events-scroll-list';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getInnerSubmodule(array $module)
    {
        $inner_modules = array(

            self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_DETAILS],
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_LIST],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::MODULE_FILTER_EVENTS];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {
        $details = array(
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS],
        );
        $simpleviews = array(
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST],
        );
        if (in_array($module, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($module, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        } elseif (in_array($module, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($module, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($module, $lists)) {
            $format = POP_FORMAT_LIST;
        }

        return $format ?? parent::getFormat($module);
    }

    protected function getImmutableDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST:
                // 1 week of events
                $ret['scope'] = 'week';
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST:
                return $this->instanceManager->getInstance(EventObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('events', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}


