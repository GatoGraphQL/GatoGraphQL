<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_FormGroupsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMGROUP];
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        $this->addJsmethod($ret, 'tooltip', 'tooltip');

        return $ret;
    }

    public function getLabel(array $module, array &$props)
    {
        return '';
    }

    public function getComponentSubmodule(array $module)
    {
        return null;
    }

    public function getLabelClass(array $module)
    {
        return 'control-label';
    }
    public function getFormcontrolClass(array $module)
    {
        return '';
    }
    public function getInfo(array $module, array &$props)
    {
        return '';
    }
    public function getDescription(array $module, array &$props)
    {
        return '';
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
        $ret[] = $this->getComponentSubmodule($module);
        return $ret;
    }

    public function getComponentName(array $module)
    {

        // This property is needed for the inheriting class FormComponentGroupsBase, to print the name of the formcomponent
        // We initialize it here as the inner module, however at this stage, FormGroupsBase, it is not really needed
        return $this->getComponentSubmodule($module);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        $component = $this->getComponentSubmodule($module);
        $component_processor = $moduleprocessor_manager->getProcessor($component);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['component'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($component);

        // Re-use the label from the component
        if ($label = $this->getProp($module, $props, 'label')) {
            $ret['label'] = $label;
        }
        if ($label_class = $this->getLabelClass($module)) {
            $ret[GD_JS_CLASSES]['label'] = $label_class;
        }
        if ($formcontrol_class = $this->getFormcontrolClass($module)) {
            $ret[GD_JS_CLASSES]['formcontrol'] = $formcontrol_class;
        }
        if ($info = $this->getInfo($module, $props)) {
            $ret['info'] = $info;
        }
        if ($description = $this->getDescription($module, $props)) {
            $ret[GD_JS_DESCRIPTION] = $description;
        }

        // This property is needed for the inheriting class FormComponentGroupsBase, to print the name of the formcomponent
        $ret['component-name'] = $this->getComponentName($module);

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // No need for the input to have a label or a placeholder (for the text inputs) anymore
        $label = $this->getLabel($module, $props);
        $this->setProp($module, $props, 'label', $label);

        $this->appendProp($module, $props, 'class', 'form-group');
        parent::initModelProps($module, $props);
    }
}
