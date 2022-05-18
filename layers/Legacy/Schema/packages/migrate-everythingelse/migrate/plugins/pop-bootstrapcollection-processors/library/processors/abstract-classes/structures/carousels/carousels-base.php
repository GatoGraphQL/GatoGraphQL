<?php

define('POP_COREPROCESSORS_CAROUSELMODE_STATIC', 'static');
define('POP_COREPROCESSORS_CAROUSELMODE_AUTOMATIC', 'automatic');

abstract class PoP_Module_Processor_CarouselsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_CAROUSEL];
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        if ($controls_bottom = $this->getControlsBottomSubmodule($component)) {
            $ret[] = $controls_bottom;
        }
        if ($controls_top = $this->getControlsTopSubmodule($component)) {
            $ret[] = $controls_top;
        }

        // Slideshow with indicators?
        if ($indicators = $this->getLayoutIndicatorSubmodules($component)) {
            $ret[] = $indicators;
        }

        return $ret;
    }

    public function getLayoutIndicatorSubmodules(array $component)
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
    public function getControlsBottomSubmodule(array $component)
    {
        return null;
    }
    public function getControlsTopSubmodule(array $component)
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

        if ($controls_bottom = $this->getControlsBottomSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['controls-bottom'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($controls_bottom);
        }
        if ($controls_top = $this->getControlsTopSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['controls-top'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($controls_top);
        }

        if ($indicators = $this->getLayoutIndicatorSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['carousel-indicators'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($indicators);
            if ($indicators_class = $this->getIndicatorsClass($component, $props)) {
                $ret[GD_JS_CLASSES]['indicators'] = $indicators_class;
            }
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        if ($controls_bottom = $this->getControlsBottomSubmodule($component)) {
            $this->setProp($controls_bottom, $props, 'carousel-module', $component);
        }
        if ($controls_top = $this->getControlsTopSubmodule($component)) {
            $this->setProp($controls_top, $props, 'carousel-module', $component);
        }
        if ($indicators = $this->getLayoutIndicatorSubmodules($component)) {
            $this->setProp($indicators, $props, 'carousel-module', $component);
        }

        parent::initModelProps($component, $props);
    }
}
