<?php

abstract class PoP_Module_Processor_SocialMediaBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SOCIALMEDIA];
    }

    public function useCounter(array $componentVariation)
    {
        return false;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($this->useCounter($componentVariation)) {
            $this->addJsmethod($ret, 'socialmediaCounter');
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $title = $this->getProp($componentVariation, $props, 'title');
        foreach ($this->getSubComponentVariations($componentVariation) as $subComponentVariation) {
            $this->setProp([$subComponentVariation], $props, 'title', $title);
        }

        if ($this->useCounter($componentVariation)) {
            foreach ($this->getSubComponentVariations($componentVariation) as $subComponentVariation) {
                $this->setProp([$subComponentVariation], $props, 'load-socialmedia-counter', true);
            }
        }

        $this->appendProp($componentVariation, $props, 'class', 'pop-hidden-print');

        parent::initModelProps($componentVariation, $props);
    }
}
