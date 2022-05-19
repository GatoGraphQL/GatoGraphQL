<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_OffcanvasBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_OFFCANVAS];
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->addClosebutton($component, $props)) {
            $this->addJsmethod($ret, 'offcanvasToggle', 'close');
        }

        return $ret;
    }

    protected function getHtmltag(array $component, array &$props)
    {
        return 'div';
    }

    protected function getClosebuttonTitle(array $component, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('Close', 'pop-mastercollection-processors');
    }

    protected function getClosebuttonClass(array $component, array &$props)
    {
        return 'toggle-side close';
    }

    protected function getWrapperClass(array $component, array &$props)
    {
        return '';
    }

    protected function getContentClass(array $component, array &$props)
    {
        return '';
    }

    protected function addClosebutton(array $component, array &$props)
    {
        return false;
    }

    abstract protected function getOffcanvasClass(array $component, array &$props);

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['html-tag'] = $this->getHtmltag($component, $props);
        if ($wrapper_class = $this->getWrapperClass($component, $props)) {
            $ret[GD_JS_CLASSES]['wrapper'] = $wrapper_class;
        }
        if ($content_class = $this->getContentClass($component, $props)) {
            $ret[GD_JS_CLASSES]['content'] = $content_class;
        }
        if ($this->addClosebutton($component, $props)) {
            $ret['add-closebutton'] = true;
            if ($closebutton_class = $this->getClosebuttonClass($component, $props)) {
                $ret[GD_JS_CLASSES]['closebutton'] = $closebutton_class;
            }
            if ($closebutton_title = $this->getClosebuttonTitle($component, $props)) {
                $ret[GD_JS_TITLES]['closebutton'] = $closebutton_title;
            }
        }
        if ($subComponents = $this->getSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['elements'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance(), 'getModuleOutputName'],
                $subComponents
            );
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $offcanvas_class = $this->getOffcanvasClass($component, $props);
        $this->appendProp($component, $props, 'class', 'offcanvas '.$offcanvas_class);
        $this->mergeProp(
            $component,
            $props,
            'params',
            array(
                'data-offcanvas' => $offcanvas_class,
            )
        );

        parent::initModelProps($component, $props);
    }
}
