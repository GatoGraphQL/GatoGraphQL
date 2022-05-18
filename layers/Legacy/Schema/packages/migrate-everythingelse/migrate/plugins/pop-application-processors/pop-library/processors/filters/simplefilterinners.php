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

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_CONTENT],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORCONTENT],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_POSTS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_CATEGORYPOSTS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORPOSTS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGPOSTS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGCATEGORYPOSTS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_USERS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORCOMMUNITYMEMBERS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYCONTENT],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYPOSTS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYCATEGORYPOSTS],
            [self::class, self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGCONTENT],
        );
    }

    protected function getInputSubmodules(array $component)
    {
        $ret = parent::getInputSubmodules($component);

        $inputmodules = [
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_POSTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::COMPONENT_FILTERINPUT_POSTSECTIONS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERTAG],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_CONTENT => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORCONTENT => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGCONTENT => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_CATEGORYPOSTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORPOSTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::COMPONENT_FILTERINPUT_POSTSECTIONS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGPOSTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::COMPONENT_FILTERINPUT_POSTSECTIONS],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGCATEGORYPOSTS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_USERS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_NAME],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERUSER],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORCOMMUNITYMEMBERS => [
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_NAME],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERUSER],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYCONTENT => [
                GD_CreateUpdate_Utils::moderate() ?
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS] :
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYPOSTS => [
                GD_CreateUpdate_Utils::moderate() ?
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS] :
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::COMPONENT_FILTERINPUT_POSTSECTIONS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
            self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYCATEGORYPOSTS => [
                GD_CreateUpdate_Utils::moderate() ?
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS] :
                    [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_HASHTAGS],
                [PoP_Module_Processor_DateRangeComponentFilterInputs::class, PoP_Module_Processor_DateRangeComponentFilterInputs::COMPONENT_FILTERINPUT_CUSTOMPOSTDATES],
                [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERPOST],
            ],
        ];
        if ($components = \PoP\Root\App::applyFilters(
            'Blog:SimpleFilterInners:inputmodules',
            $inputmodules[$component[1]],
            $component
        )) {
            $ret = array_merge(
                $ret,
                $components
            );
        }
        return $ret;
    }

    // public function getFilter(array $component)
    // {
    //     $filters = array(
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGS => POP_FILTER_TAGS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_CONTENT => POP_FILTER_CONTENT,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORCONTENT => POP_FILTER_AUTHORCONTENT,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_POSTS => POP_FILTER_POSTS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_CATEGORYPOSTS => POP_FILTER_CATEGORYPOSTS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORPOSTS => POP_FILTER_AUTHORPOSTS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS => POP_FILTER_AUTHORCATEGORYPOSTS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGPOSTS => POP_FILTER_TAGPOSTS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGCATEGORYPOSTS => POP_FILTER_TAGCATEGORYPOSTS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_USERS => POP_FILTER_USERS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_AUTHORCOMMUNITYMEMBERS => POP_FILTER_AUTHORCOMMUNITYMEMBERS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYCONTENT => POP_FILTER_MYCONTENT,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYPOSTS => POP_FILTER_MYPOSTS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_MYCATEGORYPOSTS => POP_FILTER_MYCATEGORYPOSTS,
    //         self::COMPONENT_SIMPLEFILTERINPUTCONTAINER_TAGCONTENT => POP_FILTER_TAGCONTENT,
    //     );
    //     if ($filter = $filters[$component[1]] ?? null) {
    //         return $filter;
    //     }

    //     return parent::getFilter($component);
    // }
}



