<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;

abstract class PoP_Module_Processor_TypeaheadFormComponentsBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getFormcomponentModule(array $module)
    {
        return $this->getInputSubmodule($module);
    }

    public function getComponentSubmodules(array $module)
    {
        return array();
    }
    public function getInputSubmodule(array $module)
    {
        return [PoP_Module_Processor_TypeaheadTextFormInputs::class, PoP_Module_Processor_TypeaheadTextFormInputs::MODULE_FORMINPUT_TEXT_TYPEAHEAD];
    }

    public function getLabel(array $module, array &$props)
    {
        $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($module);
        $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $input = $this->getInputSubmodule($module);

        // Because getLabel is used on initModelProps, the structure in $props for the submodule may not be created yet, throwing an error since then it's null
        // Just for this case, pass another array, not $props
        $submodule_props = [];
        if ($props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES]) {
            $submodule_props = &$props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES];
        }
        return $moduleprocessor_manager->getProcessor($input)->getLabel($input, $submodule_props);
    }

    public function initRequestProps(array $module, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($module, $props);
        parent::initRequestProps($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $typeahead_class = $this->getTypeaheadClass($module, $props);
        $this->appendProp($module, $props, 'class', $typeahead_class);

        // Make the input hidden? This is needed when using the typeahead to pre-select a value,
        // but we don't want the user to keep selecting more. Eg: Add Highlight
        $input = $this->getInputSubmodule($module);
        $this->appendProp($input, $props, 'class', 'typeahead');

        // Transfer properties down the line
        $label = $this->getProp($module, $props, 'label');
        if (!is_null($label)) {
            $this->setProp($input, $props, 'label', $label);
        }
        $placeholder = $this->getProp($module, $props, 'placeholder');
        if (!is_null($placeholder)) {
            $this->setProp($input, $props, 'placeholder', $placeholder);
        }

        parent::initModelProps($module, $props);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $input = $this->getInputSubmodule($module);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['input'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($input);

        return $ret;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        return array_merge(
            $ret,
            $this->getComponentSubmodules($module),
            array(
                $this->getInputSubmodule($module)
            )
        );
    }
    public function getTypeaheadClass(array $module, array &$props)
    {
        return 'pop-typeahead';
    }
    public function getTypeaheadJsmethod(array $module, array &$props)
    {
        return null;
    }

    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        if ($typeahead_jsmethod = $this->getTypeaheadJsmethod($module, $props)) {
            $ret[$typeahead_jsmethod]['dataset-components'] = $this->getComponentSubmodules($module);
        }

        return $ret;
    }

    public function getModulesToPropagateDataProperties(array $module): array
    {

        // Important: the TYPEAHEAD_COMPONENT (eg: PoP_Module_Processor_UserTypeaheadComponentLayouts::MODULE_LAYOUTUSER_TYPEAHEAD_COMPONENT) should not have data-fields, because it doesn't apply to {{blockSettings.dataset}}
        // but it applies to Twitter Typeahead, which doesn't need these parameters, however these, here, upset the whole getDatasetmoduletreeSectionFlattenedDataFields
        // To fix this, in self::MODULE_FORMINPUT_TYPEAHEAD data_properties we stop spreading down, so it never reaches below there to get further data-fields
        return array_values(
            array_diff(
                parent::getModulesToPropagateDataProperties($module),
                $this->getComponentSubmodules($module)
            )
        );
    }
}
