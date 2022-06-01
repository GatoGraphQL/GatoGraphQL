<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_CarouselComponentsBase extends PoP_Module_Processor_PanelBootstrapComponentsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BOOTSTRAPCOMPONENT_CAROUSEL];
    }

    protected function isMandatoryActivePanel(\PoP\ComponentModel\Component\Component $component)
    {
        return true;
    }

    public function getPanelHeaderType(\PoP\ComponentModel\Component\Component $component)
    {
        return 'indicators';
    }

    public function getCarouselClass(\PoP\ComponentModel\Component\Component $component)
    {
        return 'slide';
    }
    public function getCarouselParams(\PoP\ComponentModel\Component\Component $component)
    {
        return array(
            'data-interval' => false,
            'data-wrap' => false,
            'data-ride' => 'carousel'
        );
    }

    public function getPanelactiveClass(\PoP\ComponentModel\Component\Component $component)
    {
        return 'active';
    }

    public function getBootstrapcomponentType(\PoP\ComponentModel\Component\Component $component)
    {
        return 'carousel';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
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
