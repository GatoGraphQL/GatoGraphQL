<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class PoP_Module_Processor_ViewComponentButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT = 'viewcomponent-postbuttonwrapper-addcomment';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT_BIG = 'viewcomponent-postbuttonwrapper-addcomment-big';
    public final const COMPONENT_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL = 'layoutwrapper-postconclusionsidebar-horizontal';
    public final const COMPONENT_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL = 'layoutwrapper-subjugatedpostconclusionsidebar-horizontal';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT_BIG],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL],
        );
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT_BIG:
                $ret[] = [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG];
                break;

            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT:
                $ret[] = [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT];
                break;

            case self::COMPONENT_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
                $ret[] = [PoP_Module_Processor_PostLayoutSidebars::class, PoP_Module_Processor_PostLayoutSidebars::COMPONENT_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL];
                break;

            case self::COMPONENT_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:
                $ret[] = [PoP_Module_Processor_PostLayoutSidebars::class, PoP_Module_Processor_PostLayoutSidebars::COMPONENT_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL];
                break;
        }

        return $ret;
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT_BIG:
            case self::COMPONENT_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
            case self::COMPONENT_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
        }

        return null;
    }
}



