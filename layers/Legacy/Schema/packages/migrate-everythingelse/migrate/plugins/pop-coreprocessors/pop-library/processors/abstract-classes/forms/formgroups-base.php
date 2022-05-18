<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_FormGroupsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMGROUP];
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        $this->addJsmethod($ret, 'tooltip', 'tooltip');

        return $ret;
    }

    public function getLabel(array $component, array &$props)
    {
        return '';
    }

    public function getComponentSubmodule(array $component)
    {
        return null;
    }

    public function getLabelClass(array $component)
    {
        return 'control-label';
    }
    public function getFormcontrolClass(array $component)
    {
        return '';
    }
    public function getInfo(array $component, array &$props)
    {
        return '';
    }
    public function getDescription(array $component, array &$props)
    {
        return '';
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);
        $ret[] = $this->getComponentSubmodule($component);
        return $ret;
    }

    public function getComponentName(array $component)
    {

        // This property is needed for the inheriting class FormComponentGroupsBase, to print the name of the formcomponent
        // We initialize it here as the inner module, however at this stage, FormGroupsBase, it is not really needed
        return $this->getComponentSubmodule($component);
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);

        $component = $this->getComponentSubmodule($component);
        $component_processor = $componentprocessor_manager->getProcessor($component);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['component'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($component);

        // Re-use the label from the component
        if ($label = $this->getProp($component, $props, 'label')) {
            $ret['label'] = $label;
        }
        if ($label_class = $this->getLabelClass($component)) {
            $ret[GD_JS_CLASSES]['label'] = $label_class;
        }
        if ($formcontrol_class = $this->getFormcontrolClass($component)) {
            $ret[GD_JS_CLASSES]['formcontrol'] = $formcontrol_class;
        }
        if ($info = $this->getInfo($component, $props)) {
            $ret['info'] = $info;
        }
        if ($description = $this->getDescription($component, $props)) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }

        // This property is needed for the inheriting class FormComponentGroupsBase, to print the name of the formcomponent
        $ret['component-name'] = $this->getComponentName($component);

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // No need for the input to have a label or a placeholder (for the text inputs) anymore
        $label = $this->getLabel($component, $props);
        $this->setProp($component, $props, 'label', $label);

        $this->appendProp($component, $props, 'class', 'form-group');
        parent::initModelProps($component, $props);
    }
}
