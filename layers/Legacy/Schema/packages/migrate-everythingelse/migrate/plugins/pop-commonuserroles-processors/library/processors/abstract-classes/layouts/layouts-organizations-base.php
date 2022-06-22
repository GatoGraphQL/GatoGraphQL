<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class GD_URE_Custom_Module_Processor_ProfileOrganizationLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoPTheme_Wassup_URE_TemplateResourceLoaderProcessor::class, PoPTheme_Wassup_URE_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PROFILEORGANIZATION_DETAILS];
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        return array('organizationTypesByName', 'organizationCategoriesByName', 'contactPerson', 'contactNumber');
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret[GD_JS_CLASSES]['label'] = 'label-info';
        $ret[GD_JS_CLASSES]['label2'] = 'label-primary';
        
        // Allow Agenda Urbana to override the titles
        $ret[GD_JS_TITLES] = \PoP\Root\App::applyFilters(
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
