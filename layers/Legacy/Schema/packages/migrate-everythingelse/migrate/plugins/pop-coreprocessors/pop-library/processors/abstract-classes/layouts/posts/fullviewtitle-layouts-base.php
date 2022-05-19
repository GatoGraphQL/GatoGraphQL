<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

abstract class PoP_Module_Processor_FullViewTitleLayoutsBase extends PoP_Module_Processor_FullObjectTitleLayoutsBase
{
    public function getTitleField(array $component, array &$props)
    {
        return 'title';
    }

    public function getTitleattrField(array $component, array &$props)
    {
        return 'alt';
    }

    public function getTitleConditionField(array $component, array &$props)
    {
        return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
    }
}
