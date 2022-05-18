<?php

abstract class PoP_Module_Processor_CarouselControlsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_CAROUSEL_CONTROLS];
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        $this->addJsmethod($ret, 'carouselControls');

        return $ret;
    }

    public function getControlClass(array $module)
    {
        return 'carousel-control';
    }
    public function getControlPrevClass(array $module)
    {
        return '';
    }
    public function getControlNextClass(array $module)
    {
        return '';
    }
    public function getTitleClass(array $module)
    {
        return '';
    }
    public function getTitle(array $module, array &$props)
    {
        return '';
    }
    protected function getTitleLink(array $module, array &$props)
    {
        return null;
    }
    protected function getTarget(array $module, array &$props)
    {
        return null;
    }
    protected function getHtmlTag(array $module, array &$props)
    {
        return 'h4';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['carousel-module'] = $this->getProp($module, $props, 'carousel-module');
        $ret['html-tag'] = $this->getProp($module, $props, 'html-tag');

        if ($control_class = $this->getControlClass($module)) {
            $ret[GD_JS_CLASSES]['control'] = $control_class;
        }
        if ($control_prev_class = $this->getControlPrevClass($module)) {
            $ret[GD_JS_CLASSES]['control-prev'] = $control_prev_class;
        }
        if ($control_next_class = $this->getControlNextClass($module)) {
            $ret[GD_JS_CLASSES]['control-next'] = $control_next_class;
        }
        if ($title = $this->getTitle($module, $props)) {
            if ($title_class = $this->getProp($module, $props, 'title-class')) {
                $ret[GD_JS_CLASSES]['title'] = $title_class;
            }
            if ($target = $this->getTarget($module, $props)) {
                $ret['target'] = $target;
            }
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($module, $props);

        // Adding in the runtime configuration, because the title/link can change. Eg:
        // the "Latest Thoughts about TPP" in the user profile says "By {{name of org}}"
        if ($title = $this->getTitle($module, $props)) {
            $ret['title'] = $title;

            // Check if the title_link has been overwritten. If so, use that one. It works
            // only for setting general value, eg: '', and not runtime-specific ones, since
            // it will be cached in pop-cache props
            $title_link = $this->getProp($module, $props, 'title-link');
            $title_link = isset($title_link) ? $title_link : $this->getTitleLink($module, $props);
            if ($title_link) {
                $ret['title-link'] = $title_link;
            }
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->setProp($module, $props, 'title-class', $this->getTitleClass($module));
        $this->setProp($module, $props, 'html-tag', $this->getHtmlTag($module, $props));
        parent::initModelProps($module, $props);
    }
}
