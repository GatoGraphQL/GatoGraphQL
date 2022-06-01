<?php
use PoP\ComponentModel\Facades\HelperServices\DataloadHelperServiceFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\ModuleConfiguration as UsersModuleConfiguration;

class PoP_Module_Processor_UserTypeaheadComponentFormInputs extends PoP_Module_Processor_UserTypeaheadComponentFormInputsBase
{
    public final const COMPONENT_TYPEAHEAD_COMPONENT_USERS = 'forminput-typeaheadcomponent-users';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_TYPEAHEAD_COMPONENT_USERS,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_TYPEAHEAD_COMPONENT_USERS:
                return getRouteIcon(UsersModuleConfiguration::getUsersRoute(), true).TranslationAPIFacade::getInstance()->__('Users:', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    // protected function getSourceFilter(\PoP\ComponentModel\Component\Component $component, array &$props)
    // {
    //     return POP_FILTER_USERS;
    // }
    protected function getSourceFilterParams(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getSourceFilterParams($component, $props);

        if (defined('POP_POSTS_INITIALIZED')) {
            $ret[] = [
                'component' => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::COMPONENT_FILTERINPUT_ORDERUSER],
                'value' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:users:post-count').'|DESC',
            ];
        }

        return $ret;
    }
    protected function getRemoteUrl(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $url = parent::getRemoteUrl($component, $props);

        $dataloadHelperService = DataloadHelperServiceFacade::getInstance();
        return $dataloadHelperService->addFilterParams(
            $url,
            [
                [
                    'component' => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_NAME],
                    'value' => GD_JSPLACEHOLDER_QUERY,
                ],
            ]
        );
    }

    protected function getTypeaheadDataloadSource(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component->name) {
            case self::COMPONENT_TYPEAHEAD_COMPONENT_USERS:
                return RouteUtils::getRouteURL(UsersModuleConfiguration::getUsersRoute());
        }

        return parent::getTypeaheadDataloadSource($component, $props);
    }
}



