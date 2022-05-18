<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_InputGroupFormComponentsBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMCOMPONENT_INPUTGROUP];
    }

    public function getFormcomponentModule(array $componentVariation)
    {
        return $this->getInputSubmodule($componentVariation);
    }

    public function getControlSubmodules(array $componentVariation)
    {
        return array();
    }
    public function getInputSubmodule(array $componentVariation)
    {
        return null;
    }
    public function getInputgroupbtnClass(array $componentVariation)
    {
        return '';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret[GD_JS_CLASSES]['input-group-btn'] = $this->getInputgroupbtnClass($componentVariation);

        $counter = 0;
        $keys = array();
        foreach ($this->getControlSubmodules($componentVariation) as $control) {
            $key = 'a'.$counter++;
            $ret[GD_JS_SUBMODULEOUTPUTNAMES][$key] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($control);
            $keys[] = $key;
        }
        $ret['settings-keys']['controls'] = $keys;

        if ($input = $this->getInputSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['input'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($input);
        }
        return $ret;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($input = $this->getInputSubmodule($componentVariation)) {
            $ret[] = $input;
        }

        $ret = array_merge(
            $ret,
            $this->getControlSubmodules($componentVariation)
        );

        return $ret;
    }

    public function initRequestProps(array $componentVariation, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($componentVariation, $props);
        parent::initRequestProps($componentVariation, $props);
    }
}
