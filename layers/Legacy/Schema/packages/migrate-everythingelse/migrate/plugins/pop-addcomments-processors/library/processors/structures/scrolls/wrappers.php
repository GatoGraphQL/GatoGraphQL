<?php

class PoP_Module_Processor_CommentsWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_WIDGETWRAPPER_POSTCOMMENTS = 'widgetwrapper-postcomments';
    public final const MODULE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT = 'widgetwrapper-postcomments-appendtoscript';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGETWRAPPER_POSTCOMMENTS],
            [self::class, self::MODULE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT],
        );
    }

    public function getConditionSucceededSubmodules(array $componentVariation)
    {
        $ret = parent::getConditionSucceededSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETWRAPPER_POSTCOMMENTS:
                $ret[] = [PoP_Module_Processor_CommentsWidgets::class, PoP_Module_Processor_CommentsWidgets::MODULE_WIDGET_POSTCOMMENTS];
                break;

            case self::MODULE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT:
                $ret[] = [PoP_Module_Processor_CommentsWidgets::class, PoP_Module_Processor_CommentsWidgets::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETWRAPPER_POSTCOMMENTS:
            case self::MODULE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT:
                $this->appendProp($componentVariation, $props, 'class', 'postcomments clearfix');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getConditionField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_WIDGETWRAPPER_POSTCOMMENTS:
            case self::MODULE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT:
                return 'hasComments';
        }

        return null;
    }
}



