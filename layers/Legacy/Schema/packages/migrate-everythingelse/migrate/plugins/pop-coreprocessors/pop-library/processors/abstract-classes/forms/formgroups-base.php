<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_FormGroupsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMGROUP];
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        $this->addJsmethod($ret, 'tooltip', 'tooltip');

        return $ret;
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    public function getLabelClass(\PoP\ComponentModel\Component\Component $component)
    {
        return 'control-label';
    }
    public function getFormcontrolClass(\PoP\ComponentModel\Component\Component $component)
    {
        return '';
    }
    public function getInfo(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }
    public function getDescription(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
        $ret[] = $this->getComponentSubcomponent($component);
        return $ret;
    }

    public function getComponentName(\PoP\ComponentModel\Component\Component $component)
    {

        // This property is needed for the inheriting class FormComponentGroupsBase, to print the name of the formcomponent
        // We initialize it here as the inner module, however at this stage, FormGroupsBase, it is not really needed
        return $this->getComponentSubcomponent($component);
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);

        $component = $this->getComponentSubcomponent($component);
        $component_processor = $componentprocessor_manager->getProcessor($component);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['component'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($component);

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

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {

        // No need for the input to have a label or a placeholder (for the text inputs) anymore
        $label = $this->getLabel($component, $props);
        $this->setProp($component, $props, 'label', $label);

        $this->appendProp($component, $props, 'class', 'form-group');
        parent::initModelProps($component, $props);
    }
}
