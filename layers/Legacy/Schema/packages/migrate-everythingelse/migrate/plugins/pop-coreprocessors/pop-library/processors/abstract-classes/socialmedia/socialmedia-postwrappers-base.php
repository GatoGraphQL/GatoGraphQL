<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

abstract class PoP_Module_Processor_SocialMediaPostWrapperBase extends PoP_Module_Processor_ConditionWrapperBase
{
    public function getSocialmediaComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        $ret[] = $this->getSocialmediaComponent($component);

        return $ret;
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'pop-hidden-print');
        parent::initModelProps($component, $props);
    }
}
