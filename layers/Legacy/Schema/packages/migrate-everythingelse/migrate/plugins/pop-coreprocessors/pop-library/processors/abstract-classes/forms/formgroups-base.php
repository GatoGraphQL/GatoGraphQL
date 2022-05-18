<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_FormGroupsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMGROUP];
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        $this->addJsmethod($ret, 'tooltip', 'tooltip');

        return $ret;
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getLabelClass(array $componentVariation)
    {
        return 'control-label';
    }
    public function getFormcontrolClass(array $componentVariation)
    {
        return '';
    }
    public function getInfo(array $componentVariation, array &$props)
    {
        return '';
    }
    public function getDescription(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);
        $ret[] = $this->getComponentSubmodule($componentVariation);
        return $ret;
    }

    public function getComponentName(array $componentVariation)
    {

        // This property is needed for the inheriting class FormComponentGroupsBase, to print the name of the formcomponent
        // We initialize it here as the inner module, however at this stage, FormGroupsBase, it is not really needed
        return $this->getComponentSubmodule($componentVariation);
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $component = $this->getComponentSubmodule($componentVariation);
        $component_processor = $componentprocessor_manager->getProcessor($component);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['component-variation'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($component);

        // Re-use the label from the component
        if ($label = $this->getProp($componentVariation, $props, 'label')) {
            $ret['label'] = $label;
        }
        if ($label_class = $this->getLabelClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['label'] = $label_class;
        }
        if ($formcontrol_class = $this->getFormcontrolClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['formcontrol'] = $formcontrol_class;
        }
        if ($info = $this->getInfo($componentVariation, $props)) {
            $ret['info'] = $info;
        }
        if ($description = $this->getDescription($componentVariation, $props)) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }

        // This property is needed for the inheriting class FormComponentGroupsBase, to print the name of the formcomponent
        $ret['component-name'] = $this->getComponentName($componentVariation);

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // No need for the input to have a label or a placeholder (for the text inputs) anymore
        $label = $this->getLabel($componentVariation, $props);
        $this->setProp($componentVariation, $props, 'label', $label);

        $this->appendProp($componentVariation, $props, 'class', 'form-group');
        parent::initModelProps($componentVariation, $props);
    }
}
