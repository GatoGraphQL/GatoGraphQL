<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class GD_URE_Custom_Module_Processor_ProfileOrganizationLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoPTheme_Wassup_URE_TemplateResourceLoaderProcessor::class, PoPTheme_Wassup_URE_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PROFILEORGANIZATION_DETAILS];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('organizationTypesByName', 'organizationCategoriesByName', 'contactPerson', 'contactNumber');
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_CLASSES]['label'] = 'label-info';
        $ret[GD_JS_CLASSES]['label2'] = 'label-primary';
        
        // Allow Agenda Urbana to override the titles
        $ret[GD_JS_TITLES] = \PoP\Root\App::getHookManager()->applyFilters(
            'GD_URE_Custom_Module_Processor_ProfileOrganizationLayoutsBase:titles',
            array(
                'types' => TranslationAPIFacade::getInstance()->__('Type', 'poptheme-wassup'),
                'categories' => TranslationAPIFacade::getInstance()->__('Categories', 'poptheme-wassup'),
                'contactperson' => TranslationAPIFacade::getInstance()->__('Contact Person', 'poptheme-wassup'),
                'number' => TranslationAPIFacade::getInstance()->__('Tel / Fax', 'poptheme-wassup'),
            )
        );
        
        return $ret;
    }
}
