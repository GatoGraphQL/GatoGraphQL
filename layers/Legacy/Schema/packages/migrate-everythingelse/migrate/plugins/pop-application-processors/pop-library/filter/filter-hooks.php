<?php
use PoPCMSSchema\Posts\ModuleProcessors\FilterInnerModuleProcessor as PostFilterInners;

class PoPThemeWassup_DataLoad_FilterHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'Blog:FilterInnerModuleProcessor:inputmodules',
            $this->modifyPostFilterInputs(...),
            10,
            2
        );
        \PoP\Root\App::addFilter(
            'Blog:SimpleFilterInners:inputmodules',
            $this->modifyPostSimpleFilterInputs(...),
            10,
            2
        );
    }

    public function modifyPostFilterInputs($filterinputs, array $module)
    {
        $is_post = in_array($module, [
            [PostFilterInners::class, PostFilterInners::MODULE_FILTERINPUTCONTAINER_POSTS],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_AUTHORPOSTS],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_TAGPOSTS],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_TAGCATEGORYPOSTS],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_MYPOSTS],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_MYCATEGORYPOSTS],
        ]);
        $is_content = in_array($module, [
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_CONTENT],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_AUTHORCONTENT],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_TAGCONTENT],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_MYCONTENT],
        ]);
        if ($is_content) {

            if (defined('POP_TAXONOMYQUERY_INITIALIZED') && PoP_Application_TaxonomyQuery_ConfigurationUtils::enableFilterAllcontentByTaxonomy() && PoP_ApplicationProcessors_Utils::addSections()) {
                array_splice(
                    $filterinputs,
                    $pos+1,
                    0,
                    array(
                        [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_CONTENTSECTIONS],
                    )
                );
            }
        }
        if ($is_post || $is_content) {
            // filter-tagallcontent doesn't have the hashtags, so use search there
            $pos = in_array(
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                $filterinputs
            ) ? array_search(
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_HASHTAGS],
                $filterinputs
            ) :
            array_search(
                [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FILTERINPUTGROUP_SEARCH],
                $filterinputs
            );

            // Adding needed components in reverse order because the one component we always know will be there is hashtags, it's the reference one, then we start adding from right to left
            if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                array_splice(
                    $filterinputs,
                    $pos+1,
                    0,
                    array(
                        [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_APPLIESTO],
                    )
                );
            }
            if (PoP_ApplicationProcessors_Utils::addCategories()) {
                array_splice(
                    $filterinputs,
                    $pos+1,
                    0,
                    array(
                        [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FILTERINPUTGROUP_CATEGORIES],
                    )
                );
            }
        }

        return $filterinputs;
    }

    public function modifyPostSimpleFilterInputs($filterinputs, array $module)
    {
        $is_post = in_array($module, [
            [PostFilterInners::class, PostFilterInners::MODULE_FILTERINPUTCONTAINER_POSTS],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_AUTHORPOSTS],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_AUTHORCATEGORYPOSTS],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_TAGPOSTS],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_TAGCATEGORYPOSTS],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_MYPOSTS],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_MYCATEGORYPOSTS],
        ]);
        $is_content = in_array($module, [
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_CONTENT],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_AUTHORCONTENT],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_TAGCONTENT],
            [PoP_Module_Processor_CustomFilterInners::class, PoP_Module_Processor_CustomFilterInners::MODULE_FILTERINPUTCONTAINER_MYCONTENT],
        ]);
        if ($is_content) {

            if (defined('POP_TAXONOMYQUERY_INITIALIZED') && PoP_Application_TaxonomyQuery_ConfigurationUtils::enableFilterAllcontentByTaxonomy() && PoP_ApplicationProcessors_Utils::addSections()) {
                array_splice(
                    $filterinputs,
                    $pos+1,
                    0,
                    array(
                        [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CONTENTSECTIONS],
                    )
                );
            }
        }
        if ($is_post || $is_content) {
            // filter-tagallcontent doesn't have the hashtags, so use search there
            $pos = in_array(
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                $filterinputs
            ) ? array_search(
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_HASHTAGS],
                $filterinputs
            ) : array_search(
                [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH],
                $filterinputs
            );

            // Adding needed components in reverse order because the one component we always know will be there is hashtags, it's the reference one, then we start adding from right to left
            if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                array_splice(
                    $filterinputs,
                    $pos+1,
                    0,
                    array(
                        [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_APPLIESTO],
                    )
                );
            }
            if (PoP_ApplicationProcessors_Utils::addCategories()) {
                array_splice(
                    $filterinputs,
                    $pos+1,
                    0,
                    array(
                        [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CATEGORIES],
                    )
                );
            }
        }

        return $filterinputs;
    }
}

/**
 * Initialize
 */
new PoPThemeWassup_DataLoad_FilterHooks();
