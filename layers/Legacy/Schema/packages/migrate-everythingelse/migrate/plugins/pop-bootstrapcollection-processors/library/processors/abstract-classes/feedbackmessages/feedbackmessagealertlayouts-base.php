<?php

abstract class PoP_Module_Processor_FeedbackMessageAlertLayoutsBase extends PoP_Module_Processor_AlertsBase
{
    public function getLayoutSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);
        $ret[] = $this->getLayoutSubmodule($componentVariation);
        return $ret;
    }

    public function addFeedbackobjectClass(array $componentVariation, array &$props)
    {
        return true;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);
        $this->addJsmethod($ret, 'addDomainClass');
        return $ret;
    }

    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        // For function addDomainClass
        $ret['addDomainClass']['prefix'] = 'feedbackmessage-';

        return $ret;
    }

    public function getAlertClass(array $componentVariation, array &$props)
    {

        // If the block is multidomain, then make the feedbackmessage look smaller,
        // since many of them may pile up on top of each other, for each domain
        // (eg: no events for this website, no events for that website)
        // if ($this->get_general_prop($props, 'is-multidomain')) {
        if ($this->getProp($componentVariation, $props, 'multidomain')) {
            return 'alert-xs';
        }

        // The actual alert class will be added from the feedback-msg object
        return '';
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'class', 'pop-feedbackmessage');
        parent::initModelProps($componentVariation, $props);
    }
}
