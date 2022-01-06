<?php

use PoP\Engine\ModuleProcessors\FormMultipleInputModuleProcessorTrait;
use PoP\Engine\FormInputs\DateRangeFormInput;
use PoP\Engine\FormInputs\DateRangeTimeFormInput;

abstract class PoP_Module_Processor_DateRangeFormInputsBase extends PoP_Module_Processor_FormInputsBase
{
    use FormMultipleInputModuleProcessorTrait;

    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_DATERANGE];
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
        $this->addJsmethod($ret, 'dateRange');
        return $ret;
    }

    public function getInputSubnames(array $module): array
    {
        if ($this->useTime($module)) {
            return array('from', 'to', 'fromtime', 'totime', 'readable');
        }

        return array('from', 'to', 'readable');
    }

    public function getInputClass(array $module): string
    {
        if ($this->useTime($module)) {
            return DateRangeTimeFormInput::class;
        }

        return DateRangeFormInput::class;
    }

    public function getDbobjectField(array $module)
    {
        if ($this->useTime($module)) {
            return 'daterangetime';
        }

        return 'daterange';
    }

    public function useTime(array $module)
    {
        return false;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($this->useTime($module)) {
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

        if ($placeholder = $this->getProp($module, $props, 'placeholder')) {
            $ret['placeholder'] = $placeholder;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Use the label as placeholder
        $this->setProp($module, $props, 'placeholder', $this->getLabel($module, $props));

        $this->appendProp($module, $props, 'class', 'make-daterangepicker');

        $this->setProp($module, $props, 'daterange-class', 'opens-right');
        $daterange_class = $this->getProp($module, $props, 'daterange-class');
        $this->appendProp($module, $props, 'class', $daterange_class);

        parent::initModelProps($module, $props);
    }
}
