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

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL => POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(

            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_EVENTS_DETAILS],
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW],
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_EVENTS_FULLVIEW],
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_EVENTS_THUMBNAIL],
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST => [PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::class, PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls::COMPONENT_SCROLL_AUTOMATEDEMAILS_EVENTS_LIST],
        );

        return $inner_components[$component->name] ?? null;
    }

    public function getFilterSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST:
                return [PoP_Events_Module_Processor_CustomFilters::class, PoP_Events_Module_Processor_CustomFilters::COMPONENT_FILTER_EVENTS];
        }

        return parent::getFilterSubcomponent($component);
    }

    public function getFormat(\PoP\ComponentModel\Component\Component $component): ?string
    {
        $details = array(
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS,
        );
        $simpleviews = array(
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW,
        );
        $fullviews = array(
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW,
        );
        $thumbnails = array(
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL,
        );
        $lists = array(
            self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST,
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

    protected function getImmutableDataloadQueryArgs(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($component, $props);

        switch ($component->name) {
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

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_FULLVIEW:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_DATALOAD_AUTOMATEDEMAILS_EVENTS_SCROLL_LIST:
                return $this->instanceManager->getInstance(EventObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
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


