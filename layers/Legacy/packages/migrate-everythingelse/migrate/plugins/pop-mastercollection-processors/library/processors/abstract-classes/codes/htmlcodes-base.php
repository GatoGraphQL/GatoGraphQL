<?php

abstract class PoP_Module_Processor_HTMLCodesBase extends PoP_Module_Processor_CodesBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_HTMLCODE];
    }

    public function getHtmlTag(array $module, array &$props)
    {
        return 'div';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);
    
        if ($html_tag = $this->getHtmlTag($module, $props)) {
            $ret['html-tag'] = $html_tag;
        }
        
        return $ret;
    }
}
