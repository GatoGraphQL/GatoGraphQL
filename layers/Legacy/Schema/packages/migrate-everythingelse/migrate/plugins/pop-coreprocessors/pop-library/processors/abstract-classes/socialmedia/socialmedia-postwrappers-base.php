<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

abstract class PoP_Module_Processor_SocialMediaPostWrapperBase extends PoP_Module_Processor_ConditionWrapperBase
{
    public function getSocialmediaModule(array $component)
    {
        return null;
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        $ret[] = $this->getSocialmediaModule($component);

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'pop-hidden-print');
        parent::initModelProps($component, $props);
    }
}
