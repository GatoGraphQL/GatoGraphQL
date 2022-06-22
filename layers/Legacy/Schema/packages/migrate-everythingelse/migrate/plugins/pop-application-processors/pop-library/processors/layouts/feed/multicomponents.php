<?php

class PoP_Module_Processor_MultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTICOMPONENT_USERPOSTACTIVITY_SIMPLEVIEW = 'multicomponent-userpostactivity-simpleview';
    public final const COMPONENT_MULTICOMPONENT_USERPOSTACTIVITY_LAZYSIMPLEVIEW = 'multicomponent-userpostactivity-lazysimpleview';
    public final const COMPONENT_MULTICOMPONENT_USERPOSTACTIVITY = 'multicomponent-userpostactivity';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTICOMPONENT_USERPOSTACTIVITY_SIMPLEVIEW,
            self::COMPONENT_MULTICOMPONENT_USERPOSTACTIVITY_LAZYSIMPLEVIEW,
            self::COMPONENT_MULTICOMPONENT_USERPOSTACTIVITY,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_MULTICOMPONENT_USERPOSTACTIVITY_SIMPLEVIEW:
                $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_SIMPLEVIEW];
                $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW];
                $ret[] = [PoP_Module_Processor_CommentsWrappers::class, PoP_Module_Processor_CommentsWrappers::COMPONENT_WIDGETWRAPPER_POSTCOMMENTS];
                break;

            case self::COMPONENT_MULTICOMPONENT_USERPOSTACTIVITY_LAZYSIMPLEVIEW:
                $ret[] = self::COMPONENT_MULTICOMPONENT_USERPOSTACTIVITY;
                $ret[] = [PoP_Module_Processor_FeedButtonWrappers::class, PoP_Module_Processor_FeedButtonWrappers::COMPONENT_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY];
                break;

            case self::COMPONENT_MULTICOMPONENT_USERPOSTACTIVITY:
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER];
                $ret[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS];
                $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_REFERENCEDBY];
                $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_LAZYSUBCOMPONENT_POSTCOMMENTS];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_MULTICOMPONENT_USERPOSTACTIVITY_LAZYSIMPLEVIEW:
                // Make the User Post Interaction group a collapse, initially collapsed
                $this->appendProp(self::COMPONENT_MULTICOMPONENT_USERPOSTACTIVITY, $props, 'class', 'collapse');

                // Indicate the button what collapse to open
                $this->setProp([PoP_Module_Processor_FeedButtons::class, PoP_Module_Processor_FeedButtons::COMPONENT_BUTTON_TOGGLEUSERPOSTACTIVITY], $props, 'target-component', self::COMPONENT_MULTICOMPONENT_USERPOSTACTIVITY);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



