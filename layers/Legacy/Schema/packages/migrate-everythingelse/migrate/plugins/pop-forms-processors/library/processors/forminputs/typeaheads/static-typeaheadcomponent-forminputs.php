<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\ModuleConfiguration as UsersModuleConfiguration;

class PoP_Module_Processor_StaticTypeaheadComponentFormInputs extends PoP_Module_Processor_StaticTypeaheadComponentFormInputsBase
{
    public final const COMPONENT_TYPEAHEAD_COMPONENT_STATICSEARCH = 'forminput-typeaheadcomponent-staticsearch';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TYPEAHEAD_COMPONENT_STATICSEARCH],
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_TYPEAHEAD_COMPONENT_STATICSEARCH:
                return getRouteIcon(POP_BLOG_ROUTE_SEARCHCONTENT, true).TranslationAPIFacade::getInstance()->__('Search:', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    protected function getStaticSuggestions(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getStaticSuggestions($component, $props);

        switch ($component->name) {
            case self::COMPONENT_TYPEAHEAD_COMPONENT_STATICSEARCH:
                $query_wildcard = GD_JSPLACEHOLDER_QUERY;
                $ret[] = array(
                    'title' => getRouteIcon(POP_BLOG_ROUTE_CONTENT, true).TranslationAPIFacade::getInstance()->__('Content with ', 'pop-coreprocessors').'"'.GD_JSPLACEHOLDER_QUERY.'"',
                    'value' => $query_wildcard,
                    'url' => GD_StaticSearchUtils::getContentSearchUrl($props, $query_wildcard),
                );
                $ret[] = array(
                    'title' => getRouteIcon(UsersModuleConfiguration::getUsersRoute(), true).TranslationAPIFacade::getInstance()->__('Users with ', 'pop-coreprocessors').'"'.GD_JSPLACEHOLDER_QUERY.'"',
                    'value' => $query_wildcard,
                    'url' => GD_StaticSearchUtils::getUsersSearchUrl($props, $query_wildcard),
                );
                break;
        }

        return $ret;
    }
}



