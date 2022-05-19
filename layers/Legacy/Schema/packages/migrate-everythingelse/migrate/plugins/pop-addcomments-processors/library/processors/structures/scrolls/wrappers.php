<?php

class PoP_Module_Processor_CommentsWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_WIDGETWRAPPER_POSTCOMMENTS = 'widgetwrapper-postcomments';
    public final const COMPONENT_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT = 'widgetwrapper-postcomments-appendtoscript';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_WIDGETWRAPPER_POSTCOMMENTS],
            [self::class, self::COMPONENT_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT],
        );
    }

    public function getConditionSucceededSubcomponents(array $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_WIDGETWRAPPER_POSTCOMMENTS:
                $ret[] = [PoP_Module_Processor_CommentsWidgets::class, PoP_Module_Processor_CommentsWidgets::COMPONENT_WIDGET_POSTCOMMENTS];
                break;

            case self::COMPONENT_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT:
                $ret[] = [PoP_Module_Processor_CommentsWidgets::class, PoP_Module_Processor_CommentsWidgets::COMPONENT_WIDGET_POSTCOMMENTS_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETWRAPPER_POSTCOMMENTS:
            case self::COMPONENT_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT:
                $this->appendProp($component, $props, 'class', 'postcomments clearfix');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETWRAPPER_POSTCOMMENTS:
            case self::COMPONENT_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT:
                return 'hasComments';
        }

        return null;
    }
}



