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
            [self::class, self::COMPONENT_FILTER_TAGS],
            [self::class, self::COMPONENT_FILTER_CONTENT],
            [self::class, self::COMPONENT_FILTER_AUTHORCONTENT],
            [self::class, self::COMPONENT_FILTER_TAGCONTENT],
            [self::class, self::COMPONENT_FILTER_POSTS],
            [self::class, self::COMPONENT_FILTER_CATEGORYPOSTS],
            [self::class, self::COMPONENT_FILTER_AUTHORPOSTS],
            [self::class, self::COMPONENT_FILTER_AUTHORCATEGORYPOSTS],
            [self::class, self::COMPONENT_FILTER_TAGPOSTS],
            [self::class, self::COMPONENT_FILTER_TAGCATEGORYPOSTS],
            [self::class, self::COMPONENT_FILTER_USERS],
            [self::class, self::COMPONENT_FILTER_AUTHORCOMMUNITYMEMBERS],
            [self::class, self::COMPONENT_FILTER_MYCONTENT],
            [self::class, self::COMPONENT_FILTER_MYPOSTS],
            [self::class, self::COMPONENT_FILTER_MYCATEGORYPOSTS],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FILTER_TAGS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_TAGS],
            self::COMPONENT_FILTER_CONTENT => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_CONTENT],
            self::COMPONENT_FILTER_AUTHORCONTENT => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_AUTHORCONTENT],
            self::COMPONENT_FILTER_TAGCONTENT => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_TAGCONTENT],
            self::COMPONENT_FILTER_POSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_POSTS],
            self::COMPONENT_FILTER_CATEGORYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_CATEGORYPOSTS],
            self::COMPONENT_FILTER_AUTHORPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_AUTHORPOSTS],
            self::COMPONENT_FILTER_AUTHORCATEGORYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS],
            self::COMPONENT_FILTER_TAGPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_TAGPOSTS],
            self::COMPONENT_FILTER_TAGCATEGORYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_TAGCATEGORYPOSTS],
            self::COMPONENT_FILTER_USERS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_USERS],
            self::COMPONENT_FILTER_AUTHORCOMMUNITYMEMBERS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_AUTHORCOMMUNITYMEMBERS],
            self::COMPONENT_FILTER_MYCONTENT => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_MYCONTENT],
            self::COMPONENT_FILTER_MYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_MYPOSTS],
            self::COMPONENT_FILTER_MYCATEGORYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::COMPONENT_FILTERINPUTCONTAINER_MYCATEGORYPOSTS],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}


