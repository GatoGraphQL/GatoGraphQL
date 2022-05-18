<?php

class PoP_Module_Processor_MultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTICOMPONENT_USERPOSTACTIVITY_SIMPLEVIEW = 'multicomponent-userpostactivity-simpleview';
    public final const MODULE_MULTICOMPONENT_USERPOSTACTIVITY_LAZYSIMPLEVIEW = 'multicomponent-userpostactivity-lazysimpleview';
    public final const MODULE_MULTICOMPONENT_USERPOSTACTIVITY = 'multicomponent-userpostactivity';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTICOMPONENT_USERPOSTACTIVITY_SIMPLEVIEW],
            [self::class, self::MODULE_MULTICOMPONENT_USERPOSTACTIVITY_LAZYSIMPLEVIEW],
            [self::class, self::MODULE_MULTICOMPONENT_USERPOSTACTIVITY],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_USERPOSTACTIVITY_SIMPLEVIEW:
                $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_WIDGETWRAPPER_HIGHLIGHTS_SIMPLEVIEW];
                $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW];
                $ret[] = [PoP_Module_Processor_CommentsWrappers::class, PoP_Module_Processor_CommentsWrappers::MODULE_WIDGETWRAPPER_POSTCOMMENTS];
                break;

            case self::MODULE_MULTICOMPONENT_USERPOSTACTIVITY_LAZYSIMPLEVIEW:
                $ret[] = [self::class, self::MODULE_MULTICOMPONENT_USERPOSTACTIVITY];
                $ret[] = [PoP_Module_Processor_FeedButtonWrappers::class, PoP_Module_Processor_FeedButtonWrappers::MODULE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY];
                break;

            case self::MODULE_MULTICOMPONENT_USERPOSTACTIVITY:
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER];
                $ret[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS];
                $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::MODULE_LAZYSUBCOMPONENT_REFERENCEDBY];
                $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_USERPOSTACTIVITY_LAZYSIMPLEVIEW:
                // Make the User Post Interaction group a collapse, initially collapsed
                $this->appendProp([self::class, self::MODULE_MULTICOMPONENT_USERPOSTACTIVITY], $props, 'class', 'collapse');

                // Indicate the button what collapse to open
                $this->setProp([PoP_Module_Processor_FeedButtons::class, PoP_Module_Processor_FeedButtons::MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY], $props, 'target-module', [self::class, self::MODULE_MULTICOMPONENT_USERPOSTACTIVITY]);
                break;
        }

        parent::initModelProps($module, $props);
    }
}



