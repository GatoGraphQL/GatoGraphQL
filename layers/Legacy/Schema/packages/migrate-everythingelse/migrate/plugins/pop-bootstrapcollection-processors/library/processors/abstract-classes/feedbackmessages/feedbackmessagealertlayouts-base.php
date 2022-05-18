<?php

abstract class PoP_Module_Processor_FeedbackMessageAlertLayoutsBase extends PoP_Module_Processor_AlertsBase
{
    public function getLayoutSubmodule(array $component)
    {
        return null;
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);
        $ret[] = $this->getLayoutSubmodule($component);
        return $ret;
    }

    public function addFeedbackobjectClass(array $component, array &$props)
    {
        return true;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        $this->addJsmethod($ret, 'addDomainClass');
        return $ret;
    }

    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        // For function addDomainClass
        $ret['addDomainClass']['prefix'] = 'feedbackmessage-';

        return $ret;
    }

    public function getAlertClass(array $component, array &$props)
    {

        // If the block is multidomain, then make the feedbackmessage look smaller,
        // since many of them may pile up on top of each other, for each domain
        // (eg: no events for this website, no events for that website)
        // if ($this->get_general_prop($props, 'is-multidomain')) {
        if ($this->getProp($component, $props, 'multidomain')) {
            return 'alert-xs';
        }

        // The actual alert class will be added from the feedback-msg object
        return '';
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'pop-feedbackmessage');
        parent::initModelProps($component, $props);
    }
}
