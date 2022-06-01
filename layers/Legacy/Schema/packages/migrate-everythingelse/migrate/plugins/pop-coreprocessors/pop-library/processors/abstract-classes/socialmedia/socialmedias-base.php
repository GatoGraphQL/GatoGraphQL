<?php

abstract class PoP_Module_Processor_SocialMediaBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SOCIALMEDIA];
    }

    public function useCounter(\PoP\ComponentModel\Component\Component $component)
    {
        return false;
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->useCounter($component)) {
            $this->addJsmethod($ret, 'socialmediaCounter');
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $title = $this->getProp($component, $props, 'title');
        foreach ($this->getSubcomponents($component) as $subComponent) {
            $this->setProp([$subComponent], $props, 'title', $title);
        }

        if ($this->useCounter($component)) {
            foreach ($this->getSubcomponents($component) as $subComponent) {
                $this->setProp([$subComponent], $props, 'load-socialmedia-counter', true);
            }
        }

        $this->appendProp($component, $props, 'class', 'pop-hidden-print');

        parent::initModelProps($component, $props);
    }
}
