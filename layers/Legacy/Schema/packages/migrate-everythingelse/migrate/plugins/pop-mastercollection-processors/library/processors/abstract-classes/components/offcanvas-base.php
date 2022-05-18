<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_OffcanvasBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_OFFCANVAS];
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($this->addClosebutton($module, $props)) {
            $this->addJsmethod($ret, 'offcanvasToggle', 'close');
        }

        return $ret;
    }

    protected function getHtmltag(array $module, array &$props)
    {
        return 'div';
    }

    protected function getClosebuttonTitle(array $module, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('Close', 'pop-mastercollection-processors');
    }

    protected function getClosebuttonClass(array $module, array &$props)
    {
        return 'toggle-side close';
    }

    protected function getWrapperClass(array $module, array &$props)
    {
        return '';
    }

    protected function getContentClass(array $module, array &$props)
    {
        return '';
    }

    protected function addClosebutton(array $module, array &$props)
    {
        return false;
    }

    abstract protected function getOffcanvasClass(array $module, array &$props);

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['html-tag'] = $this->getHtmltag($module, $props);
        if ($wrapper_class = $this->getWrapperClass($module, $props)) {
            $ret[GD_JS_CLASSES]['wrapper'] = $wrapper_class;
        }
        if ($content_class = $this->getContentClass($module, $props)) {
            $ret[GD_JS_CLASSES]['content'] = $content_class;
        }
        if ($this->addClosebutton($module, $props)) {
            $ret['add-closebutton'] = true;
            if ($closebutton_class = $this->getClosebuttonClass($module, $props)) {
                $ret[GD_JS_CLASSES]['closebutton'] = $closebutton_class;
            }
            if ($closebutton_title = $this->getClosebuttonTitle($module, $props)) {
                $ret[GD_JS_TITLES]['closebutton'] = $closebutton_title;
            }
        }
        if ($submodules = $this->getSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['elements'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $submodules
            );
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $offcanvas_class = $this->getOffcanvasClass($module, $props);
        $this->appendProp($module, $props, 'class', 'offcanvas '.$offcanvas_class);
        $this->mergeProp(
            $module,
            $props,
            'params',
            array(
                'data-offcanvas' => $offcanvas_class,
            )
        );

        parent::initModelProps($module, $props);
    }
}
