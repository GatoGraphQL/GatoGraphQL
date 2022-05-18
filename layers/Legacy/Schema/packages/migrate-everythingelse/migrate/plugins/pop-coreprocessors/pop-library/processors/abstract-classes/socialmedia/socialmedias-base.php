<?php

abstract class PoP_Module_Processor_SocialMediaBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SOCIALMEDIA];
    }

    public function useCounter(array $module)
    {
        return false;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($this->useCounter($module)) {
            $this->addJsmethod($ret, 'socialmediaCounter');
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $title = $this->getProp($module, $props, 'title');
        foreach ($this->getSubmodules($module) as $submodule) {
            $this->setProp([$submodule], $props, 'title', $title);
        }

        if ($this->useCounter($module)) {
            foreach ($this->getSubmodules($module) as $submodule) {
                $this->setProp([$submodule], $props, 'load-socialmedia-counter', true);
            }
        }

        $this->appendProp($module, $props, 'class', 'pop-hidden-print');

        parent::initModelProps($module, $props);
    }
}
