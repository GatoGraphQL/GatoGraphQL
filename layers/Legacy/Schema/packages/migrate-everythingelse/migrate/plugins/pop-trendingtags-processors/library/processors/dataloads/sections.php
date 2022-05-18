<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_TrendingTags_Module_Processor_SectionDataloads extends Abstract_PoP_TrendingTags_Module_Processor_SectionDataloads
{
    public final const MODULE_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS = 'dataload-trendingtags-scroll-details';
    public final const MODULE_DATALOAD_TRENDINGTAGS_SCROLL_LIST = 'dataload-trendingtags-scroll-list';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TRENDINGTAGS_SCROLL_LIST],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS => POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS,
            self::MODULE_DATALOAD_TRENDINGTAGS_SCROLL_LIST => POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_componentVariations = array(
            self::MODULE_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_TAGS_DETAILS],
            self::MODULE_DATALOAD_TRENDINGTAGS_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_TAGS_LIST],
        );

        return $inner_componentVariations[$componentVariation[1]] ?? null;
    }

    public function getFormat(array $componentVariation): ?string
    {
        // Add the format attr
        $details = array(
            [self::class, self::MODULE_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_TRENDINGTAGS_SCROLL_LIST],
        );
        if (in_array($componentVariation, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($componentVariation, $lists)) {
            $format = POP_FORMAT_LIST;
        }

        return $format ?? parent::getFormat($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_TRENDINGTAGS_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TRENDINGTAGS_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('tags', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



