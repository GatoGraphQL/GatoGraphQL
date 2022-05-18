<?php

abstract class PoP_Module_Processor_CarouselControlsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_CAROUSEL_CONTROLS];
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        $this->addJsmethod($ret, 'carouselControls');

        return $ret;
    }

    public function getControlClass(array $componentVariation)
    {
        return 'carousel-control';
    }
    public function getControlPrevClass(array $componentVariation)
    {
        return '';
    }
    public function getControlNextClass(array $componentVariation)
    {
        return '';
    }
    public function getTitleClass(array $componentVariation)
    {
        return '';
    }
    public function getTitle(array $componentVariation, array &$props)
    {
        return '';
    }
    protected function getTitleLink(array $componentVariation, array &$props)
    {
        return null;
    }
    protected function getTarget(array $componentVariation, array &$props)
    {
        return null;
    }
    protected function getHtmlTag(array $componentVariation, array &$props)
    {
        return 'h4';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret['carousel-module'] = $this->getProp($componentVariation, $props, 'carousel-module');
        $ret['html-tag'] = $this->getProp($componentVariation, $props, 'html-tag');

        if ($control_class = $this->getControlClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['control'] = $control_class;
        }
        if ($control_prev_class = $this->getControlPrevClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['control-prev'] = $control_prev_class;
        }
        if ($control_next_class = $this->getControlNextClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['control-next'] = $control_next_class;
        }
        if ($title = $this->getTitle($componentVariation, $props)) {
            if ($title_class = $this->getProp($componentVariation, $props, 'title-class')) {
                $ret[GD_JS_CLASSES]['title'] = $title_class;
            }
            if ($target = $this->getTarget($componentVariation, $props)) {
                $ret['target'] = $target;
            }
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($componentVariation, $props);

        // Adding in the runtime configuration, because the title/link can change. Eg:
        // the "Latest Thoughts about TPP" in the user profile says "By {{name of org}}"
        if ($title = $this->getTitle($componentVariation, $props)) {
            $ret['title'] = $title;

            // Check if the title_link has been overwritten. If so, use that one. It works
            // only for setting general value, eg: '', and not runtime-specific ones, since
            // it will be cached in pop-cache props
            $title_link = $this->getProp($componentVariation, $props, 'title-link');
            $title_link = isset($title_link) ? $title_link : $this->getTitleLink($componentVariation, $props);
            if ($title_link) {
                $ret['title-link'] = $title_link;
            }
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->setProp($componentVariation, $props, 'title-class', $this->getTitleClass($componentVariation));
        $this->setProp($componentVariation, $props, 'html-tag', $this->getHtmlTag($componentVariation, $props));
        parent::initModelProps($componentVariation, $props);
    }
}
