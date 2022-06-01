<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_InputGroupFormComponentsBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMCOMPONENT_INPUTGROUP];
    }

    public function getFormcomponentComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return $this->getInputSubcomponent($component);
    }

    public function getControlSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }
    public function getInputSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    public function getInputgroupbtnClass(\PoP\ComponentModel\Component\Component $component)
    {
        return '';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret[GD_JS_CLASSES]['input-group-btn'] = $this->getInputgroupbtnClass($component);

        $counter = 0;
        $keys = array();
        foreach ($this->getControlSubcomponents($component) as $control) {
            $key = 'a'.$counter++;
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES][$key] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($control);
            $keys[] = $key;
        }
        $ret['settings-keys']['controls'] = $keys;

        if ($input = $this->getInputSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['input'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($input);
        }
        return $ret;
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($input = $this->getInputSubcomponent($component)) {
            $ret[] = $input;
        }

        $ret = array_merge(
            $ret,
            $this->getControlSubcomponents($component)
        );

        return $ret;
    }

    public function initRequestProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($component, $props);
        parent::initRequestProps($component, $props);
    }
}
