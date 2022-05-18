<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_ScrollsBase extends PoP_Module_Processor_StructuresBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SCROLL];
    }

    protected function getDescription(array $componentVariation, array &$props)
    {
        return null;
    }

    public function getFetchmoreButtonSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_FetchMore::class, PoP_Module_Processor_FetchMore::MODULE_FETCHMORE];
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($fetchmore = $this->getFetchmoreButtonSubmodule($componentVariation)) {
            $ret[] = $fetchmore;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        if ($this->useFetchmore($componentVariation, $props)) {
            $fetchmore = $this->getFetchmoreButtonSubmodule($componentVariation);
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['fetchmore'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($fetchmore);
        }
        if ($description = $this->getProp($componentVariation, $props, 'description')) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }
        if ($inner_class = $this->getProp($componentVariation, $props, 'inner-class')) {
            $ret[GD_JS_CLASSES]['inner'] = $inner_class;
        }

        return $ret;
    }

    protected function useFetchmore(array $componentVariation, array &$props)
    {
        return $this->getFetchmoreButtonSubmodule($componentVariation) && $this->getProp($componentVariation, $props, 'show-fetchmore');
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->setProp($componentVariation, $props, 'show-fetchmore', true);
        $this->setProp($componentVariation, $props, 'description', $this->getDescription($componentVariation, $props));
        parent::initModelProps($componentVariation, $props);
    }
}
