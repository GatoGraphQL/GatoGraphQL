<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_CarouselComponentsBase extends PoP_Module_Processor_PanelBootstrapComponentsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_CAROUSEL];
    }

    protected function isMandatoryActivePanel(array $component)
    {
        return true;
    }

    public function getPanelHeaderType(array $component)
    {
        return 'indicators';
    }

    public function getCarouselClass(array $component)
    {
        return 'slide';
    }
    public function getCarouselParams(array $component)
    {
        return array(
            'data-interval' => false,
            'data-wrap' => false,
            'data-ride' => 'carousel'
        );
    }

    public function getPanelactiveClass(array $component)
    {
        return 'active';
    }

    public function getBootstrapcomponentType(array $component)
    {
        return 'carousel';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($carousel_class = $this->getCarouselClass($component)) {
            $ret[GD_JS_CLASSES]['carousel'] = $carousel_class;
        }
        if ($carousel_params = $this->getCarouselParams($component)) {
            $ret['carousel-params'] = $carousel_params;
        }
        $header_type = $this->getPanelHeaderType($component);
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
