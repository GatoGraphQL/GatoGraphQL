<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_CommonUserRoles_Module_Processor_UserTypeaheadComponentFormInputs extends PoP_Module_Processor_UserTypeaheadComponentFormInputs
{
    public final const MODULE_URE_TYPEAHEAD_COMPONENT_ORGANIZATION = 'ure-typeahead-component-organization';
    public final const MODULE_URE_TYPEAHEAD_COMPONENT_INDIVIDUAL = 'ure-typeahead-component-individual';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_TYPEAHEAD_COMPONENT_ORGANIZATION],
            [self::class, self::MODULE_URE_TYPEAHEAD_COMPONENT_INDIVIDUAL],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_URE_TYPEAHEAD_COMPONENT_ORGANIZATION:
                return TranslationAPIFacade::getInstance()->__('Organizations', 'ure-popprocessors');

            case self::MODULE_URE_TYPEAHEAD_COMPONENT_INDIVIDUAL:
                return TranslationAPIFacade::getInstance()->__('Individuals', 'ure-popprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    protected function getTypeaheadDataloadSource(array $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::MODULE_URE_TYPEAHEAD_COMPONENT_ORGANIZATION:
                return RouteUtils::getRouteURL(POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS);

            case self::MODULE_URE_TYPEAHEAD_COMPONENT_INDIVIDUAL:
                return RouteUtils::getRouteURL(POP_COMMONUSERROLES_ROUTE_INDIVIDUALS);
        }

        return parent::getTypeaheadDataloadSource($component, $props);
    }

    protected function getThumbprintQuery(array $component, array &$props)
    {
        $ret = parent::getThumbprintQuery($component, $props);

        switch ($component[1]) {
            case self::MODULE_URE_TYPEAHEAD_COMPONENT_ORGANIZATION:
                $ret['role'] = GD_URE_ROLE_ORGANIZATION;
                break;

            case self::MODULE_URE_TYPEAHEAD_COMPONENT_INDIVIDUAL:
                $ret['role'] = GD_URE_ROLE_INDIVIDUAL;
                break;
        }

        return $ret;
    }

    protected function getPendingMsg(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_URE_TYPEAHEAD_COMPONENT_ORGANIZATION:
                return TranslationAPIFacade::getInstance()->__('Loading Organizations', 'ure-popprocessors');

            case self::MODULE_URE_TYPEAHEAD_COMPONENT_INDIVIDUAL:
                return TranslationAPIFacade::getInstance()->__('Loading Individuals', 'ure-popprocessors');
        }

        return parent::getPendingMsg($component);
    }

    protected function getNotfoundMsg(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_URE_TYPEAHEAD_COMPONENT_ORGANIZATION:
                return TranslationAPIFacade::getInstance()->__('No Organizations found', 'ure-popprocessors');

            case self::MODULE_URE_TYPEAHEAD_COMPONENT_INDIVIDUAL:
                return TranslationAPIFacade::getInstance()->__('No Individuals found', 'ure-popprocessors');
        }

        return parent::getNotfoundMsg($component);
    }
}



