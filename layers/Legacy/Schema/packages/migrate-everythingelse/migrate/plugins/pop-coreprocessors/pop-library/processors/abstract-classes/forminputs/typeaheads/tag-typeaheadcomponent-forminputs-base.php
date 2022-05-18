<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;

abstract class PoP_Module_Processor_TagTypeaheadComponentFormInputsBase extends PoP_Module_Processor_TypeaheadComponentFormInputsBase
{
    protected function getValueKey(array $component, array &$props)
    {
        return 'symbolnamedescription';
    }
    protected function getComponentTemplateResource(array $component)
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTTAG_TYPEAHEAD_COMPONENT];
    }
    protected function getTokenizerKeys(array $component, array &$props)
    {
        return array('symbolnamedescription');
    }

    // protected function getSourceFilter(array $component, array &$props)
    // {
    //     return POP_FILTER_TAGS;
    // }

    protected function getSourceFilterParams(array $component, array &$props)
    {
        $ret = parent::getSourceFilterParams($component, $props);

        // bring the tags ordering by tag count
        $ret[] = [
            'component' => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERTAG],
            'value' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:tags:count').'|DESC',
        ];

        return $ret;
    }
    protected function getRemoteUrl(array $component, array &$props)
    {
        $url = parent::getRemoteUrl($component, $props);

        // Add the query from typeahead.js to filter (http://twitter.github.io/typeahead.js/examples/)
        return GeneralUtils::addQueryArgs([
            PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH => GD_JSPLACEHOLDER_QUERY,
        ], $url);
    }

    protected function getThumbprintQuery(array $component, array &$props)
    {
        return array(
            // 'fields' => 'ids',
            'limit' => 1,
            'orderby' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:tags:id'),
            'order' => 'DESC',
        );
    }
    protected function executeThumbprint($query)
    {
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        return $postTagTypeAPI->getTags($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
    }

    protected function getPendingMsg(array $component)
    {
        return TranslationAPIFacade::getInstance()->__('Loading Tags', 'pop-coreprocessors');
    }
    protected function getNotfoundMsg(array $component)
    {
        return TranslationAPIFacade::getInstance()->__('No Tags found', 'pop-coreprocessors');
    }
}
