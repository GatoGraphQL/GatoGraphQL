<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_TagInfoLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_TAGINFO];
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(array $component, array &$props): array
    {
        return array('count');
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
    
        $ret[GD_JS_TITLES] = array(
            'count' => TranslationAPIFacade::getInstance()->__('Count', 'pop-coreprocessors'),
        );
        
        return $ret;
    }
}
