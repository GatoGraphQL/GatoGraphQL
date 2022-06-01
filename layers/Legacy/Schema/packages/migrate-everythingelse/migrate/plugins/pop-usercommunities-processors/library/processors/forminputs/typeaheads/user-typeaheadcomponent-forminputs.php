<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class GD_URE_Module_Processor_UserTypeaheadComponentFormInputs extends PoP_Module_Processor_UserTypeaheadComponentFormInputs
{
    public final const COMPONENT_URE_TYPEAHEAD_COMPONENT_COMMUNITY = 'ure-typeahead-component-community';
    public final const COMPONENT_URE_TYPEAHEAD_COMPONENT_COMMUNITYPLUSMEMBERS = 'ure-typeahead-component-communityplusmembers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_TYPEAHEAD_COMPONENT_COMMUNITY],
            [self::class, self::COMPONENT_URE_TYPEAHEAD_COMPONENT_COMMUNITYPLUSMEMBERS],
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_TYPEAHEAD_COMPONENT_COMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Communities', 'ure-popprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    protected function getTypeaheadDataloadSource(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        switch ($component->name) {
            case self::COMPONENT_URE_TYPEAHEAD_COMPONENT_COMMUNITYPLUSMEMBERS:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $url = $userTypeAPI->getUserURL($author);
                return RequestUtils::addRoute($url, POP_USERCOMMUNITIES_ROUTE_COMMUNITYPLUSMEMBERS);

            case self::COMPONENT_URE_TYPEAHEAD_COMPONENT_COMMUNITY:
                return RouteUtils::getRouteURL(POP_USERCOMMUNITIES_ROUTE_COMMUNITIES);
        }

        return parent::getTypeaheadDataloadSource($component, $props);
    }

    protected function getThumbprintQuery(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getThumbprintQuery($component, $props);

        switch ($component->name) {
            case self::COMPONENT_URE_TYPEAHEAD_COMPONENT_COMMUNITY:
                $ret['role'] = GD_URE_ROLE_COMMUNITY;
                break;
        }

        return $ret;
    }

    protected function getPendingMsg(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_TYPEAHEAD_COMPONENT_COMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Loading Communities', 'ure-popprocessors');
        }

        return parent::getPendingMsg($component);
    }

    protected function getNotfoundMsg(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_TYPEAHEAD_COMPONENT_COMMUNITY:
                return TranslationAPIFacade::getInstance()->__('No Communities found', 'ure-popprocessors');
        }

        return parent::getNotfoundMsg($component);
    }
}



