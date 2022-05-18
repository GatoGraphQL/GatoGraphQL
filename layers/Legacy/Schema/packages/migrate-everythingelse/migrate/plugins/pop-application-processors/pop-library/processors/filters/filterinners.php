<?php

class PoP_Module_Processor_CustomFilterInners extends PoP_Module_Processor_FilterInnersBase
{
    public final const MODULE_FILTERINPUTCONTAINER_TAGS = 'filterinputcontainer-tags';
    public final const MODULE_FILTERINPUTCONTAINER_CONTENT = 'filterinputcontainer-content';
    public final const MODULE_FILTERINPUTCONTAINER_AUTHORCONTENT = 'filterinputcontainer-authorcontent';
    public final const MODULE_FILTERINPUTCONTAINER_TAGCONTENT = 'filterinputcontainer-tagcontent';
    public final const MODULE_FILTERINPUTCONTAINER_POSTS = 'filterinputcontainer-posts';
    public final const MODULE_FILTERINPUTCONTAINER_CATEGORYPOSTS = 'filterinputcontainer-categoryposts';
    public final const MODULE_FILTERINPUTCONTAINER_AUTHORPOSTS = 'filterinputcontainer-authorposts';
    public final const MODULE_FILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS = 'filterinputcontainer-authorcategoryposts';
    public final const MODULE_FILTERINPUTCONTAINER_TAGPOSTS = 'filterinputcontainer-tagposts';
    public final const MODULE_FILTERINPUTCONTAINER_TAGCATEGORYPOSTS = 'filterinputcontainer-tagcategoryposts';
    public final const MODULE_FILTERINPUTCONTAINER_USERS = 'filterinputcontainer-users';
    public final const MODULE_FILTERINPUTCONTAINER_AUTHORCOMMUNITYMEMBERS = 'filterinputcontainer-authorcommunitymembers';
    public final const MODULE_FILTERINPUTCONTAINER_MYCONTENT = 'filterinputcontainer-mycontent';
    public final const MODULE_FILTERINPUTCONTAINER_MYPOSTS = 'filterinputcontainer-myposts';
    public final const MODULE_FILTERINPUTCONTAINER_MYCATEGORYPOSTS = 'filterinputcontainer-mycategoryposts';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_TAGS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CONTENT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_AUTHORCONTENT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_TAGCONTENT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_POSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CATEGORYPOSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_AUTHORPOSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_TAGPOSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_TAGCATEGORYPOSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_USERS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_AUTHORCOMMUNITYMEMBERS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYCONTENT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYPOSTS],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_MYCATEGORYPOSTS],
        );
    }

    protected function getInputSubmodules(array $componentVariation)
    {
        $ret = parent::getInputSubmodules($componentVariation);

        $inputmodules = [
            self::MODULE_FILTERINPUTCONTAINER_POSTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_POSTSECTIONS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINPUTCONTAINER_TAGS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERTAG],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CONTENT => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINPUTCONTAINER_AUTHORCONTENT => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINPUTCONTAINER_TAGCONTENT => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CATEGORYPOSTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINPUTCONTAINER_AUTHORPOSTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_POSTSECTIONS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINPUTCONTAINER_TAGPOSTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_POSTSECTIONS],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINPUTCONTAINER_TAGCATEGORYPOSTS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormComponentGroups::class, PoP_Module_Processor_FormComponentGroups::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINPUTCONTAINER_USERS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_NAME],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERUSER],
            ],
            self::MODULE_FILTERINPUTCONTAINER_AUTHORCOMMUNITYMEMBERS => [
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_NAME],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERUSER],
            ],
            self::MODULE_FILTERINPUTCONTAINER_MYCONTENT => [
                GD_CreateUpdate_Utils::moderate() ?
                    [GD_Core_Bootstrap_Module_Processor_FormInputGroups::class, GD_Core_Bootstrap_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_MODERATEDPOSTSTATUS] :
                    [GD_Core_Bootstrap_Module_Processor_FormInputGroups::class, GD_Core_Bootstrap_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINPUTCONTAINER_MYPOSTS => [
                GD_CreateUpdate_Utils::moderate() ?
                    [GD_Core_Bootstrap_Module_Processor_FormInputGroups::class, GD_Core_Bootstrap_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_MODERATEDPOSTSTATUS] :
                    [GD_Core_Bootstrap_Module_Processor_FormInputGroups::class, GD_Core_Bootstrap_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_POSTSECTIONS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
            self::MODULE_FILTERINPUTCONTAINER_MYCATEGORYPOSTS => [
                GD_CreateUpdate_Utils::moderate() ?
                    [GD_Core_Bootstrap_Module_Processor_FormInputGroups::class, GD_Core_Bootstrap_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_MODERATEDPOSTSTATUS] :
                    [GD_Core_Bootstrap_Module_Processor_FormInputGroups::class, GD_Core_Bootstrap_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                [GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::class, GD_Core_Bootstrap_Module_Processor_SubcomponentFormInputGroups::MODULE_FILTERINPUTGROUP_POSTDATES],
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_ORDERPOST],
            ],
        ];
        if ($componentVariations = \PoP\Root\App::applyFilters(
            'Blog:FilterInnerComponentProcessor:inputmodules',
            $inputmodules[$componentVariation[1]],
            $componentVariation
        )) {
            $ret = array_merge(
                $ret,
                $componentVariations
            );
        }
        return $ret;
    }

    // public function getFilter(array $componentVariation)
    // {
    //     $filters = array(
    //         self::MODULE_FILTERINPUTCONTAINER_TAGS => POP_FILTER_TAGS,
    //         self::MODULE_FILTERINPUTCONTAINER_CONTENT => POP_FILTER_CONTENT,
    //         self::MODULE_FILTERINPUTCONTAINER_AUTHORCONTENT => POP_FILTER_AUTHORCONTENT,
    //         self::MODULE_FILTERINPUTCONTAINER_TAGCONTENT => POP_FILTER_TAGCONTENT,
    //         self::MODULE_FILTERINPUTCONTAINER_POSTS => POP_FILTER_POSTS,
    //         self::MODULE_FILTERINPUTCONTAINER_CATEGORYPOSTS => POP_FILTER_CATEGORYPOSTS,
    //         self::MODULE_FILTERINPUTCONTAINER_AUTHORPOSTS => POP_FILTER_AUTHORPOSTS,
    //         self::MODULE_FILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS => POP_FILTER_AUTHORCATEGORYPOSTS,
    //         self::MODULE_FILTERINPUTCONTAINER_TAGPOSTS => POP_FILTER_TAGPOSTS,
    //         self::MODULE_FILTERINPUTCONTAINER_TAGCATEGORYPOSTS => POP_FILTER_TAGCATEGORYPOSTS,
    //         self::MODULE_FILTERINPUTCONTAINER_USERS => POP_FILTER_USERS,
    //         self::MODULE_FILTERINPUTCONTAINER_AUTHORCOMMUNITYMEMBERS => POP_FILTER_AUTHORCOMMUNITYMEMBERS,
    //         self::MODULE_FILTERINPUTCONTAINER_MYCONTENT => POP_FILTER_MYCONTENT,
    //         self::MODULE_FILTERINPUTCONTAINER_MYPOSTS => POP_FILTER_MYPOSTS,
    //         self::MODULE_FILTERINPUTCONTAINER_MYCATEGORYPOSTS => POP_FILTER_MYCATEGORYPOSTS,
    //     );
    //     if ($filter = $filters[$componentVariation[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($componentVariation);
    // }
}



