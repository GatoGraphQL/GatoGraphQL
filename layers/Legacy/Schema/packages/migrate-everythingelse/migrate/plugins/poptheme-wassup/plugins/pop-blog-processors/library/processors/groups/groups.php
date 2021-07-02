<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class PoPTheme_Wassup_Blog_Module_Processor_Groups extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_GROUP_TAGCONTENT_SCROLL_DETAILS = 'group-tagcontent-scroll-details';
    public const MODULE_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW = 'group-tagcontent-scroll-simpleview';
    public const MODULE_GROUP_TAGCONTENT_SCROLL_FULLVIEW = 'group-tagcontent-scroll-fullview';
    public const MODULE_GROUP_TAGCONTENT_SCROLL_THUMBNAIL = 'group-tagcontent-scroll-thumbnail';
    public const MODULE_GROUP_TAGCONTENT_SCROLL_LIST = 'group-tagcontent-scroll-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GROUP_TAGCONTENT_SCROLL_DETAILS],
            [self::class, self::MODULE_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_GROUP_TAGCONTENT_SCROLL_FULLVIEW],
            [self::class, self::MODULE_GROUP_TAGCONTENT_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_GROUP_TAGCONTENT_SCROLL_LIST],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_DETAILS:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_LIST:
                $inners = array(
                    self::MODULE_GROUP_TAGCONTENT_SCROLL_DETAILS => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGCONTENT_SCROLL_DETAILS],
                    self::MODULE_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGCONTENT_SCROLL_SIMPLEVIEW],
                    self::MODULE_GROUP_TAGCONTENT_SCROLL_FULLVIEW => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGCONTENT_SCROLL_FULLVIEW],
                    self::MODULE_GROUP_TAGCONTENT_SCROLL_THUMBNAIL => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGCONTENT_SCROLL_THUMBNAIL],
                    self::MODULE_GROUP_TAGCONTENT_SCROLL_LIST => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGCONTENT_SCROLL_LIST],
                );
                return $inners[$module[1]];
        }

        return null;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_DETAILS:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_LIST:
                $submodule = $this->getInnerSubmodule($module);
                $this->setProp([$submodule], $props, 'title-htmltag', 'h2');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function initRequestProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_DETAILS:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_LIST:
                $submodule = $this->getInnerSubmodule($module);

                // Change the block title from the #hashtag to Latest, because this blockgroup will assume that name
                $title = getRouteIcon(POP_BLOG_ROUTE_CONTENT, true).TranslationAPIFacade::getInstance()->__('Latest content', 'poptheme-wassup');
                $this->setProp([$submodule], $props, 'title', $title);
                break;
        }

        parent::initRequestProps($module, $props);
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_DETAILS:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_FULLVIEW:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_THUMBNAIL:
            case self::MODULE_GROUP_TAGCONTENT_SCROLL_LIST:
                $ret[] = $this->getInnerSubmodule($module);
                break;
        }

        return $ret;
    }
}


