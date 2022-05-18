<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class GD_URE_Module_Processor_UserTypeaheadComponentFormInputs extends PoP_Module_Processor_UserTypeaheadComponentFormInputs
{
    public final const MODULE_URE_TYPEAHEAD_COMPONENT_COMMUNITY = 'ure-typeahead-component-community';
    public final const MODULE_URE_TYPEAHEAD_COMPONENT_COMMUNITYPLUSMEMBERS = 'ure-typeahead-component-communityplusmembers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_TYPEAHEAD_COMPONENT_COMMUNITY],
            [self::class, self::MODULE_URE_TYPEAHEAD_COMPONENT_COMMUNITYPLUSMEMBERS],
        );
    }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_TYPEAHEAD_COMPONENT_COMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Communities', 'ure-popprocessors');
        }

        return parent::getLabelText($componentVariation, $props);
    }

    protected function getTypeaheadDataloadSource(array $componentVariation, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_URE_TYPEAHEAD_COMPONENT_COMMUNITYPLUSMEMBERS:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $url = $userTypeAPI->getUserURL($author);
                return RequestUtils::addRoute($url, POP_USERCOMMUNITIES_ROUTE_COMMUNITYPLUSMEMBERS);

            case self::MODULE_URE_TYPEAHEAD_COMPONENT_COMMUNITY:
                return RouteUtils::getRouteURL(POP_USERCOMMUNITIES_ROUTE_COMMUNITIES);
        }

        return parent::getTypeaheadDataloadSource($componentVariation, $props);
    }

    protected function getThumbprintQuery(array $componentVariation, array &$props)
    {
        $ret = parent::getThumbprintQuery($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_URE_TYPEAHEAD_COMPONENT_COMMUNITY:
                $ret['role'] = GD_URE_ROLE_COMMUNITY;
                break;
        }

        return $ret;
    }

    protected function getPendingMsg(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_TYPEAHEAD_COMPONENT_COMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Loading Communities', 'ure-popprocessors');
        }

        return parent::getPendingMsg($componentVariation);
    }

    protected function getNotfoundMsg(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_TYPEAHEAD_COMPONENT_COMMUNITY:
                return TranslationAPIFacade::getInstance()->__('No Communities found', 'ure-popprocessors');
        }

        return parent::getNotfoundMsg($componentVariation);
    }
}



