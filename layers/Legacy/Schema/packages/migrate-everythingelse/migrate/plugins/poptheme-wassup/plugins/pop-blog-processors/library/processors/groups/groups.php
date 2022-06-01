<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoPTheme_Wassup_Blog_Module_Processor_Groups extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_GROUP_TAGCONTENT_SCROLL_DETAILS = 'group-tagcontent-scroll-details';
    public final const COMPONENT_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW = 'group-tagcontent-scroll-simpleview';
    public final const COMPONENT_GROUP_TAGCONTENT_SCROLL_FULLVIEW = 'group-tagcontent-scroll-fullview';
    public final const COMPONENT_GROUP_TAGCONTENT_SCROLL_THUMBNAIL = 'group-tagcontent-scroll-thumbnail';
    public final const COMPONENT_GROUP_TAGCONTENT_SCROLL_LIST = 'group-tagcontent-scroll-list';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_GROUP_TAGCONTENT_SCROLL_DETAILS,
            self::COMPONENT_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW,
            self::COMPONENT_GROUP_TAGCONTENT_SCROLL_FULLVIEW,
            self::COMPONENT_GROUP_TAGCONTENT_SCROLL_THUMBNAIL,
            self::COMPONENT_GROUP_TAGCONTENT_SCROLL_LIST,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_DETAILS:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_LIST:
                $inners = array(
                    self::COMPONENT_GROUP_TAGCONTENT_SCROLL_DETAILS => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGCONTENT_SCROLL_DETAILS],
                    self::COMPONENT_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGCONTENT_SCROLL_SIMPLEVIEW],
                    self::COMPONENT_GROUP_TAGCONTENT_SCROLL_FULLVIEW => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGCONTENT_SCROLL_FULLVIEW],
                    self::COMPONENT_GROUP_TAGCONTENT_SCROLL_THUMBNAIL => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGCONTENT_SCROLL_THUMBNAIL],
                    self::COMPONENT_GROUP_TAGCONTENT_SCROLL_LIST => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGCONTENT_SCROLL_LIST],
                );
                return $inners[$component->name];
        }

        return null;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_DETAILS:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_LIST:
                $subcomponent = $this->getInnerSubcomponent($component);
                $this->setProp([$subcomponent], $props, 'title-htmltag', 'h2');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function initRequestProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_DETAILS:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_LIST:
                $subcomponent = $this->getInnerSubcomponent($component);

                // Change the block title from the #hashtag to Latest, because this blockgroup will assume that name
                $title = getRouteIcon(POP_BLOG_ROUTE_CONTENT, true).TranslationAPIFacade::getInstance()->__('Latest content', 'poptheme-wassup');
                $this->setProp([$subcomponent], $props, 'title', $title);
                break;
        }

        parent::initRequestProps($component, $props);
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_DETAILS:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_FULLVIEW:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::COMPONENT_GROUP_TAGCONTENT_SCROLL_LIST:
                $ret[] = $this->getInnerSubcomponent($component);
                break;
        }

        return $ret;
    }
}


