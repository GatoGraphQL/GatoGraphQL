<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Events\TypeResolvers\ObjectType\EventObjectTypeResolver;

class PoPTheme_Wassup_EM_AE_Module_Processor_SectionDataloads extends PoP_CommonAutomatedEmails_Module_Processor_SectionDataloadsBase
{
    public final const COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS = 'dataload-automatedemails-events-scroll-details';
    public final const COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW = 'dataload-automatedemails-events-scroll-simpleview';
    public final const COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW = 'dataload-automatedemails-events-scroll-fullview';
    public final const COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL = 'dataload-automatedemails-events-scroll-thumbnail';
    public final const COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST = 'dataload-automatedemails-events-scroll-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS],
            [self::class, self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW],
            [self::class, self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW],
            [self::class, self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL],
            [self::class, self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubmodule(array $component)
    {
        $inner_components = array(

            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_EVENTS_DETAILS],
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW],
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_EVENTS_FULLVIEW],
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_EVENTS_THUMBNAIL],
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_EVENTS_LIST],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getFilterSubmodule(array $component): ?array
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::COMPONENT_FILTER_EVENTS];
        }

        return parent::getFilterSubmodule($component);
    }

    public function getFormat(array $component): ?string
    {
        $details = array(
            [self::class, self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS],
        );
        $simpleviews = array(
            [self::class, self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST],
        );
        if (in_array($component, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($component, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        } elseif (in_array($component, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($component, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($component, $lists)) {
            $format = POP_FORMAT_LIST;
        }

        return $format ?? parent::getFormat($component);
    }

    protected function getImmutableDataloadQueryArgs(array $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST:
                // 1 week of events
                $ret['scope'] = 'week';
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST:
                return $this->instanceManager->getInstance(EventObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('events', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}


