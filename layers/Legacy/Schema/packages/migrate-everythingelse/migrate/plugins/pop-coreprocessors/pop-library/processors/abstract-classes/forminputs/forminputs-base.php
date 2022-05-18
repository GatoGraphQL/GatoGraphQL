<?php
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\FormInputComponentProcessorTrait;

abstract class PoP_Module_Processor_FormInputsBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentValueTrait, FormInputComponentProcessorTrait;

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    public function getValueFormat(array $componentVariation, array &$props)
    {
        return null;
    }

    public function isHidden(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        return $this->getLabelText($componentVariation, $props).($this->isMandatory($componentVariation, $props) ? GD_CONSTANT_MANDATORY : '');
    }

    public function isMandatory(array $componentVariation, array &$props)
    {
        if ($this->getProp($componentVariation, $props, 'mandatory')) {
            return true;
        }

        return false;
    }

    //-------------------------------------------------
    // OTHER Functions (Organize!)
    //-------------------------------------------------

    public function getLabelText(array $componentVariation, array &$props)
    {
        return '';
    }

    public function executeClearInput(array $componentVariation, array &$props)
    {
        if ($this->getProp($componentVariation, $props, 'pop-form-clear')) {
            return true;
        }

        return $this->clearInput($componentVariation, $props);
    }

    public function clearInput(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($this->executeClearInput($componentVariation, $props)) {
            $this->addJsmethod($ret, 'clearInput');
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($this->isHidden($componentVariation, $props)) {
            $this->appendProp($componentVariation, $props, 'class', 'hidden');
        }

        $this->appendProp($componentVariation, $props, 'input-class', GD_FORM_INPUT);

        // first set mandatory, only then label, since label will use the value of mandatory
        // The label can be overridden (normally done by the FormGroup)
        $this->setProp($componentVariation, $props, 'label', $this->getLabel($componentVariation, $props));

        if ($this->getProp($componentVariation, $props, 'disabled')) {
            $this->appendProp($componentVariation, $props, 'class', 'disabled');
        }

        parent::initModelProps($componentVariation, $props);
    }

    // public function isFiltercomponent(array $componentVariation)
    // {
    //     return false;
    // }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);
        $this->addMetaFormcomponentDataFields($ret, $componentVariation, $props);
        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $this->addMetaFormcomponentModuleConfiguration($ret, $componentVariation, $props);

        if ($value_format = $this->getValueFormat($componentVariation, $props)) {
            $ret['value-format'] = $value_format;
        }

        $ret[GD_JS_CLASSES]['input'] = $this->getProp($componentVariation, $props, 'input-class');

        $ret['name'] = $this->getInputName($componentVariation, $props);
        $ret['label'] = $this->getProp($componentVariation, $props, 'label');

        if ($this->getProp($componentVariation, $props, 'readonly')) {
            $ret['readonly'] = true;
        }
        if ($this->getProp($componentVariation, $props, 'disabled')) {
            $ret['disabled'] = true;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($componentVariation, $props);

        $this->addMetaFormcomponentModuleRuntimeconfiguration($ret, $componentVariation, $props);

        return $ret;
    }
}
