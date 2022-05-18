<?php

class PoP_Module_Processor_CustomDelegatorFilters extends PoP_Module_Processor_CustomDelegatorFiltersBase
{
    public final const COMPONENT_DELEGATORFILTER_AUTHORCATEGORYPOSTS = 'delegatorfilter-authorcategoryposts';
    public final const COMPONENT_DELEGATORFILTER_AUTHORMAINCONTENT = 'delegatorfilter-authormaincontent';
    public final const COMPONENT_DELEGATORFILTER_AUTHORPOSTS = 'delegatorfilter-authorposts';
    public final const COMPONENT_DELEGATORFILTER_AUTHORCONTENT = 'delegatorfilter-authorcontent';
    public final const COMPONENT_DELEGATORFILTER_AUTHORCOMMUNITYMEMBERS = 'delegatorfilter-authorcommunitymembers';
    public final const COMPONENT_DELEGATORFILTER_CATEGORYPOSTS = 'delegatorfilter-categoryposts';
    public final const COMPONENT_DELEGATORFILTER_HOMECONTENT = 'delegatorfilter-homecontent';
    public final const COMPONENT_DELEGATORFILTER_MYCATEGORYPOSTS = 'delegatorfilter-mycategoryposts';
    public final const COMPONENT_DELEGATORFILTER_MYPOSTS = 'delegatorfilter-myposts';
    public final const COMPONENT_DELEGATORFILTER_POSTS = 'delegatorfilter-posts';
    public final const COMPONENT_DELEGATORFILTER_TAGCATEGORYPOSTS = 'delegatorfilter-tagcategoryposts';
    public final const COMPONENT_DELEGATORFILTER_TAGCONTENT = 'delegatorfilter-tagcontent';
    public final const COMPONENT_DELEGATORFILTER_TAGMAINCONTENT = 'delegatorfilter-tagmaincontent';
    public final const COMPONENT_DELEGATORFILTER_TAGPOSTS = 'delegatorfilter-tagposts';
    public final const COMPONENT_DELEGATORFILTER_MYCONTENT = 'delegatorfilter-mycontent';
    public final const COMPONENT_DELEGATORFILTER_CONTENT = 'delegatorfilter-content';
    public final const COMPONENT_DELEGATORFILTER_TAGS = 'delegatorfilter-tags';
    public final const COMPONENT_DELEGATORFILTER_USERS = 'delegatorfilter-users';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DELEGATORFILTER_TAGS],
            [self::class, self::COMPONENT_DELEGATORFILTER_CONTENT],
            [self::class, self::COMPONENT_DELEGATORFILTER_AUTHORCONTENT],
            [self::class, self::COMPONENT_DELEGATORFILTER_POSTS],
            [self::class, self::COMPONENT_DELEGATORFILTER_CATEGORYPOSTS],
            [self::class, self::COMPONENT_DELEGATORFILTER_AUTHORPOSTS],
            [self::class, self::COMPONENT_DELEGATORFILTER_AUTHORCATEGORYPOSTS],
            [self::class, self::COMPONENT_DELEGATORFILTER_TAGPOSTS],
            [self::class, self::COMPONENT_DELEGATORFILTER_TAGCATEGORYPOSTS],
            [self::class, self::COMPONENT_DELEGATORFILTER_USERS],
            [self::class, self::COMPONENT_DELEGATORFILTER_AUTHORCOMMUNITYMEMBERS],
            [self::class, self::COMPONENT_DELEGATORFILTER_MYCONTENT],
            [self::class, self::COMPONENT_DELEGATORFILTER_MYPOSTS],
            [self::class, self::COMPONENT_DELEGATORFILTER_MYCATEGORYPOSTS],
            [self::class, self::COMPONENT_DELEGATORFILTER_TAGMAINCONTENT],
            [self::class, self::COMPONENT_DELEGATORFILTER_TAGCONTENT],
            [self::class, self::COMPONENT_DELEGATORFILTER_HOMECONTENT],
            [self::class, self::COMPONENT_DELEGATORFILTER_AUTHORMAINCONTENT],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_DELEGATORFILTER_TAGS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGS],
            self::COMPONENT_DELEGATORFILTER_CONTENT => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_CONTENT],
            self::COMPONENT_DELEGATORFILTER_AUTHORCONTENT => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORCONTENT],
            self::COMPONENT_DELEGATORFILTER_POSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_POSTS],
            self::COMPONENT_DELEGATORFILTER_CATEGORYPOSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_CATEGORYPOSTS],
            self::COMPONENT_DELEGATORFILTER_AUTHORPOSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORPOSTS],
            self::COMPONENT_DELEGATORFILTER_AUTHORCATEGORYPOSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS],
            self::COMPONENT_DELEGATORFILTER_TAGPOSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGPOSTS],
            self::COMPONENT_DELEGATORFILTER_TAGCATEGORYPOSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGCATEGORYPOSTS],
            self::COMPONENT_DELEGATORFILTER_USERS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_USERS],
            self::COMPONENT_DELEGATORFILTER_AUTHORCOMMUNITYMEMBERS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORCOMMUNITYMEMBERS],
            self::COMPONENT_DELEGATORFILTER_MYCONTENT => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYCONTENT],
            self::COMPONENT_DELEGATORFILTER_MYPOSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYPOSTS],
            self::COMPONENT_DELEGATORFILTER_MYCATEGORYPOSTS => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYCATEGORYPOSTS],
            self::COMPONENT_DELEGATORFILTER_TAGMAINCONTENT => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGCONTENT],
            self::COMPONENT_DELEGATORFILTER_TAGCONTENT => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGCONTENT],
            self::COMPONENT_DELEGATORFILTER_HOMECONTENT => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_CONTENT],
            self::COMPONENT_DELEGATORFILTER_AUTHORMAINCONTENT => [PoP_Module_Processor_CustomSimpleFilterInners::class, PoP_Module_Processor_CustomSimpleFilterInners::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORCONTENT],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function getBlockTarget(array $component, array &$props)
    {

        // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
        // Comment Leo 12/01/2017: Actually, for the forms we must use .active instead of :last-child, because the selector is executed
        // on runtime, and not when initializing the JS
        switch ($component[1]) {
         // Because the Home has a different structure (blockgroup_home => block with content) then must change the block target
            case self::COMPONENT_DELEGATORFILTER_HOMECONTENT:
                return '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel.active > .blockgroup-home > .blocksection-extensions > .pop-block.withfilter';

         // Because the Home has a different structure (blockgroup_home => block with content) then must change the block target
            case self::COMPONENT_DELEGATORFILTER_AUTHORMAINCONTENT:
                return '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel.active > .blockgroup-author > .blocksection-extensions > .pop-block.withfilter';

            case self::COMPONENT_DELEGATORFILTER_TAGMAINCONTENT:
                return '#'.POP_MODULEID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel.active > .blockgroup-tag > .blocksection-extensions > .pop-block.withfilter';
        }

        return parent::getBlockTarget($component, $props);
    }
}



