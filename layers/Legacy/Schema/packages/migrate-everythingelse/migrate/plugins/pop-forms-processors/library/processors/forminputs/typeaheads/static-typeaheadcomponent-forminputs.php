<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\ComponentConfiguration as UsersComponentConfiguration;

class PoP_Module_Processor_StaticTypeaheadComponentFormInputs extends PoP_Module_Processor_StaticTypeaheadComponentFormInputsBase
{
    public final const MODULE_TYPEAHEAD_COMPONENT_STATICSEARCH = 'forminput-typeaheadcomponent-staticsearch';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TYPEAHEAD_COMPONENT_STATICSEARCH],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_STATICSEARCH:
                return getRouteIcon(POP_BLOG_ROUTE_SEARCHCONTENT, true).TranslationAPIFacade::getInstance()->__('Search:', 'pop-coreprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    protected function getStaticSuggestions(array $module, array &$props)
    {
        $ret = parent::getStaticSuggestions($module, $props);

        switch ($module[1]) {
            case self::MODULE_TYPEAHEAD_COMPONENT_STATICSEARCH:
                $query_wildcard = GD_JSPLACEHOLDER_QUERY;
                $ret[] = array(
                    'title' => getRouteIcon(POP_BLOG_ROUTE_CONTENT, true).TranslationAPIFacade::getInstance()->__('Content with ', 'pop-coreprocessors').'"'.GD_JSPLACEHOLDER_QUERY.'"',
                    'value' => $query_wildcard,
                    'url' => GD_StaticSearchUtils::getContentSearchUrl($props, $query_wildcard),
                );
                $ret[] = array(
                    'title' => getRouteIcon(UsersComponentConfiguration::getUsersRoute(), true).TranslationAPIFacade::getInstance()->__('Users with ', 'pop-coreprocessors').'"'.GD_JSPLACEHOLDER_QUERY.'"',
                    'value' => $query_wildcard,
                    'url' => GD_StaticSearchUtils::getUsersSearchUrl($props, $query_wildcard),
                );
                break;
        }

        return $ret;
    }
}



