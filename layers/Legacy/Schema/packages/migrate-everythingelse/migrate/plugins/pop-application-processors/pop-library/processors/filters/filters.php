<?php

class PoP_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public final const MODULE_FILTER_TAGS = 'filter-tags';
    public final const MODULE_FILTER_CONTENT = 'filter-content';
    public final const MODULE_FILTER_AUTHORCONTENT = 'filter-authorcontent';
    public final const MODULE_FILTER_TAGCONTENT = 'filter-tagcontent';
    public final const MODULE_FILTER_POSTS = 'filter-posts';
    public final const MODULE_FILTER_CATEGORYPOSTS = 'filter-categoryposts';
    public final const MODULE_FILTER_AUTHORPOSTS = 'filter-authorposts';
    public final const MODULE_FILTER_AUTHORCATEGORYPOSTS = 'filter-authorcategoryposts';
    public final const MODULE_FILTER_TAGPOSTS = 'filter-tagposts';
    public final const MODULE_FILTER_TAGCATEGORYPOSTS = 'filter-tagcategoryposts';
    public final const MODULE_FILTER_USERS = 'filter-users';
    public final const MODULE_FILTER_AUTHORCOMMUNITYMEMBERS = 'filter-authorcommunitymembers';
    public final const MODULE_FILTER_MYCONTENT = 'filter-mycontent';
    public final const MODULE_FILTER_MYPOSTS = 'filter-myposts';
    public final const MODULE_FILTER_MYCATEGORYPOSTS = 'filter-mycategoryposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTER_TAGS],
            [self::class, self::MODULE_FILTER_CONTENT],
            [self::class, self::MODULE_FILTER_AUTHORCONTENT],
            [self::class, self::MODULE_FILTER_TAGCONTENT],
            [self::class, self::MODULE_FILTER_POSTS],
            [self::class, self::MODULE_FILTER_CATEGORYPOSTS],
            [self::class, self::MODULE_FILTER_AUTHORPOSTS],
            [self::class, self::MODULE_FILTER_AUTHORCATEGORYPOSTS],
            [self::class, self::MODULE_FILTER_TAGPOSTS],
            [self::class, self::MODULE_FILTER_TAGCATEGORYPOSTS],
            [self::class, self::MODULE_FILTER_USERS],
            [self::class, self::MODULE_FILTER_AUTHORCOMMUNITYMEMBERS],
            [self::class, self::MODULE_FILTER_MYCONTENT],
            [self::class, self::MODULE_FILTER_MYPOSTS],
            [self::class, self::MODULE_FILTER_MYCATEGORYPOSTS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::MODULE_FILTER_TAGS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_TAGS],
            self::MODULE_FILTER_CONTENT => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_CONTENT],
            self::MODULE_FILTER_AUTHORCONTENT => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_AUTHORCONTENT],
            self::MODULE_FILTER_TAGCONTENT => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_TAGCONTENT],
            self::MODULE_FILTER_POSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_POSTS],
            self::MODULE_FILTER_CATEGORYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_CATEGORYPOSTS],
            self::MODULE_FILTER_AUTHORPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_AUTHORPOSTS],
            self::MODULE_FILTER_AUTHORCATEGORYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS],
            self::MODULE_FILTER_TAGPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_TAGPOSTS],
            self::MODULE_FILTER_TAGCATEGORYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_TAGCATEGORYPOSTS],
            self::MODULE_FILTER_USERS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_USERS],
            self::MODULE_FILTER_AUTHORCOMMUNITYMEMBERS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_AUTHORCOMMUNITYMEMBERS],
            self::MODULE_FILTER_MYCONTENT => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_MYCONTENT],
            self::MODULE_FILTER_MYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_MYPOSTS],
            self::MODULE_FILTER_MYCATEGORYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_MYCATEGORYPOSTS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}


