<?php

define('POP_COREPROCESSORS_CAROUSELMODE_STATIC', 'static');
define('POP_COREPROCESSORS_CAROUSELMODE_AUTOMATIC', 'automatic');

abstract class PoP_Module_Processor_CarouselsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_CAROUSEL];
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($controls_bottom = $this->getControlsBottomSubcomponent($component)) {
            $ret[] = $controls_bottom;
        }
        if ($controls_top = $this->getControlsTopSubcomponent($component)) {
            $ret[] = $controls_top;
        }

        // Slideshow with indicators?
        if ($indicators = $this->getLayoutIndicatorSubcomponents($component)) {
            $ret[] = $indicators;
        }

        return $ret;
    }

    public function getLayoutIndicatorSubcomponents(array $component)
    {
        return null;
    }
    public function getMode(array $component, array &$props)
    {
        return POP_COREPROCESSORS_CAROUSELMODE_STATIC;
    }
    // function showControls(array $component, array &$props) {

    //     return false;
    // }
    public function getControlsBottomSubcomponent(array $component)
    {
        return null;
    }
    public function getControlsTopSubcomponent(array $component)
    {
        return null;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->getMode($component, $props) == POP_COREPROCESSORS_CAROUSELMODE_STATIC) {
            $this->addJsmethod($ret, 'carouselStatic');
        } elseif ($this->getMode($component, $props) == POP_COREPROCESSORS_CAROUSELMODE_AUTOMATIC) {
            $this->addJsmethod($ret, 'carouselAutomatic');
        }

        return $ret;
    }

    public function getIndicatorsClass(array $component, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['mode'] = $this->getMode($component, $props);

        if ($controls_bottom = $this->getControlsBottomSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['controls-bottom'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($controls_bottom);
        }
        if ($controls_top = $this->getControlsTopSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['controls-top'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($controls_top);
        }

        if ($indicators = $this->getLayoutIndicatorSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['carousel-indicators'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($indicators);
            if ($indicators_class = $this->getIndicatorsClass($component, $props)) {
                $ret[GD_JS_CLASSES]['indicators'] = $indicators_class;
            }
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        if ($controls_bottom = $this->getControlsBottomSubcomponent($component)) {
            $this->setProp($controls_bottom, $props, 'carousel-component', $component);
        }
        if ($controls_top = $this->getControlsTopSubcomponent($component)) {
            $this->setProp($controls_top, $props, 'carousel-component', $component);
        }
        if ($indicators = $this->getLayoutIndicatorSubcomponents($component)) {
            $this->setProp($indicators, $props, 'carousel-component', $component);
        }

        parent::initModelProps($component, $props);
    }
}
