<?php
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

abstract class PoP_Module_Processor_UserTypeaheadComponentFormInputsBase extends PoP_Module_Processor_TypeaheadComponentFormInputsBase
{

    // protected function getComponentTemplateResource(\PoP\ComponentModel\Component\Component $component) {

    //     return [PoP_Module_Processor_UserTypeaheadComponentLayouts::class, PoP_Module_Processor_UserTypeaheadComponentLayouts::COMPONENT_LAYOUTUSER_TYPEAHEAD_COMPONENT];
    // }
    protected function getValueKey(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'displayName';
    }
    protected function getComponentTemplateResource(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTUSER_TYPEAHEAD_COMPONENT];
    }
    // protected function getLayoutSubcomponent(\PoP\ComponentModel\Component\Component $component) {

    //     return [PoP_Module_Processor_UserTypeaheadComponentLayouts::class, PoP_Module_Processor_UserTypeaheadComponentLayouts::COMPONENT_LAYOUTUSER_TYPEAHEAD_COMPONENT];
    // }
    protected function getTokenizerKeys(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return array('displayName');
    }


    protected function getThumbprintQuery(\PoP\ComponentModel\Component\Component $component, array &$props)
    {

        // Allow PoP User Platform to add the role "profile"
        return \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_UserTypeaheadComponentFormInputsBase:thumbprint-query',
            array(
                // 'fields' => 'ID',
                'limit' => 1,
                'orderby' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:users:id'),
                'order' => 'DESC',
            )
        );
    }
    protected function executeThumbprint($query)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        return $userTypeAPI->getUsers($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
    }

    protected function getPendingMsg(\PoP\ComponentModel\Component\Component $component)
    {
        return TranslationAPIFacade::getInstance()->__('Loading Users', 'pop-coreprocessors');
    }
    protected function getNotfoundMsg(\PoP\ComponentModel\Component\Component $component)
    {
        return TranslationAPIFacade::getInstance()->__('No Users found', 'pop-coreprocessors');
    }
}
