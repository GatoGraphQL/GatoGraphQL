<?php

abstract class PoP_Module_Processor_SocialMediaBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SOCIALMEDIA];
    }

    public function useCounter(array $component)
    {
        return false;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->useCounter($component)) {
            $this->addJsmethod($ret, 'socialmediaCounter');
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $title = $this->getProp($component, $props, 'title');
        foreach ($this->getSubComponents($component) as $subComponent) {
            $this->setProp([$subComponent], $props, 'title', $title);
        }

        if ($this->useCounter($component)) {
            foreach ($this->getSubComponents($component) as $subComponent) {
                $this->setProp([$subComponent], $props, 'load-socialmedia-counter', true);
            }
        }

        $this->appendProp($component, $props, 'class', 'pop-hidden-print');

        parent::initModelProps($component, $props);
    }
}
