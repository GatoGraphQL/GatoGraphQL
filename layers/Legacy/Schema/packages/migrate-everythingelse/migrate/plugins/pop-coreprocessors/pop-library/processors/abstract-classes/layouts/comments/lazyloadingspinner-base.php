<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LazyLoadingSpinnerLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_LAZYLOADINGSPINNER];
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['codes']['spinner'] = sprintf(
            '<div class="pop-lazyload-loading">%s</div>',
            GD_CONSTANT_LOADING_SPINNER.' '.TranslationAPIFacade::getInstance()->__('Loading data', 'poptheme-wassup')
        );

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->setProp($component, $props, 'appendable', true);
        $this->setProp($component, $props, 'appendable-class', GD_CLASS_SPINNER);

        parent::initModelProps($component, $props);
    }
}
