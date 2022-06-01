<?php
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;

abstract class PoP_Module_Processor_PostTypeaheadComponentFormInputsBase extends PoP_Module_Processor_TypeaheadComponentFormInputsBase
{
    protected function getValueKey(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'title';
    }
    protected function getComponentTemplateResource(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTPOST_TYPEAHEAD_COMPONENT];
    }
    protected function getTokenizerKeys(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return array('title');
    }

    protected function getThumbprintQuery(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return array(
            // 'fields' => 'ids',
            'limit' => 1,
            'orderby' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:customposts:id'),
            'order' => 'DESC',
        );
    }
    protected function executeThumbprint($query)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
    }

    protected function getPendingMsg(\PoP\ComponentModel\Component\Component $component)
    {
        return TranslationAPIFacade::getInstance()->__('Loading Content', 'pop-coreprocessors');
    }
    protected function getNotfoundMsg(\PoP\ComponentModel\Component\Component $component)
    {
        return TranslationAPIFacade::getInstance()->__('No Content found', 'pop-coreprocessors');
    }
}
