<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

abstract class PoP_Module_Processor_SocialMediaPostWrapperBase extends PoP_Module_Processor_ConditionWrapperBase
{
    public function getSocialmediaModule(array $module)
    {
        return null;
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        $ret[] = $this->getSocialmediaModule($module);

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->appendProp($module, $props, 'class', 'pop-hidden-print');
        parent::initModelProps($module, $props);
    }
}
