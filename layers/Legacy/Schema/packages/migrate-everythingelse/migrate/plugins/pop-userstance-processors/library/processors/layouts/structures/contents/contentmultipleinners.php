<?php

class UserStance_Module_Processor_ContentMultipleInners extends PoP_Module_Processor_ContentMultipleInnersBase
{
    public final const COMPONENT_LAYOUTCONTENTINNER_STANCES = 'contentinnerlayout-stances';
    public final const COMPONENT_LAYOUTCONTENTINNER_STANCES_APPENDABLE = 'contentinnerlayout-stances-appendable';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTCONTENTINNER_STANCES],
            [self::class, self::COMPONENT_LAYOUTCONTENTINNER_STANCES_APPENDABLE],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
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


