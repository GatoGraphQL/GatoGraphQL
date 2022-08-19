<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_TrendingTags_Module_Processor_SectionDataloads extends Abstract_PoP_TrendingTags_Module_Processor_SectionDataloads
{
    public final const COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS = 'dataload-trendingtags-scroll-details';
    public final const COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_LIST = 'dataload-trendingtags-scroll-list';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS,
            self::COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_LIST,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS => POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS,
            self::COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_LIST => POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_TAGS_DETAILS],
            self::COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::COMPONENT_SCROLL_TAGS_LIST],
        );

        return $inner_components[$component->name] ?? null;
    }

    public function getFormat(\PoP\ComponentModel\Component\Component $component): ?string
    {
        // Add the format attr
        $details = array(
            self::COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS,
        );
        $lists = array(
            self::COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_LIST,
        );
        if (in_array($component, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($component, $lists)) {
            $format = POP_FORMAT_LIST;
        }

        return $format ?? parent::getFormat($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS:
            case self::COMPONENT_DATALOAD_TRENDINGTAGS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('tags', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



