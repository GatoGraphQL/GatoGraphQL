<?php

use PoP\Engine\ComponentProcessors\FormMultipleInputComponentProcessorTrait;
use PoP\Engine\FormInputs\DateRangeFormInput;
use PoP\Engine\FormInputs\DateRangeTimeFormInput;

abstract class PoP_Module_Processor_DateRangeFormInputsBase extends PoP_Module_Processor_FormInputsBase
{
    use FormMultipleInputComponentProcessorTrait;

    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_DATERANGE];
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        $this->addJsmethod($ret, 'dateRange');
        return $ret;
    }

    public function getInputSubnames(array $component): array
    {
        if ($this->useTime($component)) {
            return array('from', 'to', 'fromtime', 'totime', 'readable');
        }

        return array('from', 'to', 'readable');
    }

    public function getInputClass(array $component): string
    {
        if ($this->useTime($component)) {
            return DateRangeTimeFormInput::class;
        }

        return DateRangeFormInput::class;
    }

    public function getDbobjectField(array $component): ?string
    {
        if ($this->useTime($component)) {
            return 'daterangetime';
        }

        return 'daterange';
    }

    public function useTime(array $component)
    {
        return false;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($this->useTime($component)) {
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

        if ($placeholder = $this->getProp($component, $props, 'placeholder')) {
            $ret['placeholder'] = $placeholder;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Use the label as placeholder
        $this->setProp($component, $props, 'placeholder', $this->getLabel($component, $props));

        $this->appendProp($component, $props, 'class', 'make-daterangepicker');

        $this->setProp($component, $props, 'daterange-class', 'opens-right');
        $daterange_class = $this->getProp($component, $props, 'daterange-class');
        $this->appendProp($component, $props, 'class', $daterange_class);

        parent::initModelProps($component, $props);
    }
}
