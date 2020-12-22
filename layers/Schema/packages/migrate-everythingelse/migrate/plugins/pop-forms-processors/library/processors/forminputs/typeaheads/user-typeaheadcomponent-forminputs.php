<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Engine\Route\RouteUtils;

class PoP_Module_Processor_UserTypeaheadComponentFormInputs extends PoP_Module_Processor_UserTypeaheadComponentFormInputsBase
{
    public const MODULE_TYPEAHEAD_COMPONENT_USERS = 'forminput-typeaheadcomponent-users';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TYPEAHEAD_COMPONENT_USERS],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_USERS:
                return getRouteIcon(POP_USERS_ROUTE_USERS, true).TranslationAPIFacade::getInstance()->__('Users:', 'pop-coreprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    // protected function getSourceFilter(array $module, array &$props)
    // {
    //     return POP_FILTER_USERS;
    // }
    protected function getSourceFilterParams(array $module, array &$props)
    {
        $ret = parent::getSourceFilterParams($module, $props);

        if (defined('POP_POSTS_INITIALIZED')) {
            $ret[] = [
                'module' => [PoP_Module_Processor_SelectFilterInputs::class, PoP_Module_Processor_SelectFilterInputs::MODULE_FILTERINPUT_ORDERUSER],
                'value' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:users:post-count').'|DESC',
            ];
        }

        return $ret;
    }
    protected function getRemoteUrl(array $module, array &$props)
    {
        $url = parent::getRemoteUrl($module, $props);
        
        return \PoP\ComponentModel\DataloadUtils::addFilterParams(
            $url, 
            [
                [
                    'module' => [PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_NAME],
                    'value' => GD_JSPLACEHOLDER_QUERY,
                ],
            ]
        );
    }

    protected function getTypeaheadDataloadSource(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_USERS:
                return RouteUtils::getRouteURL(POP_USERS_ROUTE_USERS);
        }

        return parent::getTypeaheadDataloadSource($module, $props);
    }
}



