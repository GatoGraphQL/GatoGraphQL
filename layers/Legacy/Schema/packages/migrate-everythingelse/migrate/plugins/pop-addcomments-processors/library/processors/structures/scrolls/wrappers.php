<?php

class PoP_Module_Processor_CommentsWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_WIDGETWRAPPER_POSTCOMMENTS = 'widgetwrapper-postcomments';
    public final const MODULE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT = 'widgetwrapper-postcomments-appendtoscript';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGETWRAPPER_POSTCOMMENTS],
            [self::class, self::MODULE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_WIDGETWRAPPER_POSTCOMMENTS:
                $ret[] = [PoP_Module_Processor_CommentsWidgets::class, PoP_Module_Processor_CommentsWidgets::MODULE_WIDGET_POSTCOMMENTS];
                break;

            case self::MODULE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT:
                $ret[] = [PoP_Module_Processor_CommentsWidgets::class, PoP_Module_Processor_CommentsWidgets::MODULE_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETWRAPPER_POSTCOMMENTS:
            case self::MODULE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT:
                $this->appendProp($module, $props, 'class', 'postcomments clearfix');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETWRAPPER_POSTCOMMENTS:
            case self::MODULE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT:
                return 'hasComments';
        }

        return null;
    }
}



