<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

abstract class PoP_Module_Processor_TagTypeaheadComponentFormInputsBase extends PoP_Module_Processor_TypeaheadComponentFormInputsBase
{
    protected function getValueKey(array $module, array &$props)
    {
        return 'symbolnamedescription';
    }
    protected function getComponentTemplateResource(array $module)
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTTAG_TYPEAHEAD_COMPONENT];
    }
    protected function getTokenizerKeys(array $module, array &$props)
    {
        return array('symbolnamedescription');
    }

    // protected function getSourceFilter(array $module, array &$props)
    // {
    //     return POP_FILTER_TAGS;
    // }

    protected function getSourceFilterParams(array $module, array &$props)
    {
        $ret = parent::getSourceFilterParams($module, $props);

        // bring the tags ordering by tag count
        $ret[] = [
            'module' => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERTAG],
            'value' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:tags:count').'|DESC',
        ];

        return $ret;
    }
    protected function getRemoteUrl(array $module, array &$props)
    {
        $url = parent::getRemoteUrl($module, $props);

        // Add the query from typeahead.js to filter (http://twitter.github.io/typeahead.js/examples/)
        return GeneralUtils::addQueryArgs([
            PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH => GD_JSPLACEHOLDER_QUERY,
        ], $url);
    }

    protected function getThumbprintQuery(array $module, array &$props)
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
        $posttagapi = \PoPSchema\PostTags\FunctionAPIFactory::getInstance();
        return $posttagapi->getTags($query, ['return-type' => ReturnTypes::IDS]);
    }

    protected function getPendingMsg(array $module)
    {
        return TranslationAPIFacade::getInstance()->__('Loading Tags', 'pop-coreprocessors');
    }
    protected function getNotfoundMsg(array $module)
    {
        return TranslationAPIFacade::getInstance()->__('No Tags found', 'pop-coreprocessors');
    }
}
