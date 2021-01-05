<?php

class PoP_Module_Processor_CustomFilters extends PoP_Module_Processor_FiltersBase
{
    public const MODULE_FILTER_TAGS = 'filter-tags';
    public const MODULE_FILTER_CONTENT = 'filter-content';
    public const MODULE_FILTER_AUTHORCONTENT = 'filter-authorcontent';
    public const MODULE_FILTER_TAGCONTENT = 'filter-tagcontent';
    public const MODULE_FILTER_POSTS = 'filter-posts';
    public const MODULE_FILTER_CATEGORYPOSTS = 'filter-categoryposts';
    public const MODULE_FILTER_AUTHORPOSTS = 'filter-authorposts';
    public const MODULE_FILTER_AUTHORCATEGORYPOSTS = 'filter-authorcategoryposts';
    public const MODULE_FILTER_TAGPOSTS = 'filter-tagposts';
    public const MODULE_FILTER_TAGCATEGORYPOSTS = 'filter-tagcategoryposts';
    public const MODULE_FILTER_USERS = 'filter-users';
    public const MODULE_FILTER_AUTHORCOMMUNITYMEMBERS = 'filter-authorcommunitymembers';
    public const MODULE_FILTER_MYCONTENT = 'filter-mycontent';
    public const MODULE_FILTER_MYPOSTS = 'filter-myposts';
    public const MODULE_FILTER_MYCATEGORYPOSTS = 'filter-mycategoryposts';

    public function getModulesToProcess(): array
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

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FILTER_TAGS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_TAGS],
            self::MODULE_FILTER_CONTENT => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_CONTENT],
            self::MODULE_FILTER_AUTHORCONTENT => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_AUTHORCONTENT],
            self::MODULE_FILTER_TAGCONTENT => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_TAGCONTENT],
            self::MODULE_FILTER_POSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_POSTS],
            self::MODULE_FILTER_CATEGORYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_CATEGORYPOSTS],
            self::MODULE_FILTER_AUTHORPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_AUTHORPOSTS],
            self::MODULE_FILTER_AUTHORCATEGORYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_AUTHORCATEGORYPOSTS],
            self::MODULE_FILTER_TAGPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_TAGPOSTS],
            self::MODULE_FILTER_TAGCATEGORYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_TAGCATEGORYPOSTS],
            self::MODULE_FILTER_USERS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_USERS],
            self::MODULE_FILTER_AUTHORCOMMUNITYMEMBERS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_AUTHORCOMMUNITYMEMBERS],
            self::MODULE_FILTER_MYCONTENT => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_MYCONTENT],
            self::MODULE_FILTER_MYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_MYPOSTS],
            self::MODULE_FILTER_MYCATEGORYPOSTS => [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_MYCATEGORYPOSTS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}


