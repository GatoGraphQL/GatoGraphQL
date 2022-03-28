<?php
use PoP\ComponentModel\ModuleProcessors\FormComponentModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\FormInputModuleProcessorTrait;

abstract class PoP_Module_Processor_FormInputsBase extends PoPEngine_QueryDataModuleProcessorBase implements FormComponentModuleProcessorInterface
{
    use FormComponentValueTrait, FormInputModuleProcessorTrait;

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    public function getValueFormat(array $module, array &$props)
    {
        return null;
    }

    public function isHidden(array $module, array &$props)
    {
        return false;
    }

    public function getLabel(array $module, array &$props)
    {
        return $this->getLabelText($module, $props).($this->isMandatory($module, $props) ? GD_CONSTANT_MANDATORY : '');
    }

    public function isMandatory(array $module, array &$props)
    {
        if ($this->getProp($module, $props, 'mandatory')) {
            return true;
        }

        return false;
    }

    //-------------------------------------------------
    // OTHER Functions (Organize!)
    //-------------------------------------------------

    public function getLabelText(array $module, array &$props)
    {
        return '';
    }

    public function executeClearInput(array $module, array &$props)
    {
        if ($this->getProp($module, $props, 'pop-form-clear')) {
            return true;
        }

        return $this->clearInput($module, $props);
    }

    public function clearInput(array $module, array &$props)
    {
        return false;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($this->executeClearInput($module, $props)) {
            $this->addJsmethod($ret, 'clearInput');
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($this->isHidden($module, $props)) {
            $this->appendProp($module, $props, 'class', 'hidden');
        }

        $this->appendProp($module, $props, 'input-class', GD_FORM_INPUT);

        // first set mandatory, only then label, since label will use the value of mandatory
        // The label can be overridden (normally done by the FormGroup)
        $this->setProp($module, $props, 'label', $this->getLabel($module, $props));

        if ($this->getProp($module, $props, 'disabled')) {
            $this->appendProp($module, $props, 'class', 'disabled');
        }

        parent::initModelProps($module, $props);
    }

    // public function isFiltercomponent(array $module)
    // {
    //     return false;
    // }

    /**
     * @todo Migrate from string to LeafField
     *
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\LeafField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);
        $this->addMetaFormcomponentDataFields($ret, $module, $props);
        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $this->addMetaFormcomponentModuleConfiguration($ret, $module, $props);

        if ($value_format = $this->getValueFormat($module, $props)) {
            $ret['value-format'] = $value_format;
        }

        $ret[GD_JS_CLASSES]['input'] = $this->getProp($module, $props, 'input-class');

        $ret['name'] = $this->getInputName($module, $props);
        $ret['label'] = $this->getProp($module, $props, 'label');

        if ($this->getProp($module, $props, 'readonly')) {
            $ret['readonly'] = true;
        }
        if ($this->getProp($module, $props, 'disabled')) {
            $ret['disabled'] = true;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($module, $props);

        $this->addMetaFormcomponentModuleRuntimeconfiguration($ret, $module, $props);

        return $ret;
    }
}
