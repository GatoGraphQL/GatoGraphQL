<?php

abstract class PoP_Module_Processor_FeedbackMessageAlertLayoutsBase extends PoP_Module_Processor_AlertsBase
{
    public function getLayoutSubmodule(array $module)
    {
        return null;
    }
    
    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);
        $ret[] = $this->getLayoutSubmodule($module);
        return $ret;
    }

    public function addFeedbackobjectClass(array $module, array &$props)
    {
        return true;
    }
    
    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
        $this->addJsmethod($ret, 'addDomainClass');
        return $ret;
    }

    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        // For function addDomainClass
        $ret['addDomainClass']['prefix'] = 'feedbackmessage-';

        return $ret;
    }

    public function getAlertClass(array $module, array &$props)
    {

        // If the block is multidomain, then make the feedbackmessage look smaller,
        // since many of them may pile up on top of each other, for each domain
        // (eg: no events for this website, no events for that website)
        // if ($this->get_general_prop($props, 'is-multidomain')) {
        if ($this->getProp($module, $props, 'multidomain')) {
            return 'alert-xs';
        }

        // The actual alert class will be added from the feedback-msg object
        return '';
    }

    public function initModelProps(array $module, array &$props)
    {
        $this->appendProp($module, $props, 'class', 'pop-feedbackmessage');
        parent::initModelProps($module, $props);
    }
}
