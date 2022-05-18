<?php

use PoP\Engine\ComponentProcessors\FormMultipleInputComponentProcessorTrait;
use PoP\Engine\FormInputs\DateRangeFormInput;
use PoP\Engine\FormInputs\DateRangeTimeFormInput;

abstract class PoP_Module_Processor_DateRangeFormInputsBase extends PoP_Module_Processor_FormInputsBase
{
    use FormMultipleInputComponentProcessorTrait;

    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_DATERANGE];
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);
        $this->addJsmethod($ret, 'dateRange');
        return $ret;
    }

    public function getInputSubnames(array $componentVariation): array
    {
        if ($this->useTime($componentVariation)) {
            return array('from', 'to', 'fromtime', 'totime', 'readable');
        }

        return array('from', 'to', 'readable');
    }

    public function getInputClass(array $componentVariation): string
    {
        if ($this->useTime($componentVariation)) {
            return DateRangeTimeFormInput::class;
        }

        return DateRangeFormInput::class;
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        if ($this->useTime($componentVariation)) {
            return 'daterangetime';
        }

        return 'daterange';
    }

    public function useTime(array $componentVariation)
    {
        return false;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($this->useTime($componentVariation)) {
            $ret['timepicker'] = 'timepicker';
            $ret['subfields'] = array(
                'from' => array('from'),
                'to' => array('to'),
                'fromtime' => array('fromtime'),
                'totime' => array('totime'),
                'readable' => array('readable'),
            );
        } else {
            $ret['subfields'] = array(
                'from' => array('from'),
                'to' => array('to'),
                'readable' => array('readable'),
            );
        }

        if ($placeholder = $this->getProp($componentVariation, $props, 'placeholder')) {
            $ret['placeholder'] = $placeholder;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Use the label as placeholder
        $this->setProp($componentVariation, $props, 'placeholder', $this->getLabel($componentVariation, $props));

        $this->appendProp($componentVariation, $props, 'class', 'make-daterangepicker');

        $this->setProp($componentVariation, $props, 'daterange-class', 'opens-right');
        $daterange_class = $this->getProp($componentVariation, $props, 'daterange-class');
        $this->appendProp($componentVariation, $props, 'class', $daterange_class);

        parent::initModelProps($componentVariation, $props);
    }
}
