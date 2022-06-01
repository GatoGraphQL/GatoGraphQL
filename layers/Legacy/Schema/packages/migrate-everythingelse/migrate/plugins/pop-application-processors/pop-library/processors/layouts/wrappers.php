<?php

use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class PoP_Module_Processor_CustomWrapperLayouts extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_LAYOUTWRAPPER_USERPOSTINTERACTION = 'layoutwrapper-userpostinteraction';
    public final const COMPONENT_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION = 'layoutwrapper-userhighlightpostinteraction';
    public final const COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER = 'codewrapper-lazyloadingspinner';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTWRAPPER_USERPOSTINTERACTION,
            self::COMPONENT_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION,
            self::COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER,
        );
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUTWRAPPER_USERPOSTINTERACTION:
                $ret[] = [Wassup_Module_Processor_UserPostInteractionLayouts::class, Wassup_Module_Processor_UserPostInteractionLayouts::COMPONENT_LAYOUT_USERPOSTINTERACTION];
                break;

            case self::COMPONENT_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:
                $ret[] = [Wassup_Module_Processor_UserPostInteractionLayouts::class, Wassup_Module_Processor_UserPostInteractionLayouts::COMPONENT_LAYOUT_USERHIGHLIGHTPOSTINTERACTION];
                break;

            case self::COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER:
                $ret[] = [PoP_Module_Processor_LazyLoadingSpinnerLayouts::class, PoP_Module_Processor_LazyLoadingSpinnerLayouts::COMPONENT_LAYOUT_LAZYLOADINGSPINNER];
                break;
        }

        return $ret;
    }

    // function getConditionFailedSubcomponents(\PoP\ComponentModel\Component\Component $component) {

    //     $ret = parent::getConditionFailedSubcomponents($component);

    //     switch ($component->name) {

    //         case self::COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER:

    //             // This is needed because we need to print the id no matter what, since this module
    //             // will be referenced using previouscomponents-ids in [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS_FULLVIEW], etc
    //             $ret[] = [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::COMPONENT_CODE_EMPTY];
    //             break;
    //     }

    //     return $ret;
    // }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUTWRAPPER_USERPOSTINTERACTION:
            case self::COMPONENT_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:
            case self::COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
        }

        return null;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUTWRAPPER_USERPOSTINTERACTION:
            case self::COMPONENT_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:
                $this->appendProp($component, $props, 'class', 'userpostinteraction clearfix');
                break;

            case self::COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER:
                $this->appendProp($component, $props, 'class', 'loadingmsg clearfix');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



