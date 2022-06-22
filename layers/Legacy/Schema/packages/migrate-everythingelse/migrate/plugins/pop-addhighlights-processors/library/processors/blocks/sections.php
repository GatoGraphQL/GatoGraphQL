<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddHighlights_Module_Processor_CustomSectionBlocks extends PoP_Module_Processor_SectionBlocksBase
{
    public final const COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW = 'block-authorhighlights-scroll-fullview';
    public final const COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_LIST = 'block-authorhighlights-scroll-list';
    public final const COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL = 'block-authorhighlights-scroll-thumbnail';
    public final const COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_ADDONS = 'block-highlights-scroll-addons';
    public final const COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_FULLVIEW = 'block-highlights-scroll-fullview';
    public final const COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_LIST = 'block-highlights-scroll-list';
    public final const COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_NAVIGATOR = 'block-highlights-scroll-navigator';
    public final const COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_THUMBNAIL = 'block-highlights-scroll-thumbnail';
    public final const COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW = 'block-singlerelatedhighlightcontent-scroll-fullview';
    public final const COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL = 'block-singlerelatedhighlightcontent-scroll-thumbnail';
    public final const COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST = 'block-singlerelatedhighlightcontent-scroll-list';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_NAVIGATOR,
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_ADDONS,
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_LIST,
            self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_LIST,
            self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW,
            self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL,
            self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_LIST => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_ADDONS => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_FULLVIEW => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_LIST => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_THUMBNAIL => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_NAVIGATOR => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL => POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_NAVIGATOR => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_NAVIGATOR],
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_ADDONS => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_ADDONS],
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_FULLVIEW => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_THUMBNAIL => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_LIST => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_HIGHLIGHTS_SCROLL_LIST],
            self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_LIST => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORHIGHLIGHTS_SCROLL_LIST],
            self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW],
            self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL],
            self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST => [PoP_AddHighlights_Module_Processor_CustomSectionDataloads::class, PoP_AddHighlights_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST],
        );

        return $inner_components[$component->name] ?? null;
    }

    protected function getControlgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKAUTHORPOSTLIST];

            case self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_LIST:
            case self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKPOSTLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_NAVIGATOR:
            case self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_ADDONS:
            case self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_HIGHLIGHTS_SCROLL_LIST:
            case self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_AUTHORHIGHLIGHTS_SCROLL_LIST:
            case self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_BLOCK_SINGLERELATEDHIGHLIGHTCONTENT_SCROLL_LIST:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('highlights', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



