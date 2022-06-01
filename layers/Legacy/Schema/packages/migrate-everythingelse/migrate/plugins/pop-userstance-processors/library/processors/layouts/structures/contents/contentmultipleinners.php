<?php

class UserStance_Module_Processor_ContentMultipleInners extends PoP_Module_Processor_ContentMultipleInnersBase
{
    public final const COMPONENT_LAYOUTCONTENTINNER_STANCES = 'contentinnerlayout-stances';
    public final const COMPONENT_LAYOUTCONTENTINNER_STANCES_APPENDABLE = 'contentinnerlayout-stances-appendable';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTCONTENTINNER_STANCES,
            self::COMPONENT_LAYOUTCONTENTINNER_STANCES_APPENDABLE,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUTCONTENTINNER_STANCES:
                $ret[] = [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED];
                break;

            case self::COMPONENT_LAYOUTCONTENTINNER_STANCES_APPENDABLE:
                // No need for anything, since this is the layout container, to be filled when the lazyload request comes back
                break;
        }

        return $ret;
    }
}


