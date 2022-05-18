<?php

abstract class PoP_Module_Processor_CarouselControlsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_CAROUSEL_CONTROLS];
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        $this->addJsmethod($ret, 'carouselControls');

        return $ret;
    }

    public function getControlClass(array $component)
    {
        return 'carousel-control';
    }
    public function getControlPrevClass(array $component)
    {
        return '';
    }
    public function getControlNextClass(array $component)
    {
        return '';
    }
    public function getTitleClass(array $component)
    {
        return '';
    }
    public function getTitle(array $component, array &$props)
    {
        return '';
    }
    protected function getTitleLink(array $component, array &$props)
    {
        return null;
    }
    protected function getTarget(array $component, array &$props)
    {
        return null;
    }
    protected function getHtmlTag(array $component, array &$props)
    {
        return 'h4';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['carousel-module'] = $this->getProp($component, $props, 'carousel-module');
        $ret['html-tag'] = $this->getProp($component, $props, 'html-tag');

        if ($control_class = $this->getControlClass($component)) {
            $ret[GD_JS_CLASSES]['control'] = $control_class;
        }
        if ($control_prev_class = $this->getControlPrevClass($component)) {
            $ret[GD_JS_CLASSES]['control-prev'] = $control_prev_class;
        }
        if ($control_next_class = $this->getControlNextClass($component)) {
            $ret[GD_JS_CLASSES]['control-next'] = $control_next_class;
        }
        if ($title = $this->getTitle($component, $props)) {
            if ($title_class = $this->getProp($component, $props, 'title-class')) {
                $ret[GD_JS_CLASSES]['title'] = $title_class;
            }
            if ($target = $this->getTarget($component, $props)) {
                $ret['target'] = $target;
            }
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        // Adding in the runtime configuration, because the title/link can change. Eg:
        // the "Latest Thoughts about TPP" in the user profile says "By {{name of org}}"
        if ($title = $this->getTitle($component, $props)) {
            $ret['title'] = $title;

            // Check if the title_link has been overwritten. If so, use that one. It works
            // only for setting general value, eg: '', and not runtime-specific ones, since
            // it will be cached in pop-cache props
            $title_link = $this->getProp($component, $props, 'title-link');
            $title_link = isset($title_link) ? $title_link : $this->getTitleLink($component, $props);
            if ($title_link) {
                $ret['title-link'] = $title_link;
            }
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->setProp($component, $props, 'title-class', $this->getTitleClass($component));
        $this->setProp($component, $props, 'html-tag', $this->getHtmlTag($component, $props));
        parent::initModelProps($component, $props);
    }
}
