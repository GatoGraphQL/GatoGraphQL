<?php

define('POP_COREPROCESSORS_CAROUSELMODE_STATIC', 'static');
define('POP_COREPROCESSORS_CAROUSELMODE_AUTOMATIC', 'automatic');

abstract class PoP_Module_Processor_CarouselsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_CAROUSEL];
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($controls_bottom = $this->getControlsBottomSubmodule($componentVariation)) {
            $ret[] = $controls_bottom;
        }
        if ($controls_top = $this->getControlsTopSubmodule($componentVariation)) {
            $ret[] = $controls_top;
        }

        // Slideshow with indicators?
        if ($indicators = $this->getLayoutIndicatorSubmodules($componentVariation)) {
            $ret[] = $indicators;
        }

        return $ret;
    }

    public function getLayoutIndicatorSubmodules(array $componentVariation)
    {
        return null;
    }
    public function getMode(array $componentVariation, array &$props)
    {
        return POP_COREPROCESSORS_CAROUSELMODE_STATIC;
    }
    // function showControls(array $componentVariation, array &$props) {

    //     return false;
    // }
    public function getControlsBottomSubmodule(array $componentVariation)
    {
        return null;
    }
    public function getControlsTopSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($this->getMode($componentVariation, $props) == POP_COREPROCESSORS_CAROUSELMODE_STATIC) {
            $this->addJsmethod($ret, 'carouselStatic');
        } elseif ($this->getMode($componentVariation, $props) == POP_COREPROCESSORS_CAROUSELMODE_AUTOMATIC) {
            $this->addJsmethod($ret, 'carouselAutomatic');
        }

        return $ret;
    }

    public function getIndicatorsClass(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret['mode'] = $this->getMode($componentVariation, $props);

        if ($controls_bottom = $this->getControlsBottomSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['controls-bottom'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($controls_bottom);
        }
        if ($controls_top = $this->getControlsTopSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['controls-top'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($controls_top);
        }

        if ($indicators = $this->getLayoutIndicatorSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['carousel-indicators'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($indicators);
            if ($indicators_class = $this->getIndicatorsClass($componentVariation, $props)) {
                $ret[GD_JS_CLASSES]['indicators'] = $indicators_class;
            }
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($controls_bottom = $this->getControlsBottomSubmodule($componentVariation)) {
            $this->setProp($controls_bottom, $props, 'carousel-module', $componentVariation);
        }
        if ($controls_top = $this->getControlsTopSubmodule($componentVariation)) {
            $this->setProp($controls_top, $props, 'carousel-module', $componentVariation);
        }
        if ($indicators = $this->getLayoutIndicatorSubmodules($componentVariation)) {
            $this->setProp($indicators, $props, 'carousel-module', $componentVariation);
        }

        parent::initModelProps($componentVariation, $props);
    }
}
