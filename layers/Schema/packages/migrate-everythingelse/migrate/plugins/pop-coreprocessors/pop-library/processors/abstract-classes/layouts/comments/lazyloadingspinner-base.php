<?php
use PoP\Translation\Facades\TranslationAPIFacade;

abstract class PoP_Module_Processor_LazyLoadingSpinnerLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_LAZYLOADINGSPINNER];
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['codes']['spinner'] = sprintf(
            '<div class="pop-lazyload-loading">%s</div>',
            GD_CONSTANT_LOADING_SPINNER.' '.TranslationAPIFacade::getInstance()->__('Loading data', 'poptheme-wassup')
        );
        
        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {
        $this->setProp($module, $props, 'appendable', true);
        $this->setProp($module, $props, 'appendable-class', GD_CLASS_SPINNER);

        parent::initModelProps($module, $props);
    }
}
