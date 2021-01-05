<?php

class PoP_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public const MODULE_DELEGATORFILTER_AUTHORCATEGORYPOSTS = 'delegatorfilter-authorcategoryposts';
    public const MODULE_DELEGATORFILTER_AUTHORMAINCONTENT = 'delegatorfilter-authormaincontent';
    public const MODULE_DELEGATORFILTER_AUTHORPOSTS = 'delegatorfilter-authorposts';
    public const MODULE_DELEGATORFILTER_AUTHORCONTENT = 'delegatorfilter-authorcontent';
    public const MODULE_DELEGATORFILTER_AUTHORCOMMUNITYMEMBERS = 'delegatorfilter-authorcommunitymembers';
    public const MODULE_DELEGATORFILTER_CATEGORYPOSTS = 'delegatorfilter-categoryposts';
    public const MODULE_DELEGATORFILTER_HOMECONTENT = 'delegatorfilter-homecontent';
    public const MODULE_DELEGATORFILTER_MYCATEGORYPOSTS = 'delegatorfilter-mycategoryposts';
    public const MODULE_DELEGATORFILTER_MYPOSTS = 'delegatorfilter-myposts';
    public const MODULE_DELEGATORFILTER_POSTS = 'delegatorfilter-posts';
    public const MODULE_DELEGATORFILTER_TAGCATEGORYPOSTS = 'delegatorfilter-tagcategoryposts';
    public const MODULE_DELEGATORFILTER_TAGCONTENT = 'delegatorfilter-tagcontent';
    public const MODULE_DELEGATORFILTER_TAGMAINCONTENT = 'delegatorfilter-tagmaincontent';
    public const MODULE_DELEGATORFILTER_TAGPOSTS = 'delegatorfilter-tagposts';
    public const MODULE_DELEGATORFILTER_MYCONTENT = 'delegatorfilter-mycontent';
    public const MODULE_DELEGATORFILTER_CONTENT = 'delegatorfilter-content';
    public const MODULE_DELEGATORFILTER_TAGS = 'delegatorfilter-tags';
    public const MODULE_DELEGATORFILTER_USERS = 'delegatorfilter-users';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DELEGATORFILTER_TAGS],
            [self::class, self::MODULE_DELEGATORFILTER_CONTENT],
            [self::class, self::MODULE_DELEGATORFILTER_AUTHORCONTENT],
            [self::class, self::MODULE_DELEGATORFILTER_POSTS],
            [self::class, self::MODULE_DELEGATORFILTER_CATEGORYPOSTS],
            [self::class, self::MODULE_DELEGATORFILTER_AUTHORPOSTS],
            [self::class, self::MODULE_DELEGATORFILTER_AUTHORCATEGORYPOSTS],
            [self::class, self::MODULE_DELEGATORFILTER_TAGPOSTS],
            [self::class, self::MODULE_DELEGATORFILTER_TAGCATEGORYPOSTS],
            [self::class, self::MODULE_DELEGATORFILTER_USERS],
            [self::class, self::MODULE_DELEGATORFILTER_AUTHORCOMMUNITYMEMBERS],
            [self::class, self::MODULE_DELEGATORFILTER_MYCONTENT],
            [self::class, self::MODULE_DELEGATORFILTER_MYPOSTS],
            [self::class, self::MODULE_DELEGATORFILTER_MYCATEGORYPOSTS],
            [self::class, self::MODULE_DELEGATORFILTER_TAGMAINCONTENT],
            [self::class, self::MODULE_DELEGATORFILTER_TAGCONTENT],
            [self::class, self::MODULE_DELEGATORFILTER_HOMECONTENT],
            [self::class, self::MODULE_DELEGATORFILTER_AUTHORMAINCONTENT],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_DELEGATORFILTER_TAGS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_TAGS],
            self::MODULE_DELEGATORFILTER_CONTENT => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_CONTENT],
            self::MODULE_DELEGATORFILTER_AUTHORCONTENT => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_AUTHORCONTENT],
            self::MODULE_DELEGATORFILTER_POSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_POSTS],
            self::MODULE_DELEGATORFILTER_CATEGORYPOSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_CATEGORYPOSTS],
            self::MODULE_DELEGATORFILTER_AUTHORPOSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_AUTHORPOSTS],
            self::MODULE_DELEGATORFILTER_AUTHORCATEGORYPOSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_AUTHORCATEGORYPOSTS],
            self::MODULE_DELEGATORFILTER_TAGPOSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_TAGPOSTS],
            self::MODULE_DELEGATORFILTER_TAGCATEGORYPOSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_TAGCATEGORYPOSTS],
            self::MODULE_DELEGATORFILTER_USERS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_USERS],
            self::MODULE_DELEGATORFILTER_AUTHORCOMMUNITYMEMBERS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_AUTHORCOMMUNITYMEMBERS],
            self::MODULE_DELEGATORFILTER_MYCONTENT => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_MYCONTENT],
            self::MODULE_DELEGATORFILTER_MYPOSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_MYPOSTS],
            self::MODULE_DELEGATORFILTER_MYCATEGORYPOSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_MYCATEGORYPOSTS],
            self::MODULE_DELEGATORFILTER_TAGMAINCONTENT => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_TAGCONTENT],
            self::MODULE_DELEGATORFILTER_TAGCONTENT => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_TAGCONTENT],
            self::MODULE_DELEGATORFILTER_HOMECONTENT => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_CONTENT],
            self::MODULE_DELEGATORFILTER_AUTHORMAINCONTENT => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::MODULE_SIMPLEFILTERINNER_AUTHORCONTENT],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function getBlockTarget(array $module, array &$props)
    {

        // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
        // Comment Leo 12/01/2017: Actually, for the forms we must use .active instead of :last-child, because the selector is executed
        // on runtime, and not when initializing the JS
        switch ($module[1]) {
         // Because the Home has a different structure (blockgroup_home => block with content) then must change the block target
            case self::MODULE_DELEGATORFILTER_HOMECONTENT:
                return '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel.active > .blockgroup-home > .blocksection-extensions > .pop-block.withfilter';

         // Because the Home has a different structure (blockgroup_home => block with content) then must change the block target
            case self::MODULE_DELEGATORFILTER_AUTHORMAINCONTENT:
                return '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel.active > .blockgroup-author > .blocksection-extensions > .pop-block.withfilter';

            case self::MODULE_DELEGATORFILTER_TAGMAINCONTENT:
                return '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel.active > .blockgroup-tag > .blocksection-extensions > .pop-block.withfilter';
        }

        return parent::getBlockTarget($module, $props);
    }
}



