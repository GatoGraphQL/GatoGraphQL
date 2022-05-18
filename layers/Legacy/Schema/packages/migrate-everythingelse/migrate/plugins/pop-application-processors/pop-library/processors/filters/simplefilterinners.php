<?php

class PoP_Module_Processor_CustomSimpleFilterInners extends PoP_Module_Processor_SimpleFilterInnersBase
{
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_TAGS = 'simplefilterinputcontainer-tags';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_CONTENT = 'simplefilterinputcontainer-content';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORCONTENT = 'simplefilterinputcontainer-authorcontent';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_POSTS = 'simplefilterinputcontainer-posts';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_CATEGORYPOSTS = 'simplefilterinputcontainer-categoryposts';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORPOSTS = 'simplefilterinputcontainer-authorposts';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS = 'simplefilterinputcontainer-authorcategoryposts';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_TAGPOSTS = 'simplefilterinputcontainer-tagposts';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_TAGCATEGORYPOSTS = 'simplefilterinputcontainer-tagcategoryposts';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_USERS = 'simplefilterinputcontainer-users';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORCOMMUNITYMEMBERS = 'simplefilterinputcontainer-authorcommunitymembers';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_MYCONTENT = 'simplefilterinputcontainer-mycontent';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_MYPOSTS = 'simplefilterinputcontainer-myposts';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_MYCATEGORYPOSTS = 'simplefilterinputcontainer-mycategoryposts';
    public final const MODULE_SIMPLEFILTERINPUTCONTAINER_TAGCONTENT = 'simplefilterinputcontainer-tagcontent';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_CONTENT],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORCONTENT],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_POSTS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_CATEGORYPOSTS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORPOSTS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGPOSTS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGCATEGORYPOSTS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_USERS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORCOMMUNITYMEMBERS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYCONTENT],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYPOSTS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYCATEGORYPOSTS],
            [self::class, self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGCONTENT],
        );
    }

    protected function getInputSubmodules(array $module)
    {
        $ret = parent::getInputSubmodules($module);

        $inputmodules = [
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_POSTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_POSTSECTIONS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERTAG],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_CONTENT => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORCONTENT => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGCONTENT => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_CATEGORYPOSTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORPOSTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_POSTSECTIONS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGPOSTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_POSTSECTIONS],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGCATEGORYPOSTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_USERS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_NAME],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERUSER],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORCOMMUNITYMEMBERS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_NAME],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERUSER],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYCONTENT => [
                GD_CreateUpdate_Utils::moderate() ?
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS] :
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYPOSTS => [
                GD_CreateUpdate_Utils::moderate() ?
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS] :
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_POSTSECTIONS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
            self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYCATEGORYPOSTS => [
                GD_CreateUpdate_Utils::moderate() ?
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS] :
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::MODULE_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERPOST],
            ],
        ];
        if ($modules = \PoP\Root\App::applyFilters(
            'Blog:SimpleFilterInners:inputmodules',
            $inputmodules[$module[1]],
            $module
        )) {
            $ret = array_merge(
                $ret,
                $modules
            );
        }
        return $ret;
    }

    // public function getFilter(array $module)
    // {
    //     $filters = array(
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGS => POP_FILTER_TAGS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_CONTENT => POP_FILTER_CONTENT,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORCONTENT => POP_FILTER_AUTHORCONTENT,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_POSTS => POP_FILTER_POSTS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_CATEGORYPOSTS => POP_FILTER_CATEGORYPOSTS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORPOSTS => POP_FILTER_AUTHORPOSTS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS => POP_FILTER_AUTHORCATEGORYPOSTS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGPOSTS => POP_FILTER_TAGPOSTS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGCATEGORYPOSTS => POP_FILTER_TAGCATEGORYPOSTS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_USERS => POP_FILTER_USERS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_AUTHORCOMMUNITYMEMBERS => POP_FILTER_AUTHORCOMMUNITYMEMBERS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYCONTENT => POP_FILTER_MYCONTENT,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYPOSTS => POP_FILTER_MYPOSTS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_MYCATEGORYPOSTS => POP_FILTER_MYCATEGORYPOSTS,
    //         self::MODULE_SIMPLEFILTERINPUTCONTAINER_TAGCONTENT => POP_FILTER_TAGCONTENT,
    //     );
    //     if ($filter = $filters[$module[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($module);
    // }
}



