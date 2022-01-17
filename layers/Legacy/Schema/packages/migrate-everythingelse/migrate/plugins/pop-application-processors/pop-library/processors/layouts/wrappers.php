<?php

use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class PoP_Module_Processor_CustomWrapperLayouts extends PoP_Module_Processor_ConditionWrapperBase
{
    public const MODULE_LAYOUTWRAPPER_USERPOSTINTERACTION = 'layoutwrapper-userpostinteraction';
    public const MODULE_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION = 'layoutwrapper-userhighlightpostinteraction';
    public const MODULE_CODEWRAPPER_LAZYLOADINGSPINNER = 'codewrapper-lazyloadingspinner';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTWRAPPER_USERPOSTINTERACTION],
            [self::class, self::MODULE_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION],
            [self::class, self::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUTWRAPPER_USERPOSTINTERACTION:
                $ret[] = [Wassup_Module_Processor_UserPostInteractionLayouts::class, Wassup_Module_Processor_UserPostInteractionLayouts::MODULE_LAYOUT_USERPOSTINTERACTION];
                break;

            case self::MODULE_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:
                $ret[] = [Wassup_Module_Processor_UserPostInteractionLayouts::class, Wassup_Module_Processor_UserPostInteractionLayouts::MODULE_LAYOUT_USERHIGHLIGHTPOSTINTERACTION];
                break;

            case self::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER:
                $ret[] = [PoP_Module_Processor_LazyLoadingSpinnerLayouts::class, PoP_Module_Processor_LazyLoadingSpinnerLayouts::MODULE_LAYOUT_LAZYLOADINGSPINNER];
                break;
        }

        return $ret;
    }

    // function getConditionFailedSubmodules(array $module) {

    //     $ret = parent::getConditionFailedSubmodules($module);

    //     switch ($module[1]) {

    //         case self::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER:

    //             // This is needed because we need to print the id no matter what, since this module
    //             // will be referenced using previousmodules-ids in [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS_FULLVIEW], etc
    //             $ret[] = [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::MODULE_CODE_EMPTY];
    //             break;
    //     }

    //     return $ret;
    // }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUTWRAPPER_USERPOSTINTERACTION:
            case self::MODULE_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:
            case self::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
        }

        return null;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUTWRAPPER_USERPOSTINTERACTION:
            case self::MODULE_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:
                $this->appendProp($module, $props, 'class', 'userpostinteraction clearfix');
                break;

            case self::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER:
                $this->appendProp($module, $props, 'class', 'loadingmsg clearfix');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



