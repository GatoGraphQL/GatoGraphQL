<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPSchema\CustomPosts\Types\Status;

class PoP_Module_Processor_ViewComponentButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public const MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT = 'viewcomponent-postbuttonwrapper-addcomment';
    public const MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT_BIG = 'viewcomponent-postbuttonwrapper-addcomment-big';
    public const MODULE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL = 'layoutwrapper-postconclusionsidebar-horizontal';
    public const MODULE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL = 'layoutwrapper-subjugatedpostconclusionsidebar-horizontal';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT_BIG],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL],
            [self::class, self::MODULE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT_BIG:
                $ret[] = [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG];
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT:
                $ret[] = [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT];
                break;

            case self::MODULE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
                $ret[] = [PoP_Module_Processor_PostLayoutSidebars::class, PoP_Module_Processor_PostLayoutSidebars::MODULE_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL];
                break;

            case self::MODULE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:
                $ret[] = [PoP_Module_Processor_PostLayoutSidebars::class, PoP_Module_Processor_PostLayoutSidebars::MODULE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT_BIG:
            case self::MODULE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
            case self::MODULE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
        }

        return null;
    }
}



