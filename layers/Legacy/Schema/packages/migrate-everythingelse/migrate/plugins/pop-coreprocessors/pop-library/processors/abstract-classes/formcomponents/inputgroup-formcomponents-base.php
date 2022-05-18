<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_InputGroupFormComponentsBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMCOMPONENT_INPUTGROUP];
    }

    public function getFormcomponentModule(array $component)
    {
        return $this->getInputSubmodule($component);
    }

    public function getControlSubmodules(array $component)
    {
        return array();
    }
    public function getInputSubmodule(array $component)
    {
        return null;
    }
    public function getInputgroupbtnClass(array $component)
    {
        return '';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret[GD_JS_CLASSES]['input-group-btn'] = $this->getInputgroupbtnClass($component);

        $counter = 0;
        $keys = array();
        foreach ($this->getControlSubmodules($component) as $control) {
            $key = 'a'.$counter++;
            $ret[GD_JS_SUBMODULEOUTPUTNAMES][$key] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($control);
            $keys[] = $key;
        }
        $ret['settings-keys']['controls'] = $keys;

        if ($input = $this->getInputSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['input'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($input);
        }
        return $ret;
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        if ($input = $this->getInputSubmodule($component)) {
            $ret[] = $input;
        }

        $ret = array_merge(
            $ret,
            $this->getControlSubmodules($component)
        );

        return $ret;
    }

    public function initRequestProps(array $component, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($component, $props);
        parent::initRequestProps($component, $props);
    }
}
