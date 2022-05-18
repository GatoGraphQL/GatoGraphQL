<?php

define('POP_COREPROCESSORS_CAROUSELMODE_STATIC', 'static');
define('POP_COREPROCESSORS_CAROUSELMODE_AUTOMATIC', 'automatic');

abstract class PoP_Module_Processor_CarouselsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_CAROUSEL];
    }

    public function getSubComponentVariations(array $module): array
    {
        $ret = parent::getSubComponentVariations($module);

        if ($controls_bottom = $this->getControlsBottomSubmodule($module)) {
            $ret[] = $controls_bottom;
        }
        if ($controls_top = $this->getControlsTopSubmodule($module)) {
            $ret[] = $controls_top;
        }

        // Slideshow with indicators?
        if ($indicators = $this->getLayoutIndicatorSubmodules($module)) {
            $ret[] = $indicators;
        }

        return $ret;
    }

    public function getLayoutIndicatorSubmodules(array $module)
    {
        return null;
    }
    public function getMode(array $module, array &$props)
    {
        return POP_COREPROCESSORS_CAROUSELMODE_STATIC;
    }
    // function showControls(array $module, array &$props) {

    //     return false;
    // }
    public function getControlsBottomSubmodule(array $module)
    {
        return null;
    }
    public function getControlsTopSubmodule(array $module)
    {
        return null;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($this->getMode($module, $props) == POP_COREPROCESSORS_CAROUSELMODE_STATIC) {
            $this->addJsmethod($ret, 'carouselStatic');
        } elseif ($this->getMode($module, $props) == POP_COREPROCESSORS_CAROUSELMODE_AUTOMATIC) {
            $this->addJsmethod($ret, 'carouselAutomatic');
        }

        return $ret;
    }

    public function getIndicatorsClass(array $module, array &$props)
    {
        return '';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['mode'] = $this->getMode($module, $props);

        if ($controls_bottom = $this->getControlsBottomSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['controls-bottom'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($controls_bottom);
        }
        if ($controls_top = $this->getControlsTopSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['controls-top'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($controls_top);
        }

        if ($indicators = $this->getLayoutIndicatorSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['carousel-indicators'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($indicators);
            if ($indicators_class = $this->getIndicatorsClass($module, $props)) {
                $ret[GD_JS_CLASSES]['indicators'] = $indicators_class;
            }
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($controls_bottom = $this->getControlsBottomSubmodule($module)) {
            $this->setProp($controls_bottom, $props, 'carousel-module', $module);
        }
        if ($controls_top = $this->getControlsTopSubmodule($module)) {
            $this->setProp($controls_top, $props, 'carousel-module', $module);
        }
        if ($indicators = $this->getLayoutIndicatorSubmodules($module)) {
            $this->setProp($indicators, $props, 'carousel-module', $module);
        }

        parent::initModelProps($module, $props);
    }
}
