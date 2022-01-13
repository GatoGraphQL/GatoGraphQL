<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_CarouselComponentsBase extends PoP_Module_Processor_PanelBootstrapComponentsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_CAROUSEL];
    }

    protected function isMandatoryActivePanel(array $module)
    {
        return true;
    }

    public function getPanelHeaderType(array $module)
    {
        return 'indicators';
    }

    public function getCarouselClass(array $module)
    {
        return 'slide';
    }
    public function getCarouselParams(array $module)
    {
        return array(
            'data-interval' => false,
            'data-wrap' => false,
            'data-ride' => 'carousel'
        );
    }

    public function getPanelactiveClass(array $module)
    {
        return 'active';
    }

    public function getBootstrapcomponentType(array $module)
    {
        return 'carousel';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($carousel_class = $this->getCarouselClass($module)) {
            $ret[GD_JS_CLASSES]['carousel'] = $carousel_class;
        }
        if ($carousel_params = $this->getCarouselParams($module)) {
            $ret['carousel-params'] = $carousel_params;
        }
        $header_type = $this->getPanelHeaderType($module);
        if ($header_type == 'prevnext') {
            $ret[GD_JS_TITLES]['prev'] = sprintf(
                TranslationAPIFacade::getInstance()->__('%sPrev', 'poptheme-wassup'),
                '<i class="fa fa-fw fa-chevron-left"></i>'
            );
            $ret[GD_JS_TITLES]['next'] = sprintf(
                TranslationAPIFacade::getInstance()->__('Next%s', 'poptheme-wassup'),
                '<i class="fa fa-fw fa-chevron-right"></i>'
            );
        }
                
        return $ret;
    }
}
