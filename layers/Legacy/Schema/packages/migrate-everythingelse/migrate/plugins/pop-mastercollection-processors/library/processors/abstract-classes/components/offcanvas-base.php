<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_OffcanvasBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_OFFCANVAS];
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($this->addClosebutton($componentVariation, $props)) {
            $this->addJsmethod($ret, 'offcanvasToggle', 'close');
        }

        return $ret;
    }

    protected function getHtmltag(array $componentVariation, array &$props)
    {
        return 'div';
    }

    protected function getClosebuttonTitle(array $componentVariation, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('Close', 'pop-mastercollection-processors');
    }

    protected function getClosebuttonClass(array $componentVariation, array &$props)
    {
        return 'toggle-side close';
    }

    protected function getWrapperClass(array $componentVariation, array &$props)
    {
        return '';
    }

    protected function getContentClass(array $componentVariation, array &$props)
    {
        return '';
    }

    protected function addClosebutton(array $componentVariation, array &$props)
    {
        return false;
    }

    abstract protected function getOffcanvasClass(array $componentVariation, array &$props);

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret['html-tag'] = $this->getHtmltag($componentVariation, $props);
        if ($wrapper_class = $this->getWrapperClass($componentVariation, $props)) {
            $ret[GD_JS_CLASSES]['wrapper'] = $wrapper_class;
        }
        if ($content_class = $this->getContentClass($componentVariation, $props)) {
            $ret[GD_JS_CLASSES]['content'] = $content_class;
        }
        if ($this->addClosebutton($componentVariation, $props)) {
            $ret['add-closebutton'] = true;
            if ($closebutton_class = $this->getClosebuttonClass($componentVariation, $props)) {
                $ret[GD_JS_CLASSES]['closebutton'] = $closebutton_class;
            }
            if ($closebutton_title = $this->getClosebuttonTitle($componentVariation, $props)) {
                $ret[GD_JS_TITLES]['closebutton'] = $closebutton_title;
            }
        }
        if ($submodules = $this->getSubComponentVariations($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['elements'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $submodules
            );
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $offcanvas_class = $this->getOffcanvasClass($componentVariation, $props);
        $this->appendProp($componentVariation, $props, 'class', 'offcanvas '.$offcanvas_class);
        $this->mergeProp(
            $componentVariation,
            $props,
            'params',
            array(
                'data-offcanvas' => $offcanvas_class,
            )
        );

        parent::initModelProps($componentVariation, $props);
    }
}
