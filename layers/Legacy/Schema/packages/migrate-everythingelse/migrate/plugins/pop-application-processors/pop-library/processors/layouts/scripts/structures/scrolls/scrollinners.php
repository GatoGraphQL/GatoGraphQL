<?php

class PoPApplicationProcessors_Module_Processor_CommentScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS = 'layout-referencedbyscroll-inner-details';
    public final const COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW = 'layout-referencedbyscroll-inner-simpleview';
    public final const COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW = 'layout-referencedbyscroll-inner-fullview';
    public final const COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE = 'layout-referencedbyscroll-inner-appendable';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS],
            [self::class, self::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW],
            [self::class, self::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW],
            [self::class, self::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS:
            case self::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW:
            case self::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW:
            case self::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS:
                $ret[] = [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_RELATED];
                break;

            case self::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW:
                $ret[] = [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_LINE];
                break;

            case self::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW:
                $ret[] = [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_ADDONS];
                break;

            case self::COMPONENT_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE:
                // No need for anything, since this is the layout container, to be filled when the lazyload request comes back
                break;
        }

        return $ret;
    }
}


