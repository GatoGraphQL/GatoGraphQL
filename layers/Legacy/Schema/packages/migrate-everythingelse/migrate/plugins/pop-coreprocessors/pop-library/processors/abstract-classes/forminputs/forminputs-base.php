<?php
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\FormInputComponentProcessorTrait;

abstract class PoP_Module_Processor_FormInputsBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentValueTrait, FormInputComponentProcessorTrait;

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    public function getValueFormat(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }

    public function isHidden(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return false;
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return $this->getLabelText($component, $props).($this->isMandatory($component, $props) ? GD_CONSTANT_MANDATORY : '');
    }

    public function isMandatory(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        if ($this->getProp($component, $props, 'mandatory')) {
            return true;
        }

        return false;
    }

    //-------------------------------------------------
    // OTHER Functions (Organize!)
    //-------------------------------------------------

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }

    public function executeClearInput(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        if ($this->getProp($component, $props, 'pop-form-clear')) {
            return true;
        }

        return $this->clearInput($component, $props);
    }

    public function clearInput(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return false;
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->executeClearInput($component, $props)) {
            $this->addJsmethod($ret, 'clearInput');
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        if ($this->isHidden($component, $props)) {
            $this->appendProp($component, $props, 'class', 'hidden');
        }

        $this->appendProp($component, $props, 'input-class', GD_FORM_INPUT);

        // first set mandatory, only then label, since label will use the value of mandatory
        // The label can be overridden (normally done by the FormGroup)
        $this->setProp($component, $props, 'label', $this->getLabel($component, $props));

        if ($this->getProp($component, $props, 'disabled')) {
            $this->appendProp($component, $props, 'class', 'disabled');
        }

        parent::initModelProps($component, $props);
    }

    // public function isFiltercomponent(\PoP\ComponentModel\Component\Component $component)
    // {
    //     return false;
    // }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getLeafComponentFields($component, $props);
        $this->addMetaFormcomponentDataFields($ret, $component, $props);
        return $ret;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $this->addMetaFormcomponentModuleConfiguration($ret, $component, $props);

        if ($value_format = $this->getValueFormat($component, $props)) {
            $ret['value-format'] = $value_format;
        }

        $ret[GD_JS_CLASSES]['input'] = $this->getProp($component, $props, 'input-class');

        $ret['name'] = $this->getInputName($component, $props);
        $ret['label'] = $this->getProp($component, $props, 'label');

        if ($this->getProp($component, $props, 'readonly')) {
            $ret['readonly'] = true;
        }
        if ($this->getProp($component, $props, 'disabled')) {
            $ret['disabled'] = true;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        $this->addMetaFormcomponentModuleRuntimeconfiguration($ret, $component, $props);

        return $ret;
    }
}
