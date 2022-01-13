<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

abstract class PoP_Module_Processor_UserTypeaheadComponentFormInputsBase extends PoP_Module_Processor_TypeaheadComponentFormInputsBase
{

    // protected function getComponentTemplateResource(array $module) {

    //     return [PoP_Module_Processor_UserTypeaheadComponentLayouts::class, PoP_Module_Processor_UserTypeaheadComponentLayouts::MODULE_LAYOUTUSER_TYPEAHEAD_COMPONENT];
    // }
    protected function getValueKey(array $module, array &$props)
    {
        return 'displayName';
    }
    protected function getComponentTemplateResource(array $module)
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTUSER_TYPEAHEAD_COMPONENT];
    }
    // protected function getLayoutSubmodule(array $module) {

    //     return [PoP_Module_Processor_UserTypeaheadComponentLayouts::class, PoP_Module_Processor_UserTypeaheadComponentLayouts::MODULE_LAYOUTUSER_TYPEAHEAD_COMPONENT];
    // }
    protected function getTokenizerKeys(array $module, array &$props)
    {
        return array('displayName');
    }


    protected function getThumbprintQuery(array $module, array &$props)
    {

        // Allow PoP User Platform to add the role "profile"
        return HooksAPIFacade::getInstance()->applyFilters(
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

    protected function getPendingMsg(array $module)
    {
        return TranslationAPIFacade::getInstance()->__('Loading Users', 'pop-coreprocessors');
    }
    protected function getNotfoundMsg(array $module)
    {
        return TranslationAPIFacade::getInstance()->__('No Users found', 'pop-coreprocessors');
    }
}
