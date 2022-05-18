<?php

class PoPApplicationProcessors_Module_Processor_CommentScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS = 'layout-referencedbyscroll-inner-details';
    public final const MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW = 'layout-referencedbyscroll-inner-simpleview';
    public final const MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW = 'layout-referencedbyscroll-inner-fullview';
    public final const MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE = 'layout-referencedbyscroll-inner-appendable';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS],
            [self::class, self::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW],
            [self::class, self::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW],
            [self::class, self::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS:
            case self::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW:
            case self::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW:
            case self::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($module, $props);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS:
                $ret[] = [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_RELATED];
                break;

            case self::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW:
                $ret[] = [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_LINE];
                break;

            case self::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW:
                $ret[] = [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_ADDONS];
                break;

            case self::MODULE_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE:
                // No need for anything, since this is the layout container, to be filled when the lazyload request comes back
                break;
        }

        return $ret;
    }
}


